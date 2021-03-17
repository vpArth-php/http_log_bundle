<?php

namespace UmaTech\HttpLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use UmaTech\HttpLogBundle\Service\HttpRecordRepositoryInterface;

class ViewLogController extends AbstractController
{
  /** @var HttpRecordRepositoryInterface */
  protected $repo;
  public function __construct(HttpRecordRepositoryInterface $repo)
  {
    $this->repo = $repo;
  }
  public function viewLogAction(): Response
  {
    $logEntries = $this->repo->findPaged();
    return $this->render('@HttpLog/view-log/list.html.twig', [
        'entries' => $logEntries,
    ]);
  }
}
