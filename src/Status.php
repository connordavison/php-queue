<?php

namespace CDavison\Queue;

class Status
{
    /**
     * @const int NONE Indicates that an object is detached from a working
     *     environment.
     */
    const NONE = 0;

    /**
     * @const int WAITING Indicates that an object is awaiting a particular
     *     event.
     */
    const WAITING = 1;

    /**
     * @const int RUNNING Indicates that an object is participating in an event.
     */
    const RUNNING = 2;

    /**
     * @const int SLEEPING Indicates that an object is participating in an event
     *     which cannot be continued for some reason.
     */
    const SLEEPING = 3;

    /**
     * @const int RELEASED Indicates that an object has been dismissed from an
     *     event before the event's completion.
     */
    const RELEASED = 4;

    /**
     * @const int COMPLETED Indicates that an object has participated in an
     *     event which ended in a non-negative outcome.
     */
    const COMPLETED = 5;

    /**
     * @const int FAILED Indicates that an object has participated in an event
     *     which ended in a negative outcome.
     */
    const FAILED = 6;
}
