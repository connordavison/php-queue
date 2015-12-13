<?php
namespace CDavison\Queue;

use CDavison\Queue\JobInterface;
use CDavison\Queue\AbstractWorker;
use CDavison\Queue\JobQueueInterface;

abstract class AbstractDispatcher
{
    /**
     * @var JobQueueInterface $queue
     */
    protected $queue;

    /**
     * @var AbstractWorker[] $workers
     */
    protected $workers;

    /**
     * Milliseconds to sleep between loops.
     * 
     * @var int
     */
    protected $loop_timeout = 10000;

    /**
     * Initialise a dispatcher on a queue with a list of workers to use.
     *
     * @param JobQueueInterface $queue
     * @param array $workers
     */
    public function __construct(JobQueueInterface $queue, array $workers)
    {
        $this->queue = $queue;
        $this->workers = $workers;
    }

    /**
     * Attempt to dispatch a job to a worker.
     *
     * @param JobInterface $job The job to be dispatched.
     * @param WorkerInterface $worker The worker to which the job should be
     * dispatched.
     * @return void
     */
    protected abstract function dispatch(
        JobInterface $job,
        WorkerInterface $worker
    );

    /**
     * Run this dispatcher.
     *
     * @return void
     */
    public function run()
    {
        while (true) {
            $this->loop();
            usleep(1E3 * $this->loop_timeout);
        }
    }

    /**
     * This is the main execution loop of the dispatcher.
     *
     * @return void
     */
    protected function loop()
    {
        foreach ($this->workers as $worker) {
            // Dispatch jobs to any available workers
            if (!$worker->isBusy() && $this->queue->size() > 0) {
                $job = $this->queue->pop();
                $this->dispatch($job, $worker);
            }
        }
    }
}