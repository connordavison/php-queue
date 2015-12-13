<?php
namespace CDavison\Queue;

use CDavison\Queue\JobInterface;

interface JobQueueInterface
{
    /**
     * Push a job onto the queue.
     * 
     * @param JobInterface $job
     */
    public function push(JobInterface $job);
    
    /**
     * Pop a job off the queue.
     * 
     * @return JobInterface The next job to be processed.
     */
    public function pop();

    /**
     * @return int The size of this queue.
     */
    public function size();
}