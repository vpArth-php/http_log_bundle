<?php

namespace Tests\DependencyInjection;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\KernelInterface;
use UmaTech\HttpLogBundle\DependencyInjection\HttpLogExtension;

class HttpLogExtensionTest extends TestCase
{
  /** @var MockObject|KernelInterface */
  private $kernel;
  /** @var ContainerBuilder */
  private $container;

  protected function setUp(): void
  {
    parent::setUp();
    $this->kernel    = $this->createMock(KernelInterface::class);
    $this->container = new ContainerBuilder();
    $this->container->set('kernel', $this->kernel);
  }

  /** @dataProvider getConfig */
  public function testConfig(array $config)
  {
    $enabled   = $config['enabled'];
    $extension = new HttpLogExtension();
    $extension->load([$config], $this->container);

    self::assertSame($enabled, $this->container->has('umatech.http_loader.listener'));
  }

  public function getConfig()
  {
    yield [['enabled' => true]];
    yield [['enabled' => false]];
  }
}
