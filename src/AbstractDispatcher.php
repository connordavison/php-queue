<?php

namespace CDavison\Queue;

abstract class AbstractDispatcher implements DispatcherInterface
{
    /**
     * @var QueueInterface
     */
    protected $queue;

    /**
     * Milliseconds to sleep before a worker can be redispatched.
     *
     * @var int
     */
    protected $worker_timeout = 10E3;

    /**
     * Create an instance of this dispatcher.
     *
     * @param QueueInterface  $queue  The queue from which this dispatcher will
     *                                pull jobs.
     * @param WorkerInterface $worker The worker which should run jobs from the
     *                                queue.
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
            usleep(1E3 * $this->getWorkerTimeout());
        }
    }

    /**
     * This is the main execution loop of the dispatcher.
     *
     * @return void
     */
    abstract protected function loop();

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
