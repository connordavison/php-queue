<?php
namespace CDavison\Queue;

use CDavison\Queue\AbstractJob;

/**
 * A relay allows reading & writing to the most recent state of a job.
 */
interface JobRelayInterface
{
    /**
     * Retrieve a job from the relay.
     * 
     * @param AbstractJob $job
     * @return AbstractJob
     */
    public function get(AbstractJob $job);

    /**
     * Persist a job to the relay.
     * 
     * @param AbstractJob $job
     */
    public function put(AbstractJob $job);
}