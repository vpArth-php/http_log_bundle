<?php

namespace UmaTech\HttpLogBundle\Entity;

use DateTimeInterface;

interface HttpLogRecordInterface
{
  /**
   * @return int
   */
  public function getId(): int;
  /**
   * @param int $id
   */
  public function setId(int $id): void;
  /**
   * @return string
   */
  public function getIp(): string;
  /**
   * @param string $ip
   */
  public function setIp(string $ip): void;
  /**
   * @return string
   */
  public function getUri(): string;
  /**
   * @param string $uri
   */
  public function setUri(string $uri): void;
  /**
   * @return string
   */
  public function getRequest(): string;
  /**
   * @param string $request
   */
  public function setRequest(string $request): void;
  /**
   * @return string
   */
  public function getResponse(): string;
  /**
   * @param string $response
   */
  public function setResponse(string $response): void;
  /**
   * @return int
   */
  public function getStatus(): int;
  /**
   * @param int $status
   */
  public function setStatus(int $status): void;
  /**
   * @return DateTimeInterface
   */
  public function getQueryTime(): DateTimeInterface;
  /**
   * @param DateTimeInterface $query_time
   */
  public function setQueryTime(DateTimeInterface $query_time): void;
}
