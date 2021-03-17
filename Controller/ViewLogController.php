<?php

namespace UmaTech\HttpLogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use UmaTech\HttpLogBundle\Service\HttpRecordRepositoryInterface;
use function array_flip;

class ViewLogController extends AbstractController
{
  /** @var HttpRecordRepositoryInterface */
  protected $repo;
  public function __construct(HttpRecordRepositoryInterface $repo)
  {
    $this->repo = $repo;
  }
  public function viewLogAction(Request $request): Response
  {
    $ip         = $request->get('ip');
    $logEntries = $ip ? $this->repo->findByIpPaged($ip) : $this->repo->findPaged();

    $result = [
        'entries' => $logEntries,
    ];

    $types = array_flip($request->getAcceptableContentTypes());

    $json = $types['application/json'] ?? null;
    $html = $types['text/html'] ?? null;

    if ($json !== null && ($html === null || $json < $html)) {
      return $this->json($result);
    }

    return $this->render('@HttpLog/view-log/list.html.twig', $result);
  }
}
