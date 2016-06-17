<?php

namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;
use Ko\ProcessManager;
use PHP_Timer as Timer;

class DaemonDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * A mock object that proxies native functions.
     *
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public static $functions;

    /**
     * @var WorkerInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $worker;

    /**
     * @var QueueInterface | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $queue;

    /**
     * @var ProcessManager | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $manager;

    /**
     * A partial mock of the test subject.
     *
     * @var DaemonDispatcher | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $dispatcher;

    public function setUp()
    {
        if (!extension_loaded('pcntl')) {
            $this->markTestSkipped('PCNTL extension not available.');
        }

        $this->worker = $this->getMock(WorkerInterface::class);
        $this->queue = $this->getMock(QueueInterface::class);
        $this->manager = $this->getMock(ProcessManager::class);
    }

    /**
     * When a payload is given to the dispatcher, it should generate a procedure
     * to execute that payload. That procedure should be forked by the
     * ProcessManager.
     */
    public function testDispatch()
    {
        $dispatcher = $this->getDispatcherMock(['getDispatchAction']);

        $closure = function () {
            // Do nothing!
        };

        $dispatcher->expects($this->once())
            ->method('getDispatchAction')
            ->with('testing')
            ->willReturn($closure);

        $this->manager->expects($this->once())
            ->method('fork')
            ->with($closure);

        $dispatcher->dispatch('testing');
    }

    /**
     * When a payload is dispatched, a timeout should occur, after which the
     * worker associated with the dispatcher should consume the payload.
     */
    public function testDispatchAction()
    {
        $dispatcher = new DaemonDispatcher($this->queue, $this->worker, 3);
        $dispatcher->setManager($this->manager);
        $dispatcher->setWorkerTimeout(1000);

        $this->worker->expects($this->once())->method('run')->with('testing');

        Timer::start();
        $dispatcher->getDispatchAction('testing')->__invoke();
        $time = Timer::stop();

        $this->assertEquals(1, $time, "Time not within 50ms of target.", 0.050);
        $this->assertEmpty($dispatcher->getManager()->count());
    }

    /**
     * If the dispatcher is run when there are free workers and a non-empty
     * queue, a payload should be dispatched from that queue.
     */
    public function testRun()
    {
        $dispatcher = $this->getDispatcherMock(['dispatch']);

        $this->manager->expects($this->any())->method('count')->willReturn(0);
        $this->queue->expects($this->any())->method('count')->willReturn(123);
        $this->queue->expects($this->once())->method('pop')->willReturn('test');

        $dispatcher->expects($this->once())->method('dispatch')->with('test');
        $dispatcher->run();
    }

    /**
     * If the dispatcher is run when no workers are available, no dispatch
     * should occur.
     */
    public function testRunWithNoFreeWorkers()
    {
        $dispatcher = $this->getDispatcherMock(['dispatch']);

        $this->manager->expects($this->any())->method('count')->willReturn(3);
        $this->queue->expects($this->any())->method('count')->willReturn(0);

        $dispatcher->expects($this->never())->method('dispatch');
        $dispatcher->run();
    }

    /**
     * If the dispatcher is run when there are free workers but an empty queue,
     * no dispatch should occur.
     */
    public function testRunWithEmptyQueue()
    {
        $dispatcher = $this->getDispatcherMock(['dispatch']);

        $this->manager->expects($this->any())->method('count')->willReturn(0);
        $this->queue->expects($this->any())->method('count')->willReturn(0);

        $dispatcher->expects($this->never())->method('dispatch');
        $dispatcher->run();
    }

    /**
     * Get a DaemonDispatcher with the given methods as test doubles.
     *
     * @param string[] $methods
     * @return DaemonDispatcher | \PHPUnit_Framework_MockObject_MockObject
     */
    public function getDispatcherMock(array $methods)
    {
        $dispatcher = $this->getMock(
            DaemonDispatcher::class,
            $methods,
            [$this->queue, $this->worker, 3]
        );

        $dispatcher->setManager($this->manager);

        return $dispatcher;
    }
}
