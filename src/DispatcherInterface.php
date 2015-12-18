<?php
namespace CDavison\Queue;

use CDavison\Queue\JobInterface;
use CDavison\Queue\QueueInterface;

interface DispatcherInterface
{
    /**
     * @var QueueInterface $queue
     */
    protected $queue;

    /**
     * Run this dispatcher.
     *
     * @return void
     */
    public function run();

    /**
     * Set the queue on which this dispatcher should run.
     *
     * @param QueueInterface $queue
     * @return void
     */
    public function setQueue(QueueInterface $queue);
}