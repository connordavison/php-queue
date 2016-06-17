<?php

namespace CDavison\Queue\Dispatchers;

class SyncDispatcher extends AbstractDispatcher
{
    /**
     * {@inheritdoc}
     */
    public function run()
    {
        if ($this->queue->count()) {
            $this->worker->run($this->queue->pop());
            usleep(1E3 * $this->getWorkerTimeout());
        }
    }
}
