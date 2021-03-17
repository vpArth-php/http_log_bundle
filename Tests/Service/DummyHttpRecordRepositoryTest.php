<?php

namespace Tests\Service;

use PHPUnit\Framework\TestCase;
use UmaTech\HttpLogBundle\Service\DummyHttpRecordRepository;
use UmaTech\HttpLogBundle\Service\HttpRecordRepositoryInterface;

class DummyHttpRecordRepositoryTest extends TestCase
{
  /** @var HttpRecordRepositoryInterface */
  private $subject;
  protected function setUp(): void
  {
    $this->subject = new DummyHttpRecordRepository();
  }
  public function testFindByIpPaged(): void
  {
    self::assertEquals([], $this->subject->findByIpPaged(''));
  }
  public function testFindPaged(): void
  {
    self::assertEquals([], $this->subject->findPaged());
  }
}
