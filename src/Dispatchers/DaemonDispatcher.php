<?php

namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;
use Ko\ProcessManager;

class DaemonDispatcher extends AbstractDispatcher
{
    /**
     * @var ProcessManager
     */
    protected $manager;

    /**
     * The maximum number of workers that can be dispatched at any given time.
     *
     * @var int
     */
    protected $max_workers;

    /**
     * Construct a DaemonDispatcher.
     *
     * @param QueueInterface $queue The queue from which this dispatcher will
     *     pull jobs.
     * @param WorkerInterface $worker The worker which should run jobs from the
     *     queue.
     * @param int $max_workers The maximum number of dispatched workers this
     *     instance can support.
     */
    public function __construct(
        QueueInterface $queue,
        WorkerInterface $worker,
        $max_workers
    ) {
        parent::__construct($queue, $worker);
        $this->max_workers = $max_workers;

        $this->setManager(new ProcessManager());
    }

    /**
     * {@inheritdoc} This dispatcher defers worker timeout to post-dispatch.
     *
     * @return void
     */
    public function run()
    {
        if ($this->manager->count() >= $this->max_workers) {
            $this->manager->wait();
        } elseif ($this->queue->size()) {
            $this->dispatch($this->queue->pop());
        }
    }

    /**
     * Dispatch a job to a worker.
     *
     * @param mixed $job
     * @return void
     */
    public function dispatch($job)
    {
        $this->manager->fork($this->getDispatchAction($job));
    }

    /**
     * Obtain this dispatcher's run procedure for a given job.
     *
     * @param mixed $job
     * @return \Closure
     */
    public function getDispatchAction($job)
    {
        $worker = $this->worker;
        $timeout = $this->getWorkerTimeout();

        return function () use ($worker, $job, $timeout) {
            $worker->run($job);
            usleep(1E3 * $timeout);
        };
    }

    /**
     * Set the process manager for this dispatcher.
     *
     * @param ProcessManager $manager
     */
    public function setManager(ProcessManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Get the process manager for this dispatcher.
     *
     * @return ProcessManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}
