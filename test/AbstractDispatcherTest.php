<?php
namespace CDavison\Test\Queue;

use CDavison\Queue\JobStatus;

use PHPUnit_Framework_Assert as Assert;

class AbstractDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->worker = $this->getMock('CDavison\Queue\WorkerInterface');
        $this->queue = $this->getMock('CDavison\Queue\JobQueueInterface');

        $this->dispatcher = $this->getMockForAbstractClass(
            'CDavison\Queue\AbstractDispatcher', [$this->queue, [$this->worker]]
        );

        $this->loop = new \ReflectionMethod($this->dispatcher, 'loop');
        $this->loop->setAccessible(true);
    }

    public function testLoop()
    {
        $this->worker->expects($this->any())
            ->method('isBusy')
            ->willReturn(false);

        $this->queue->expects($this->any())
            ->method('size')
            ->willReturn(123);

        $job = $this->getMock('CDavison\Queue\JobInterface');

        $this->queue->expects($this->once())
            ->method('pop')
            ->willReturn($job);

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($job, $this->worker);

        $this->loop->invoke($this->dispatcher);
    }

    public function testLoopWithBusyWorker()
    {
        $this->dispatcher->expects($this->never())->method('dispatch');
        $this->worker->expects($this->any())
            ->method('isBusy')
            ->willReturn(true);
        $this->queue->expects($this->any())
            ->method('size')
            ->willReturn(123);

        $this->loop->invoke($this->dispatcher);
    }

    public function testLoopWithEmptyQueue()
    {
        $this->dispatcher->expects($this->never())->method('dispatch');
        $this->queue->expects($this->any())
            ->method('size')
            ->willReturn(0);
        $this->worker->expects($this->any())
            ->method('isBusy')
            ->willReturn(false);

        $this->loop->invoke($this->dispatcher);
    }

    public function testLoopWithBusyWorkerAndEmptyQueue()
    {
        $this->dispatcher->expects($this->never())->method('dispatch');
        $this->worker->expects($this->any())
            ->method('isBusy')
            ->willReturn(true);
        $this->queue->expects($this->any())
            ->method('size')
            ->willReturn(0);

        $this->loop->invoke($this->dispatcher);
    }
}