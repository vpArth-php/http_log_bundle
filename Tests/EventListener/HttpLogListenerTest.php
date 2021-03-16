<?php

namespace Tests\EventListener;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\HttpKernel\KernelEvents;
use UmaTech\HttpLogBundle\EventListener\HttpLogListener;
use UmaTech\HttpLogBundle\Service\DeciderInterface;
use UmaTech\HttpLogBundle\Service\PersisterInterface;

class HttpLogListenerTest extends TestCase
{
  /** @var MockObject|DeciderInterface */
  private $decider;
  /** @var MockObject|PersisterInterface */
  private $persister;
  /** @var HttpLogListener */
  private $subject;

  protected function setUp(): void
  {
    $this->decider   = $this->createMock(DeciderInterface::class);
    $this->persister = $this->createMock(PersisterInterface::class);
    $this->subject   = new HttpLogListener($this->decider, $this->persister);
  }

  /**
   * @dataProvider onKernelResponseData
   */
  public function testOnKernelResponse(bool $deciderRes, bool $persisterCalled): void
  {
    $request  = $this->createMock(Request::class);
    $response = $this->createMock(Response::class);
    $event    = new ResponseEvent(
        $this->createMock(Kernel::class),
        $request,
        HttpKernelInterface::MASTER_REQUEST,
        $response
    );

    $this->decider->expects(self::once())
        ->method('needLog')
        ->with($request, $response)
        ->willReturn($deciderRes);

    $this->persister->expects($persisterCalled ? self::once() : self::never())
        ->method('persist')
        ->with($request, $response);

    $this->subject->onKernelResponse($event);
  }

  public function onKernelResponseData()
  {
    yield [false, false];
    yield [true, true];
  }

  public function testGetSubscribedEvents(): void
  {
    $sub = HttpLogListener::getSubscribedEvents();

    self::assertEquals('onKernelResponse', $sub[KernelEvents::RESPONSE][0]);
    self::assertEquals(-128, $sub[KernelEvents::RESPONSE][1]);
  }
}
