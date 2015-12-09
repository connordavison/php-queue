<?php
namespace CDavison\Queue;

use JobInterface;

interface WorkerInterface
{
    /**
     * Run a job on this worker.
     * 
     * @param JobInterface $job
     * @return void The supplied job's status should be modified to indicate
     * progress.
     */
    public function runJob(JobInterface $job);
}