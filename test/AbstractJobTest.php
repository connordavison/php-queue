<?php
namespace CDavison\Test\Queue;

use CDavison\Queue\AbstractJob;
use CDavison\Queue\JobStatus;

use PHPUnit_Framework_Assert as Assert;

class AbstractJobTest extends \PHPUnit_Framework_TestCase
{
    public static $default_payload = 123;

    public function setUp()
    {
        $this->job = $this->getMockForAbstractClass(
            'CDavison\Queue\AbstractJob',
            [self::$default_payload] // Inject default payload into constructor
        );
    }

    public function testConstructor()
    {
        $this->assertEquals(
            JobStatus::NONE,
            Assert::readAttribute($this->job, 'status')
        );

        $this->assertEquals(
            self::$default_payload,
            Assert::readAttribute($this->job, 'payload')
        );
    }

    public function testGetPayload()
    {
        $this->assertEquals(self::$default_payload, $this->job->getPayload());
    }

    public function testSetStatus()
    {
        $this->job->setStatus(JobStatus::WAITING);

        $this->assertEquals(
            JobStatus::WAITING,
            Assert::readAttribute($this->job, 'status')
        );
    }

    /**
     * @expectedException \DomainException
     */
    public function testSetStatusWithInvalidStatus()
    {
        $this->job->setStatus(-1);
    }

    public function testGetStatus()
    {
        $this->job->setStatus(JobStatus::SLEEPING);

        $this->assertEquals(JobStatus::SLEEPING, $this->job->getStatus());
    }
}