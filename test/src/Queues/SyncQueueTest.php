<?php
namespace CDavison\Test\Queue;

use CDavison\Queue\Queues\SyncQueue;

use PHPUnit_Framework_Assert as Assert;

class SyncQueueTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->job_one = $this->getMock('CDavison\Queue\JobInterface');
        $this->job_one->job_number = 1;

        $this->job_two = $this->getMock('CDavison\Queue\JobInterface');
        $this->job_two->job_number = 2;
    }

    public function testSize()
    {
        $queue = new SyncQueue();

        $this->assertEquals(0, $queue->size());

        $rqueue = new \ReflectionClass(
            'CDavison\Queue\Queues\SyncQueue'
        );

        $rjobs = $rqueue->getProperty('jobs');
        $rjobs->setAccessible(true);
        $rjobs->setValue($queue, [1, 2, 3]);

        $this->assertEquals(3, $queue->size());
    }

    public function testPush()
    {
        $queue = new SyncQueue();

        $this->assertAttributeEquals(array(), 'jobs', $queue);

        $queue->push($this->job_one);

        $this->assertAttributeEquals([$this->job_one], 'jobs', $queue);
    }

    public function testPop()
    {
        $queue = new SyncQueue();

        $queue->push($this->job_one);

        $this->assertEquals($this->job_one, $queue->pop());

        $this->assertAttributeEquals([], 'jobs', $queue);
    }

    public function testFifo()
    {
        $queue = new SyncQueue();

        $queue->push($this->job_one);
        $queue->push($this->job_two);

        $this->assertEquals($this->job_one, $queue->pop());
        $this->assertEquals($this->job_two, $queue->pop());
        $this->assertEquals(null, $queue->pop());
    }

}