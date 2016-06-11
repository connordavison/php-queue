<?php

namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;

interface DispatcherInterface
{
    /**
     * Attempt to dispatch any available jobs to a worker.
     *
     * @return void
     */
    public function run();

    /**
     * Dispatch a job through this interface.
     *
     * @param mixed $payload
     * @return void
     */
    public function dispatch($payload);

    /**
     * Set the queue on which this dispatcher should run.
     *
     * @param QueueInterface $queue
     * @return void
     */
    public function setQueue(QueueInterface $queue);

    /**
     * Set the worker to which this dispatcher will dispatch jobs.
     *
     * @param WorkerInterface $worker
     * @return void
     */
    public function setWorker(WorkerInterface $worker);
}
