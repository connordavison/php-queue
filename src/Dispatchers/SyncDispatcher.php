<?php
namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\DispatcherInterface;
use CDavison\Queue\JobInterface;
use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;

class SyncDispatcher implements DispatcherInterface
{
    /**
     * @var QueueInterface $queue
     */
    protected $queue;

    /**
     * @var WorkerInterface $worker
     */
    protected $worker;

    /**
     * Milliseconds to sleep between loops.
     *
     * @var int
     */
    protected $loop_timeout = 10000;

    /**
     * Initialise a dispatcher on a queue with a list of workers to use.
     *
     * @param QueueInterface $queue
     * @param array $workers
     */
    public function __construct(QueueInterface $queue, WorkerInterface $worker)
    {
        $this->setQueue($queue);
        $this->setWorker($worker);
    }

    /**
     * Attempt to dispatch a job to a worker.
     *
     * @param JobInterface $job The job to be dispatched.
     * @param WorkerInterface $worker The worker to which the job should be
     * dispatched.
     * @return void
     */
    protected function dispatch(JobInterface $job, WorkerInterface $worker)
    {
        $worker->run($job);
    }

    /**
     * {@inheritdoc}
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
        // Dispatch jobs to any available workers
        if (!$this->worker->isBusy() && $this->queue->size() > 0) {
            $job = $this->queue->pop();
            $this->dispatch($job, $this->worker);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setQueue(QueueInterface $queue)
    {
        $this->queue = $queue;
    }

    /**
     * Set the worker that this dispatcher will use.
     *
     * @param WorkerInterface $worker
     */
    public function setWorker(WorkerInterface $worker)
    {
        $this->worker = $worker;
    }
}