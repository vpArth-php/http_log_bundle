<?php

namespace Tests\Functional;

use Psr\Log\NullLogger;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;
use UmaTech\HttpLogBundle\HttpLogBundle;
use function spl_object_hash;
use function sys_get_temp_dir;

class HttpLogBundleKernel extends Kernel
{
  use MicroKernelTrait;

  public function __construct()
  {
    parent::__construct('test', true);
  }

  public function registerBundles()
  {
    return [
        new FrameworkBundle(),
        new HttpLogBundle(),
    ];
  }
  protected function configureRoutes(RoutingConfigurator $routes)
  {
    $routes->import(dirname(__DIR__, 2) . '/Resources/config/routing/view-log.yaml')
        ->prefix('/admin/http-log');
    $routes->add('_default', '/')->controller('kernel::defaultController');
  }
  protected function build(ContainerBuilder $container)
  {
    // $container->register('data_collector.dump', DumpDataCollector::class);
    $container->register('logger', NullLogger::class);
  }

  public function getCacheDir()
  {
    return sys_get_temp_dir() . '/cache-' . spl_object_hash($this);
  }

  public function getLogDir()
  {
    return sys_get_temp_dir() . '/log-' . spl_object_hash($this);
  }

  protected function configureContainer(ContainerBuilder $containerBuilder, LoaderInterface $loader): void
  {
    $containerBuilder->loadFromExtension('http_log', [
        'enabled' => true,
        'kind'    => 'get',
        'name'    => '__http_log',
    ]);
  }
  public function defaultController(Request $request): JsonResponse
  {
    return new JsonResponse([
        'headers' => $request->headers->all(),
        'query'   => $request->query->all(),
        'body'    => $request->getContent(),
    ]);
  }
}
