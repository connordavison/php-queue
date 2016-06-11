<?php

namespace CDavison\Queue;

interface WorkerInterface
{
    /**
     * Run a job on this worker.
     *
     * @param mixed $payload
     * @throws WorkerBusyException If this worker is busy.
     * @return void The supplied payload's status should be modified to indicate
     *     progress.
     */
    public function run($payload);

    /**
     * Identify if this worker is able to support a payload.
     *
     * @param mixed $payload
     * @return bool True if this worker can consume $payload.
     */
    public function supports($payload);
}
