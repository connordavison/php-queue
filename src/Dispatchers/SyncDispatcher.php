<?php

namespace CDavison\Queue\Dispatchers;

class SyncDispatcher extends AbstractDispatcher
{
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function run()
    {
        if ($this->queue->size() === 0) {
            return;
        }

        $this->dispatch($this->queue->pop());

        usleep(1E3 * $this->getWorkerTimeout());
    }
    /**
     * {@inheritdoc}
     *
     * @return void
     */
    public function dispatch($job)
    {
        $this->worker->run($job);
    }
}
