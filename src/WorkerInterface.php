<?php
namespace CDavison\Queue;

use CDavison\Queue\AbstractJob;

interface WorkerInterface
{
    /**
     * Run a job on this worker.
     * 
     * @param AbstractJob $job
     * @return void The supplied job's status should be modified to indicate
     * progress.
     */
    public function runJob(AbstractJob $job);
}