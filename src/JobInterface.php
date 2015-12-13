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
     * @return boolean True if this job has completed.
     */
    public function hasCompleted();

    /**
     * @return boolean True if this job has failed.
     */
    public function hasFailed();

    /**
     * @return boolean True if this job has finished.
     */
    public function hasFinished();

    /**
     * @return boolean True if this job has started.
     */
    public function hasStarted();

    /**
     * @return boolean True if this job is sleeping.
     */
    public function isSleeping();

    /**
     * Mark this job as started.
     *
     * @return void
     */
    public function start();

    /**
     * Mark this job as sleeping.
     *
     * @return void
     */
    public function sleep();

    /**
     * Mark this job as completed.
     *
     * @return void
     */
    public function complete();

    /**
     * Mark this job as failed.
     *
     * @return void
     */
    public function fail();
}