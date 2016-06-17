<?php

namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;

abstract class AbstractDispatcher implements DispatcherInterface
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * @var WorkerInterface
     */
    protected $worker;

    /**
     * Milliseconds to sleep before a worker can be re-dispatched.
     *
     * @var int
     */
    protected $worker_timeout = 10E3;

    /**
     * Create an instance of this dispatcher.
     *
     * @param QueueInterface $queue The queue from which this dispatcher will
     *     pull jobs.
     * @param WorkerInterface $worker The worker which should run jobs from the
     *     queue.
     */
    public function __construct(QueueInterface $queue, WorkerInterface $worker)
    {
        $this->queue = $queue;
        $this->worker = $worker;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function run();

    /**
     * Set the timeout in milliseconds before a worker can be redispatched.
     *
     * @param int $worker_timeout
     */
    public function setWorkerTimeout($worker_timeout)
    {
        $this->worker_timeout = $worker_timeout;
    }

    /**
     * Get the timeout in milliseconds before a worker can be redispatched.
     *
     * @return int
     */
    public function getWorkerTimeout()
    {
        return $this->worker_timeout;
    }
}
