<?php

namespace CDavison\Queue;

trait StatusTrait
{
    /**
     * Get the status of a job.
     *
     * @return int
     */
    abstract protected function getStatus();

    /**
     * Set the status of this worker.
     *
     * @param int $status
     * @throws \DomainException If the job status is invalid.
     * @see JobStatus For an enumeration of valid statuses.
     */
    abstract protected function setStatus($status);

    /**
     * @return bool True if this job has completed.
     */
    public function isCompleted()
    {
        return $this->getStatus() === Status::COMPLETED;
    }

    /**
     * @return bool True if this job has failed.
     */
    public function isFailed()
    {
        return $this->getStatus() === Status::FAILED;
    }

    /**
     * @return bool True if this job has finished.
     */
    public function isFinished()
    {
        $status = $this->getStatus();

        return Status::COMPLETED === $status
            || Status::FAILED === $status;
    }

    /**
     * @return bool True if this job has started.
     */
    public function isStarted()
    {
        $status = $this->getStatus();

        return Status::NONE !== $status && Status::WAITING !== $status;
    }

    /**
     * @return bool True if this job is sleeping.
     */
    public function isSleeping()
    {
        return Status::SLEEPING === $this->getStatus();
    }

    /**
     * Mark this job as started.
     *
     * @return void
     */
    public function start()
    {
        $this->setStatus(Status::RUNNING);
    }

    /**
     * Mark this job as sleeping.
     *
     * @return void
     */
    public function sleep()
    {
        $this->setStatus(Status::SLEEPING);
    }

    /**
     * Mark this job as completed.
     *
     * @return void
     */
    public function complete()
    {
        $this->setStatus(Status::COMPLETED);
    }

    /**
     * Mark this job as failed.
     *
     * @return void
     */
    public function fail()
    {
        $this->setStatus(Status::FAILED);
    }
}
