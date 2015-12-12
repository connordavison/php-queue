<?php
namespace CDavison\Queue;

use CDavison\Queue\JobStatus;

interface JobInterface
{
    /**
     * Retrieve the payload of this job.
     * 
     * @return mixed
     */
    public function getPayload();

    /**
     * Get the status of this worker.
     * 
     * @return mixed
     */
    public function getStatus();

    /**
     * Set the status of this worker.
     *
     * @param mixed $status
     * @throws \DomainException If the job status is invalid.
     */
    public function setStatus($status);
}