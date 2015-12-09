<?php
namespace CDavison\Queue;

use MabeEnum\Enum;

class JobStatus extends Enum
{
    /**
     * @var int NONE Indicates that no queue is aware of a job.
     */
    const NONE = 0;

    /**
     * @var int WAITING Indicates that a job is waiting to be processed.
     */
    const WAITING = 1;

    /**
     * @var int RUNNING Indicates that a job is running.
     */
    const RUNNING = 2;

    /**
     * @var int SLEEPING Indicates that a job is sleeping and hasn't yet
     * completed.
     */
    const SLEEPING = 3;

    /**
     * @var int RELEASED Indicates that a job has been released to its queue
     * to be finished later.
     */
    const RELEASED = 4;

    /**
     * @var int COMPLETED Indicates that a job has completed successfully.
     */
    const COMPLETED = 5;

    /**
     * @var int FAILED Indicates that a job has failed.
     */
    const FAILED = 6;
}