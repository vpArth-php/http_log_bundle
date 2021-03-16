<?php

namespace Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;
use UmaTech\HttpLogBundle\DependencyInjection\Configuration;

class ConfigurationTest extends TestCase
{
  /** @var Processor */
  private $processor;
  /** @var Configuration */
  private $subject;

  protected function setUp(): void
  {
    $this->processor = new Processor();
    $this->subject   = new Configuration();
  }
  /**
   * @dataProvider configData
   */
  public function testConfig(array $config, array $expected)
  {
    $actual = $this->processor->processConfiguration($this->subject, [$config]);

    self::assertEquals($expected, $actual);
  }

  public function configData()
  {
    $default = ['enabled' => false, 'kind' => 'get', 'name' => Configuration::DEFAULT_NAME];
    yield [[], $default];
    yield [['kind' => 'cookie'], ['kind' => 'cookie'] + $default];
    yield [['name' => 'debug'], ['name' => 'debug'] + $default];
    yield [['enabled' => true], ['enabled' => true] + $default];
  }
}
