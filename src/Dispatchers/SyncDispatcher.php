<?php
namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\AbstractDispatcher;
use CDavison\Queue\JobInterface;
use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;

class SyncDispatcher extends AbstractDispatcher
{
    /**
     * This is the main execution loop of the dispatcher.
     *
     * @return void
     */
    protected function loop()
    {
        if ($this->queue->size() > 0) {
            $this->worker->run($this->queue->pop());
        }
    }
}
