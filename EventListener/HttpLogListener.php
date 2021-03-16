<?php

namespace UmaTech\HttpLogBundle\EventListener;

use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use UmaTech\HttpLogBundle\Service\DeciderInterface;
use UmaTech\HttpLogBundle\Service\PersisterInterface;

class HttpLogListener
{
  /** @var DeciderInterface */
  protected $decider;
  /** @var PersisterInterface */
  protected $persister;

  public function __construct(DeciderInterface $decider, PersisterInterface $persister)
  {
    $this->decider   = $decider;
    $this->persister = $persister;
  }

  public function onKernelResponse(ResponseEvent $event)
  {
    $request  = $event->getRequest();
    $response = $event->getResponse();
    $needLog  = $this->decider->needLog($request, $response);
    if ($needLog) {
      $this->persister->persist($request, $response);
    }
  }
  public static function getSubscribedEvents(): array
  {
    return [
        KernelEvents::RESPONSE => ['onKernelResponse', -128],
    ];
  }
}
