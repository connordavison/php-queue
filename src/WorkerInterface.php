<?php

namespace CDavison\Queue;

interface WorkerInterface
{
    /**
     * Run a job on this worker.
     *
     * @param $payload
     * @throws WorkerBusyException If this worker is busy.
     * @return void The supplied job's status should be modified to indicate
     *     progress.
     */
    public function run($payload);

    /**
     * Identify if this worker is able to support a payload.
     *
     * @param $payload
     * @return bool True if this worker can run $job.
     */
    public function supports($payload);
}
