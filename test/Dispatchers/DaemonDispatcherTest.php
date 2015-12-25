<?php
namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\JobStatus;
use CDavison\Queue\Dispatchers\DaemonDispatcher;

function usleep($time)
{
    DaemonDispatcherTest::$functions->usleep($time);
}

class DaemonDispatcherTest extends \PHPUnit_Framework_TestCase
{
    public static $functions;

    const DISPATCHER = '\CDavison\Queue\Dispatchers\DaemonDispatcher';
    const JOB        = '\CDavison\Queue\JobInterface';
    const QUEUE      = '\CDavison\Queue\QueueInterface';
    const WORKER     = '\CDavison\Queue\WorkerInterface';

    public function setUp()
    {
        self::$functions = $this->getMockBuilder('functions')
            ->setMethods(['usleep'])
            ->getMock();

        $this->worker = $this->getMock(self::WORKER);
        $this->queue = $this->getMock(self::QUEUE);
        $this->manager = $this->getMock('\Ko\ProcessManager');
        $this->job = $this->getMock(self::JOB);

        $this->dispatcher = $this->getMockBuilder(self::DISPATCHER)
            ->setConstructorArgs([$this->queue, $this->worker, 3])
            ->setMethods(['dispatch'])
            ->getMock();

        $this->dispatcher->setManager($this->manager);

        $this->dispatch = new \ReflectionMethod(self::DISPATCHER, 'dispatch');
        $this->dispatch->setAccessible(true);
    }

    public function testDispatch()
    {
        $dispatcher = new DaemonDispatcher($this->queue, $this->worker, 3);
        $dispatcher->setManager($this->manager);

        self::$functions->expects($this->once())
            ->method('usleep')
            ->with($dispatcher->getWorkerTimeout() * 1E3);

        $process = $this->getMockBuilder('\Ko\Process')
            ->disableOriginalConstructor()
            ->getMock();

        $this->worker->expects($this->once())->method('run');

        $this->manager->expects($this->once())
            ->method('fork')
            ->with(
                $this->callback(
                    function ($callback) use ($process) {
                        // The callback should hit usleep & $worker->run()
                        $callback($process);
                        return true;
                    }
                )
            );

        $this->assertEmpty($dispatcher->getManager()->count());

        $this->dispatch->invoke($dispatcher, $this->job);
    }

    public function testLoopWithNoFreeWorkers()
    {
        $this->manager->expects($this->once())->method('count')->willReturn(3);
        $this->manager->expects($this->once())->method('wait');

        $this->dispatcher->expects($this->never())->method('dispatch');
        $this->dispatcher->loop();
    }

    public function testLoopWithFreeWorkersAndNonEmptyQueue()
    {
        $this->manager->expects($this->once())->method('count')->willReturn(2);
        $this->queue->expects($this->once())->method('size')->willReturn(123);
        $this->queue->expects($this->once())
            ->method('pop')
            ->willReturn($this->job);

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->job);

        $this->dispatcher->loop();
    }

    public function testLoopWithFreeWorkersAndEmptyQueue()
    {
        $this->manager->expects($this->once())->method('count')->willReturn(2);
        $this->queue->expects($this->once())->method('size')->willReturn(123);
        $this->queue->expects($this->once())
            ->method('pop')
            ->willReturn($this->job);

        $this->dispatcher->expects($this->once())
            ->method('dispatch')
            ->with($this->job);

        $this->dispatcher->loop();
    }
}