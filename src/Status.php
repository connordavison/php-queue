<?php

namespace CDavison\Queue;

class Status
{
    /**
     * @var int NONE Indicates that an object is detached from a working
     *     environment.
     */
    const NONE = 0;

    /**
     * @var int WAITING Indicates that an object is awaiting a particular event.
     */
    const WAITING = 1;

    /**
     * @var int RUNNING Indicates that an object is participating in an event.
     */
    const RUNNING = 2;

    /**
     * @var int SLEEPING Indicates that an object is participating in an event
     *     which cannot be continued for some reason.
     */
    const SLEEPING = 3;

    /**
     * @var int RELEASED Indicates that an object has been dismissed from an
     *     event before the event's completion.
     */
    const RELEASED = 4;

    /**
     * @var int COMPLETED Indicates that an object has participated in an event
     *     which ended in a non-negative outcome.
     */
    const COMPLETED = 5;

    /**
     * @var int FAILED Indicates that an object has participated in an event
     *     which ended in a negative outcome.
     */
    const FAILED = 6;
}
