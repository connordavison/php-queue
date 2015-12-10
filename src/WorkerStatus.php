<?php
namespace CDavison\Queue;

use MabeEnum\Enum;

class WorkerStatus extends Enum
{
    /**
     * @var int Indicates that a worker is awaiting a new job.
     */
    const WAITING = 0;

    /**
     * @var int Indicates that a worker is processing a job.
     */
    const WORKING = 1;
}