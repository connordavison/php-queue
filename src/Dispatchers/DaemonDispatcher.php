<?php
namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\AbstractDispatcher;
use CDavison\Queue\JobInterface;
use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;
use Ko\ProcessManager;

class DaemonDispatcher extends AbstractDispatcher
{
    /**
     * @var ProcessManager $manager
     */
    protected $manager;

    /**
     * Construct a DaemonDispatcher.
     *
     * @param QueueInterface $queue The queue from which this dispatcher will
     * pull jobs.
     * @param WorkerInterface $worker The worker which should run jobs from the
     * queue.
     * @param int $max_workers The maximum number of dispatched workers this
     * instance can support.
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
     * Dispatch a job to a worker.
     *
     * @param JobInterface $job
     * @return void
     */
    protected function dispatch(JobInterface $job)
    {
        $worker = $this->worker;
        $timeout = $this->getWorkerTimeout();

        $this->manager->fork(
            function (\Ko\Process $p) use ($worker, $job, $timeout) {
                $worker->run($job);
                usleep(1E3 * $timeout);
            }
        );
    }

    /**
     * {@inheritdoc} This dispatcher defers worker timeout to post-dispatch.
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
     * {@inheritdoc}
     */
    public function loop()
    {
        if ($this->manager->count() >= $this->max_workers) {
            $this->manager->wait();
        } else if ($this->queue->size()) {
            $this->dispatch($this->queue->pop());
        }
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
