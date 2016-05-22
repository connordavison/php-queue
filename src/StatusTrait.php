<?php

namespace CDavison\Queue;

trait StatusTrait
{
    /**
     * Get the status of a object.
     *
     * @return int
     */
    abstract protected function getStatus();

    /**
     * Set the status of this object.
     *
     * @param int $status
     * @throws \DomainException If the object status is invalid.
     * @see JobStatus For an enumeration of valid statuses.
     */
    abstract protected function setStatus($status);

    /**
     * @return bool True if this object has completed its task.
     */
    public function isCompleted()
    {
        return $this->getStatus() === Status::COMPLETED;
    }

    /**
     * @return bool True if this object has failed its task.
     */
    public function isFailed()
    {
        return $this->getStatus() === Status::FAILED;
    }

    /**
     * @return bool True if this object has finished (successfully or
     *     unsuccessfully).
     */
    public function isFinished()
    {
        $status = $this->getStatus();

        return Status::COMPLETED === $status
            || Status::FAILED === $status;
    }

    /**
     * @return bool True if this object has started a task.
     */
    public function isStarted()
    {
        $status = $this->getStatus();

        return Status::NONE !== $status && Status::WAITING !== $status;
    }

    /**
     * @return bool True if this object is sleeping on the job.
     */
    public function isSleeping()
    {
        return Status::SLEEPING === $this->getStatus();
    }

    /**
     * Mark this object as having started a task.
     *
     * @return void
     */
    public function start()
    {
        $this->setStatus(Status::RUNNING);
    }

    /**
     * Mark this object as sleeping on the job.
     *
     * @return void
     */
    public function sleep()
    {
        $this->setStatus(Status::SLEEPING);
    }

    /**
     * Mark this object as having completed its task.
     *
     * @return void
     */
    public function complete()
    {
        $this->setStatus(Status::COMPLETED);
    }

    /**
     * Mark this object as having failed its task.
     *
     * @return void
     */
    public function fail()
    {
        $this->setStatus(Status::FAILED);
    }
}
