<?php

namespace CDavison\Queue;

interface QueueInterface extends \Countable
{
    /**
     * Push a payload onto the queue.
     *
     * @param mixed $payload
     */
    public function push($payload);

    /**
     * Pop a payload off the queue.
     *
     * @return mixed The next payload to be processed.
     */
    public function pop();

    /**
     * @return int The number of payloads in this queue.
     */
    public function count();
}
