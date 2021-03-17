<?php

namespace Tests\Controller;

use DateTimeImmutable;
use JsonSerializable;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Tests\Functional\HttpLogBundleKernel;
use UmaTech\HttpLogBundle\Entity\HttpLogRecordInterface;
use UmaTech\HttpLogBundle\Service\HttpRecordRepositoryInterface;
use function json_decode;
use function ucfirst;

class ViewLogControllerTest extends TestCase
{
  /** @var HttpLogBundleKernel */
  private $kernel;
  /** @var KernelBrowser */
  private $client;
  /** @var MockObject|HttpRecordRepositoryInterface */
  private $repo;

  protected function setUp(): void
  {
    $this->kernel = new HttpLogBundleKernel();
    $this->client = new KernelBrowser($this->kernel);
    $this->kernel->boot();

    $this->repo = $this->createMock(HttpRecordRepositoryInterface::class);
    $this->kernel->getContainer()->set(HttpRecordRepositoryInterface::class, $this->repo);
  }
  public function testViewLogEmpty(): void
  {
    $crawler = $this->client->request('GET', '/admin/http-log/');

    self::assertTrue($this->client->getResponse()->isSuccessful(), $this->client->getResponse()->getContent());

    $table = $crawler->filter('table');
    self::assertCount(6, $table->filter('thead th'));
    self::assertCount(0, $table->filter('tbody tr'));
  }
  public function testViewLogNotEmpty(): void
  {
    $queryTime = '2020-07-13 13:14:15';
    $aData     = [
        'status'    => 200,
        'ip'        => '127.127.0.1',
        'uri'       => 'http://example.org/test',
        'request'   => "header: 1\r\nheader: 2\r\n\r\nbody",
        'response'  => "header: 1\r\nheader: 2\r\n\r\nbody",
        'queryTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $queryTime),
    ];
    $this->repo->expects(self::once())
        ->method('findPaged')
        ->willReturn([$this->getRecordStub($aData)]);

    $crawler = $this->client->request('GET', '/admin/http-log/');

    self::assertTrue($this->client->getResponse()->isSuccessful());

    $table = $crawler->filter('table');
    self::assertCount(6, $table->filter('thead th'));
    $rows = $table->filter('tbody tr');
    self::assertCount(1, $rows);
    $cells = $rows->filter('td');
    self::assertCount(6, $cells);
    self::assertEquals($aData['uri'], $cells->eq(0)->text());
    self::assertEquals($aData['request'], trim($cells->eq(1)->text(null, false)));
    self::assertEquals($aData['response'], trim($cells->eq(2)->text(null, false)));
    self::assertEquals($aData['status'], $cells->eq(3)->text());
    self::assertEquals($aData['ip'], $cells->eq(4)->text());
    self::assertEquals($queryTime, $cells->eq(5)->text());
  }
  public function testViewLogNotEmptyJson(): void
  {
    $queryTime = '2020-07-13 13:14:15';
    $aData     = [
        'id'        => 101,
        'status'    => 200,
        'ip'        => '127.127.0.1',
        'uri'       => 'http://example.org/test',
        'request'   => "header: 1\r\nheader: 2\r\n\r\nbody",
        'response'  => "header: 1\r\nheader: 2\r\n\r\nbody",
        'queryTime' => DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $queryTime),
    ];
    $this->repo->expects(self::once())
        ->method('findPaged')
        ->willReturn([$this->getRecordStub($aData)]);

    $this->client->request('GET', '/admin/http-log/', [], [], [
        'HTTP_ACCEPT' => 'application/json, text/html, */*',
    ]);

    self::assertTrue($this->client->getResponse()->isSuccessful());

    self::assertEquals([
        'entries' => [
            ['queryTime' => $aData['queryTime']->format('Y-m-d H:i:s')] + $aData],
    ], json_decode($this->client->getResponse()->getContent(), true));
  }
  private function getRecordStub($values): HttpLogRecordInterface
  {
    /** @var HttpLogRecordInterface|JsonSerializable|MockObject $rec */
    $rec = $this->createMock(HttpLogRecordInterface::class);

    foreach ($values as $field => $value) {
      $getter = 'get' . ucfirst($field);
      $rec->method($getter)
          ->willReturn($value);
    }
    $rec->method('jsonSerialize')
        ->willReturn(['queryTime' => $values['queryTime']->format('Y-m-d H:i:s')] + $values);

    return $rec;
  }
}
