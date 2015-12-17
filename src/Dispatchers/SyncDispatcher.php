<?php
namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\AbstractDispatcher;
use CDavison\Queue\JobInterface;
use CDavison\Queue\WorkerInterface;

class SyncDispatcher extends AbstractDispatcher
{
    /**
     * Run a job. That's it.
     * 
     * @param JobInterface $job
     * @param WorkerInterface $worker
     */
    public function dispatch(JobInterface $job, WorkerInterface $worker)
    {
        $worker->run($job);
    }
}