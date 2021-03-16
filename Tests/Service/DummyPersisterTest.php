<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmaTech\HttpLogBundle\Service\DummyPersister;

class DummyPersisterTest extends TestCase
{
  public function testPersist(): void
  {
    $req = $this->createMock(Request::class);
    $res = $this->createMock(Response::class);

    $req->expects(self::never())->method('get');
    $req->expects(self::never())->method('getContent');
    $res->expects(self::never())->method('getContent');

    $subject = new DummyPersister();
    $subject->persist($req, $res);
  }
}
