<?php

use CDavison\Queue\SyncQueue;

class SyncQueueTest extends \PHPUnit_Framework_TestCase
{
    /**
     * This procedure tests that a queue:
     *     - Can push items to the end of the queue
     *     - Can pop items from the front of the queue
     *     - Can count the amount of items in the queue
     */
    public function testQueueBehaviours()
    {
        $queue = new SyncQueue();

        $this->assertEquals(0, $queue->count());

        $queue->push('a');
        $queue->push('b');
        $queue->push('c');

        $this->assertEquals(3, $queue->count());

        $this->assertEquals('a', $queue->pop());

        $this->assertEquals(2, $queue->count());

        $this->assertEquals('b', $queue->pop());

        $this->assertEquals(1, $queue->count());

        $queue->push('d');
        $queue->push('e');

        $this->assertEquals(3, $queue->count());

        $this->assertEquals('c', $queue->pop());
        $this->assertEquals('d', $queue->pop());
        $this->assertEquals('e', $queue->pop());
    }
}
