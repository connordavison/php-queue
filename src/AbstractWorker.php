<?php
namespace CDavison\Queue;

use CDavison\Queue\JobInterface;
use CDavison\Queue\WorkerBusyException;

abstract class AbstractWorker
{
    /**
     * Run a job on this worker.
     * 
     * @param JobInterface $job
     * @throws WorkerBusyException If this worker is still working on another
     * job.
     * @return void The supplied job's status should be modified to indicate
     * progress.
     */
    public abstract function run(JobInterface $job);

    /**
     * @return bool True if this job is working.
     */
    public function isBusy()
    {
        return isset($this->job) ? !$this->job->isFinished() : false;
    }

    /**
     * @param JobInterface $job
     * @return bool True if this worker can run $job.
     */
    public abstract function supports(JobInterface $job);
}