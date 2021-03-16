<?php

namespace UmaTech\HttpLogBundle\DependencyInjection;

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
    if ($config['enabled']) {
      $loader = new YamlFileLoader($container, new FileLocator(dirname(__DIR__).'/Resources/config'));
      $loader->load('services.yaml');
    }
  }
}
