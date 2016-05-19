<?php

namespace CDavison\Queue\Queues;

use CDavison\Queue\JobInterface;
use CDavison\Queue\QueueInterface;

class SyncQueue implements QueueInterface
{
    /**
     * The jobs left in this queue.
     *
     * @var array
     */
    protected $jobs = [];

    /**
     * {@inheritdoc}
     */
    public function push(JobInterface $job)
    {
        array_unshift($this->jobs, $job);
    }

    /**
     * {@inheritdoc}
     */
    public function pop()
    {
        return array_pop($this->jobs);
    }

    /**
     * {@inheritdoc}
     */
    public function size()
    {
        return count($this->jobs);
    }
}
