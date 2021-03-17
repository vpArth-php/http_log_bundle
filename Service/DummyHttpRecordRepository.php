<?php

namespace UmaTech\HttpLogBundle\Service;

class DummyHttpRecordRepository implements HttpRecordRepositoryInterface
{
  /**
   * @inheritDoc
   */
  public function findByIpPaged(string $ip, int $page = 0, int $size = null): array
  {
    return [];
  }
  /**
   * @inheritDoc
   */
  public function findPaged(int $page = 0, int $size = null): array
  {
    return [];
  }
}
