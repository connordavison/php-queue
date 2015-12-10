<?php
namespace CDavison\Queue;

use CDavison\Queue\AbstractJob;
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
     * @param AbstractJob $job The job to be dispatched.
     * @param WorkerInterface $worker The worker to which the job should be
     * dispatched.
     * @return void
     */
    protected abstract function dispatch(
        AbstractJob $job,
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