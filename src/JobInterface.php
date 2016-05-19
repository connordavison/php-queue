<?php

namespace CDavison\Queue;

interface JobInterface
{
    /**
     * @return bool True if this job has completed.
     */
    public function isCompleted();

    /**
     * @return bool True if this job has failed.
     */
    public function isFailed();

    /**
     * @return bool True if this job has finished.
     */
    public function isFinished();

    /**
     * @return bool True if this job has started.
     */
    public function isStarted();

    /**
     * @return bool True if this job is sleeping.
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
