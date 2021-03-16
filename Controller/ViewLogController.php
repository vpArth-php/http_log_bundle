<?php

namespace UmaTech\HttpLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class ViewLogController extends AbstractController
{
  public function viewLogAction(): Response
  {
    return $this->render('@HttpLog/view-log/list.html.twig', [

    ]);
  }
}
