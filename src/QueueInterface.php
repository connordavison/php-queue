<?php

namespace CDavison\Queue;

interface QueueInterface
{
    /**
     * Push a payload onto the queue.
     *
     * @param mixed $payload
     */
    public function push($payload);

    /**
     * Pop a job off the queue.
     *
     * @return mixed The next payload to be processed.
     */
    public function pop();

    /**
     * @return int The size of this queue.
     */
    public function size();
}
