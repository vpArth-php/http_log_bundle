<?php

namespace Tests\Functional;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Component\DependencyInjection\ContainerInterface;
use UmaTech\HttpLogBundle\Service\DeciderInterface;
use UmaTech\HttpLogBundle\Service\PersisterInterface;
use function json_decode;

class ExtensionFunctionalTest extends TestCase
{
  /** @var KernelBrowser */
  private $client;

  /** @var ContainerInterface */
  private $container;

  /** @var MockObject|DeciderInterface */
  private $decider;

  /** @var MockObject|PersisterInterface */
  private $persister;

  protected function setUp(): void
  {
    parent::setUp();

    $kernel = new HttpLogBundleKernel();
    $client = new KernelBrowser($kernel);
    $kernel->boot();

    $decider   = $this->createMock(DeciderInterface::class);
    $persister = $this->createMock(PersisterInterface::class);

    $container = $kernel->getContainer();
    $container->set(DeciderInterface::class, $decider);
    $container->set(PersisterInterface::class, $persister);

    $this->client    = $client;
    $this->container = $container;
    $this->decider   = $decider;
    $this->persister = $persister;
  }

  public function testNoNeedLog(): void
  {
    $this->decider->expects(self::once())
        ->method('needLog')
        ->willReturn(false);

    $this->persister->expects(self::never())
        ->method('persist');

    $this->client->request('GET', '/?q=42');

    self::assertTrue($this->client->getResponse()->isSuccessful());
    $body = $this->client->getResponse()->getContent();
    $json = json_decode($body, true);

    self::assertEquals('42', $json['query']['q']);
  }

  public function testNeedLog(): void
  {
    $this->decider->expects(self::once())
        ->method('needLog')
        ->willReturn(true);

    $this->persister->expects(self::once())
        ->method('persist');

    $this->client->request('GET', '/?q=42');

    self::assertTrue($this->client->getResponse()->isSuccessful());
    $body = $this->client->getResponse()->getContent();
    $json = json_decode($body, true);

    self::assertEquals('42', $json['query']['q']);
  }

}
