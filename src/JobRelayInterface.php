<?php
namespace CDavison\Queue;

use CDavison\Queue\JobInterface;

/**
 * A relay allows reading & writing to the most recent state of a job.
 */
interface JobRelayInterface
{
    /**
     * Retrieve a job from the relay.
     * 
     * @param JobInterface $job
     * @return JobInterface
     */
    public function get(JobInterface $job);

    /**
     * Persist a job to the relay.
     * 
     * @param JobInterface $job
     */
    public function put(JobInterface $job);
}