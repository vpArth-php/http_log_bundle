<?php

namespace UmaTech\HttpLogBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
  public const NAME = 'http_log';
  public const DEFAULT_NAME = 'http_log';
  public function getConfigTreeBuilder()
  {
    $treeBuilder = new TreeBuilder(static::NAME);

    /** @noinspection NullPointerExceptionInspection */
    $treeBuilder->getRootNode()->children()
        ->booleanNode('enabled')->defaultFalse()->end()
        ->enumNode('kind')->info('kind of trigger of log request(get/cookie)')
        ->values(['get', 'cookie'])->defaultValue('get')->end()
        ->scalarNode('name')->defaultValue(self::DEFAULT_NAME)->end()
        ->end();

    return $treeBuilder;
  }
}
