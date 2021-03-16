<?php

namespace UmaTech\HttpLogBundle\Service;

use UmaTech\HttpLogBundle\Entity\HttpLogRecordInterface;

interface HttpRecordRepositoryInterface
{
  /**
   * @param string   $ip
   * @param int      $page - Zero based page number
   * @param int|null $size - null means infinity size(fetch all records and ignore page param)
   * @return HttpLogRecordInterface[]
   */
  public function findByIpPaged(string $ip, int $page = 0, int $size = null): array;
  /**
   * @param int      $page - Zero based page number
   * @param int|null $size - null means infinity size(fetch all records and ignore page param)
   * @return HttpLogRecordInterface[]
   */
  public function findPaged(int $page = 0, int $size = null): array;
}
