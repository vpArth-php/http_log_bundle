<?php

namespace UmaTech\HttpLogBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface DeciderInterface
{
  public function needLog(Request $request, Response $response);
}
