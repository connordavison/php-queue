<?php
namespace CDavison\Queue;

use JobStatus;

abstract class AbstractJob {
    /**
     * @var int $attempts The number of attempts.
     */
    protected $attempts;

    /**
     * @var mixed $payload
     */
    protected $payload;

    /**
     * Construct a job for a payload.
     * 
     * @param mixed $payload Some payload that this worker can attempt to
     * process.
     */
    public function __construct($payload)
    {
        $this->setStatus(JobStatus::NONE);
        $this->payload = $payload;
    }

    /**
     * Mark this job as running.
     * 
     * @return void
     */
    public function run()
    {
        $this->setStatus(JobStatus::RUNNING);
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

    /**
     * Sleep this job.
     *
     * @return void
     */
    public function sleep()
    {
        $this->setStatus(JobStatus::SLEEPING);
    }

    /**
     * Release the job back to the queue for collection.
     *
     * @return void
     */
    public function release()
    {
        $this->setStatus(JobStatus::RELEASED);
    }

    /**
     * Get the status of this worker.
     * 
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set the status of this worker.
     *
     * @param int $status
     * @throws \DomainException If the job status is invalid.
     * @see JobStatus For an enumeration of valid statuses.
     */
    public function setStatus($status)
    {
        if (!JobStatus::has($status)) {
            throw new \DomainException("Invalid job status.");
        }

        $this->status = $status;
    }
}