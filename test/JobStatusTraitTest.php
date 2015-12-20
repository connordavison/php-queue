<?php
use CDavison\Queue\JobStatus;

use PHPUnit_Framework_Assert as Assert;

class JobStatusTraitTest extends \PHPUnit_Framework_TestCase
{
    public static $default_payload;

    public function setUp()
    {
        $this->job = $this->getMockForTrait('CDavison\Queue\JobStatusTrait');
    }

    /**
     * @dataProvider statusGetterProvider
     */
    public function testStatusGetters($status, $descriptions)
    {
        foreach ($descriptions as $method => $expected) {
            $job = $this->getMockForTrait('CDavison\Queue\JobStatusTrait');
            $job->method('getStatus')->willReturn($status);
            $this->assertEquals($expected, $job->$method());
        }
    }

    /**
     * @dataProvider statusSetterProvider
     */
    public function testStatusSetters($method, $status)
    {
        $this->job->expects($this->once())
            ->method('setStatus')
            ->with($status);

        $this->job->$method();
    }

    /**
     * Provides a list of statuses along with a description.
     */
    public function statusGetterProvider()
    {
        return [
            [
                JobStatus::NONE,
                [
                    'isStarted'   => false,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                JobStatus::WAITING,
                [
                    'isStarted'   => false,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                JobStatus::RUNNING,
                [
                    'isStarted'   => true,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                JobStatus::SLEEPING,
                [
                    'isStarted'   => true,
                    'isSleeping'  => true,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                JobStatus::COMPLETED,
                [
                    'isStarted'   => true,
                    'isSleeping'  => false,
                    'isCompleted' => true,
                    'isFailed'    => false,
                    'isFinished'  => true,
                ],
            ],
            [
                JobStatus::FAILED,
                [
                    'isStarted'   => true,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => true,
                    'isFinished'  => true,
                ]
            ]
        ];
    }

    /**
     * Provided a list of status setting methods along with the expected status
     * to be set.
     */
    public function statusSetterProvider()
    {
        return [
            ['start',    JobStatus::RUNNING],
            ['sleep',    JobStatus::SLEEPING],
            ['fail',     JobStatus::FAILED],
            ['complete', JobStatus::COMPLETED],
        ];
    }
}