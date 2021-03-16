<?php

namespace UmaTech\HttpLogBundle\Service;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DummyPersister implements PersisterInterface
{
  public function persist(Request $request, Response $response)
  {
    // dummy
  }
}
