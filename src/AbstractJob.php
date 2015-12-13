<?php
namespace CDavison\Queue;

use CDavison\Queue\JobStatus;

abstract class AbstractJob {
    /**
     * @var int $attempts The number of attempts.
     */
    protected $attempts;

    /**
     * Get the status of this worker.
     * 
     * @return int
     */
    protected abstract function getStatus();

    /**
     * Set the status of this worker.
     *
     * @param int $status
     * @throws \DomainException If the job status is invalid.
     * @see JobStatus For an enumeration of valid statuses.
     */
    protected abstract function setStatus($status);

    /**
     * @return boolean True if this job has completed.
     */
    public function isCompleted()
    {
        return $this->getStatus() === JobStatus::COMPLETED;
    }

    /**
     * @return boolean True if this job has failed.
     */
    public function isFailed()
    {
        return $this->getStatus() === JobStatus::FAILED;
    }

    /**
     * @return boolean True if this job has finished.
     */
    public function isFinished()
    {
        $status = $this->getStatus();

        return JobStatus::COMPLETED === $status
            || JobStatus::FAILED === $status;
    }

    /**
     * @return boolean True if this job has started.
     */
    public function isStarted()
    {
        $status = $this->getStatus();

        return JobStatus::NONE !== $status && JobStatus::WAITING !== $status;
    }

    /**
     * @return boolean True if this job is sleeping.
     */
    public function isSleeping()
    {
        return JobStatus::SLEEPING === $this->getStatus();
    }

    /**
     * Mark this job as started.
     *
     * @return void
     */
    public function start()
    {
        $this->setStatus(JobStatus::RUNNING);
    }

    /**
     * Mark this job as sleeping.
     *
     * @return void
     */
    public function sleep()
    {
        $this->setStatus(JobStatus::SLEEPING);
    }

    /**
     * Mark this job as completed.
     *
     * @return void
     */
    public function complete()
    {
        $this->setStatus(JobStatus::COMPLETED);
    }

    /**
     * Mark this job as failed.
     *
     * @return void
     */
    public function fail()
    {
        $this->setStatus(JobStatus::FAILED);
    }
}