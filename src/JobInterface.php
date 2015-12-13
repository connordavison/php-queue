<?php
namespace CDavison\Queue;

use CDavison\Queue\JobStatus;

interface JobInterface
{
    /**
     * @return boolean True if this job has completed.
     */
    public function isCompleted();

    /**
     * @return boolean True if this job has failed.
     */
    public function isFailed();

    /**
     * @return boolean True if this job has finished.
     */
    public function isFinished();

    /**
     * @return boolean True if this job has started.
     */
    public function isStarted();

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