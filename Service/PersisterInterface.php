<?php

namespace UmaTech\HttpLogBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

interface PersisterInterface
{
  public function persist(Request $request, Response $response);
}
