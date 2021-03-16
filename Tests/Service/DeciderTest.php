<?php

namespace Tests\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmaTech\HttpLogBundle\Service\Decider;
use PHPUnit\Framework\TestCase;

class DeciderTest extends TestCase
{
  /**
   * @dataProvider needLogData
   */
  public function testNeedLog(string $kind, string $name, Request $request, bool $expected): void
  {
    $decider = new Decider($kind, $name);
    $actual = $decider->needLog($request, new Response());
    self::assertSame($expected, $actual);
  }

  public function needLogData()
  {
    $req = new Request();

    yield ['get', 'x', $req, false];
    yield ['cookie', 'x', $req, false];

    $req = new Request(['x' => '']);
    yield ['get', 'x', $req, true];

    $req = new Request([], [], [], ['x' => '']);
    yield ['cookie', 'x', $req, true];
  }
}
