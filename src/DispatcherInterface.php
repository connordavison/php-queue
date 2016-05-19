<?php

namespace CDavison\Queue;

interface DispatcherInterface
{
    /**
     * Run this dispatcher.
     *
     * @return void
     */
    public function run();

    /**
     * Set the queue on which this dispatcher should run.
     *
     * @param QueueInterface $queue
     *
     * @return void
     */
    public function setQueue(QueueInterface $queue);

    /**
     * Set the worker to which this dispatcher will dispatch jobs.
     *
     * @param WorkerInterface $worker
     *
     * @return void
     */
    public function setWorker(WorkerInterface $worker);
}
