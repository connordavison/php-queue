<?php
namespace CDavison\Queue;

use CDavison\Queue\AbstractJob;
use CDavison\Queue\JobRelayInterface;
use CDavison\Queue\WorkerBusyException;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

abstract class AbstractWorker extends LoggerAwareInterface
{
    use LoggerAwareTrait;

    /**
     * Initialise this worker with a relay, through which a job's most recent
     * state may be ascertained.
     * 
     * @param JobRelayInterface $relay
     */
    public function __construct(JobRelayInterface $relay)
    {
        $this->relay = $relay;
    }

    /**
     * Run a job on this worker.
     * 
     * @param AbstractJob $job
     * @return void The supplied job's status should be modified to indicate
     * progress.
     */
    public abstract function run();

    /**
     * Attempt to retrieve the status of this job.
     * 
     * @return int The worker's status.
     */
    public function getStatus()
    {
        // If this worker has no job, or if the current job is finished, report
        // that this worker is waiting.
        if (is_null($this->job) || $this->job->isFinished()) {
            return WorkerStatus::WAITING;
        }

        // The job, as we know it, is not complete, so attempt to update it from
        // our relay.
        $this->job = $this->relay->get($this->job);

        return $this->job->isFinished() 
            ? WorkerStatus::WAITING
            : WorkerStatus::WORKING;
    }

    /**
     * @return bool True if this job is working.
     */
    public function isBusy()
    {
        return $this->getStatus() === WorkerStatus::WORKING;
    }

    /**
     * Assign this worker a job. To execute the job, this worker must be run().
     *
     * @throws WorkerBusyException If this worker is still processing another
     * job.
     * @param AbstractJob $job
     */
    public function setJob(AbstractJob $job)
    {
        if ($this->getStatus() !== WorkerStatus::WAITING) {
            throw new WorkerBusyException();
        }

        $this->job = $job;
        $this->relay->put($job);
    }

    /**
     * @param AbstractJob $job
     * @return bool True if this worker can run $job.
     */
    public abstract function supports(AbstractJob $job);
}