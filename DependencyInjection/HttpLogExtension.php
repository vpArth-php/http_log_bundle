<?php

namespace UmaTech\HttpLogBundle\DependencyInjection;

use Symfony\Component\Config\Exception\FileLocatorFileNotFoundException;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class HttpLogExtension extends Extension
{
  public function load(array $configs, ContainerBuilder $container)
  {
    $configuration = $this->getConfiguration($configs, $container);
    /** @noinspection NullPointerExceptionInspection */
    $config = $this->processConfiguration($configuration, $configs);
    $container->setParameter('umatech.http_loader.enabled', $config['enabled']);
    if ($config['enabled']) {
      $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__) . '/Resources/config'));
      $loader->load('services.yaml');
      if ($container->hasParameter('kernel.environment')) {
        $env = $container->getParameter('kernel.environment');
        try {
          $loader->load("services.{$env}.yaml");
        } catch (FileLocatorFileNotFoundException $ex) {
          // ignore
        }
      }
      $container->setParameter('umatech.http_loader.kind', $config['kind']);
      $container->setParameter('umatech.http_loader.name', $config['name']);
    }
  }
}
