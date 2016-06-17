<?php

namespace CDavison\Queue;

class MemoryQueue implements QueueInterface
{
    /**
     * The jobs left in this queue.
     *
     * @var array
     */
    protected $payloads = [];

    /**
     * {@inheritdoc}
     */
    public function push($payload)
    {
        array_unshift($this->payloads, $payload);
    }

    /**
     * {@inheritdoc}
     */
    public function pop()
    {
        return array_pop($this->payloads);
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->payloads);
    }
}
