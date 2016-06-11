<?php

use CDavison\Queue\Dispatchers\SyncDispatcher;
use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;

class SyncDispatcherTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var WorkerInterface | PHPUnit_Framework_MockObject_MockObject
     */
    protected $worker;

    /**
     * @var QueueInterface | PHPUnit_Framework_MockObject_MockObject
     */
    protected $queue;

    /**
     * @var SyncDispatcher | PHPUnit_Framework_MockObject_MockObject
     */
    protected $dispatcher;

    public function setUp()
    {
        $this->queue = $this->getMock(QueueInterface::class);
        $this->worker = $this->getMock(WorkerInterface::class);
    }

    /**
     * If the dispatcher is run when there is a non-empty queue, a payload will
     * be dispatched from that queue.
     */
    public function testRun()
    {
        $dispatcher = new SyncDispatcher($this->queue, $this->worker);
        $this->queue->expects($this->any())->method('count')->willReturn(123);
        $this->queue->expects($this->once())->method('pop')->willReturn('test');
        $this->worker->expects($this->once())->method('run')->with('test');
        $dispatcher->run();
    }

    /**
     * If the dispatcher is run when there is an empty queue, no dispatch will
     * occur.
     */
    public function testRunWithEmptyQueue()
    {
        $dispatcher = new SyncDispatcher($this->queue, $this->worker);
        $this->queue->expects($this->any())->method('count')->willReturn(0);
        $this->worker->expects($this->never())->method('run');
        $dispatcher->run();
    }
}
