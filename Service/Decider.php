<?php

namespace UmaTech\HttpLogBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Decider implements DeciderInterface
{
  /** @var string */
  protected $kind;
  /** @var string */
  protected $name;

  public function __construct(string $kind, string $name)
  {
    $this->kind = $kind;
    $this->name = $name;
  }

  public function needLog(Request $request, Response $response)
  {
    switch ($this->kind) {
      case 'cookie':
        return $request->cookies->has($this->name);
      case 'get':
      default:
        return $request->query->has($this->name);
    }
  }
}
