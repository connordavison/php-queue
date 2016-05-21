<?php

use CDavison\Queue\Status;
use CDavison\Queue\StatusTrait;

class JobStatusTraitTest extends \PHPUnit_Framework_TestCase
{
    public static $default_payload;

    /**
     * @var StatusTrait | \PHPUnit_Framework_MockObject_MockObject
     */
    protected $status_trait;

    public function setUp()
    {
        $this->status_trait = $this->getMockForTrait(StatusTrait::class);
    }

    /**
     * @dataProvider statusGetterProvider
     */
    public function testStatusGetters($status, $descriptions)
    {
        foreach ($descriptions as $method => $expected) {
            $status_trait = $this->getMockForTrait(StatusTrait::class);
            $status_trait->method('getStatus')->willReturn($status);
            $this->assertEquals($expected, $status_trait->$method());
        }
    }

    /**
     * @dataProvider statusSetterProvider
     */
    public function testStatusSetters($method, $status)
    {
        $this->status_trait->expects($this->once())
            ->method('setStatus')
            ->with($status);

        $this->status_trait->$method();
    }

    /**
     * Provides a list of statuses along with a description.
     */
    public function statusGetterProvider()
    {
        return [
            [
                Status::NONE,
                [
                    'isStarted'   => false,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                Status::WAITING,
                [
                    'isStarted'   => false,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                Status::RUNNING,
                [
                    'isStarted'   => true,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                Status::SLEEPING,
                [
                    'isStarted'   => true,
                    'isSleeping'  => true,
                    'isCompleted' => false,
                    'isFailed'    => false,
                    'isFinished'  => false,
                ],
            ],
            [
                Status::COMPLETED,
                [
                    'isStarted'   => true,
                    'isSleeping'  => false,
                    'isCompleted' => true,
                    'isFailed'    => false,
                    'isFinished'  => true,
                ],
            ],
            [
                Status::FAILED,
                [
                    'isStarted'   => true,
                    'isSleeping'  => false,
                    'isCompleted' => false,
                    'isFailed'    => true,
                    'isFinished'  => true,
                ],
            ],
        ];
    }

    /**
     * Provided a list of status setting methods along with the expected status
     * to be set.
     */
    public function statusSetterProvider()
    {
        return [
            ['start',    Status::RUNNING],
            ['sleep',    Status::SLEEPING],
            ['fail',     Status::FAILED],
            ['complete', Status::COMPLETED],
        ];
    }
}
