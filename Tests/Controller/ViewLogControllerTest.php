<?php

namespace Tests\Controller;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Tests\Functional\HttpLogBundleKernel;

class ViewLogControllerTest extends TestCase
{
  public function testViewLogAction(): void
  {
    $kernel = new HttpLogBundleKernel();
    $client = new KernelBrowser($kernel);
    $client->request('GET', '/admin/http-log/');

    self::assertTrue($client->getResponse()->isSuccessful(), $client->getResponse()->getContent());
    self::assertStringContainsString('in progress', $client->getResponse()->getContent());
  }
}
