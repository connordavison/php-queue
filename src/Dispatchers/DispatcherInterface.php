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
}
