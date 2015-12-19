<?php
namespace CDavison\Queue;

use CDavison\Queue\DispatcherInterface;
use CDavison\Queue\WorkerInterface;
use CDavison\Queue\QueueInterface;

abstract class AbstractDispatcher implements DispatcherInterface
{
    /**
     * @var QueueInterface $queue
     */
    protected $queue;

    /**
     * Milliseconds to sleep between loops.
     *
     * @var int
     */
    protected $loop_timeout = 10E3;

    /**
     * Create an instance of this dispatcher.
     *
     * @param QueueInterface $queue The queue from which this dispatcher will
     * pull jobs.
     * @param WorkerInterface $worker The worker which should run jobs from the
     * queue.
     */
    public function __construct(QueueInterface $queue, WorkerInterface $worker)
    {
        $this->setQueue($queue);
        $this->setWorker($worker);
    }

    /**
     * Run this dispatcher.
     *
     * @return void
     */
    public function run()
    {
        while (true) {
            $this->loop();
            usleep(1E3 * $this->loop_timeout);
        }
    }

    /**
     * This is the main execution loop of the dispatcher.
     *
     * @return void
     */
    protected abstract function loop();

    /**
     * Set the queue on which this dispatcher should operate.
     *
     * @param QueueInterface $queue
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Set the worker which will complete jobs from the current queue.
     *
     * @param WorkerInterface $worker
     */
    public function setWorker(WorkerInterface $worker)
    {
        $this->worker = $worker;
    }
}