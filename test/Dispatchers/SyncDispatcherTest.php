<?php

use CDavison\Queue\Dispatchers\SyncDispatcher;

class SyncDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->worker = $this->getMock('CDavison\Queue\WorkerInterface');
        $this->queue = $this->getMock('CDavison\Queue\QueueInterface');

        $this->dispatcher = new SyncDispatcher($this->queue, $this->worker);

        $this->loop = new \ReflectionMethod($this->dispatcher, 'loop');
        $this->loop->setAccessible(true);
    }

    public function testLoop()
    {
        $this->queue->expects($this->any())->method('size')->willReturn(123);

        $job = $this->getMock('CDavison\Queue\JobInterface');

        $this->queue->expects($this->once())->method('pop')->willReturn($job);
        $this->worker->expects($this->once())->method('run')->with($job);

        $this->loop->invoke($this->dispatcher);
    }

    public function testLoopWithEmptyQueue()
    {
        $this->queue->expects($this->any())->method('size')->willReturn(0);
        $this->worker->expects($this->never())->method('run');

        $this->loop->invoke($this->dispatcher);
    }
}
