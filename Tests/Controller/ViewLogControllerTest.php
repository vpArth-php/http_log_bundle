<?php

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Tests\Functional\HttpLogBundleKernel;

class ViewLogControllerTest extends TestCase
{
  public function testViewLogAction(): void
  {
    $kernel  = new HttpLogBundleKernel();
    $client  = new KernelBrowser($kernel);
    $crawler = $client->request('GET', '/admin/http-log/');

    self::assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());

    $table = $crawler->filter('table');
    self::assertCount(6, $table->filter('thead th'));
    self::assertCount(0, $table->filter('tbody tr'));
  }
}
