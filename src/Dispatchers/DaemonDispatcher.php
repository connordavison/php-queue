<?php
namespace CDavison\Queue\Dispatchers;

use CDavison\Queue\AbstractDispatcher;
use CDavison\Queue\JobInterface;
use CDavison\Queue\QueueInterface;
use CDavison\Queue\WorkerInterface;

class DaemonDispatcher extends AbstractDispatcher
{
    /**
     * The PIDs of dispatched workers.
     *
     * @var int[]
     */
    protected $children = array();

    /**
     * {@inheritdoc}
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
    }

    /**
     * Dispatch a job to a worker.
     *
     * @param JobInterface $job
     * @param WorkerInterface $worker
     * @return void
     */
    protected function dispatch(JobInterface $job)
    {
        $pid = pcntl_fork();

        if (-1 === $pid) {
            throw new \RuntimeException("Could not fork job to worker.");
        } else if ($pid) {
            $this->children[] = $pid;
        } else {
            $this->worker->run($job);
            usleep($this->getWorkerTimeout() * 1E3);
            die;
        }
    }

    /**
     * {@inheritdoc} This dispatcher defers loop timeout to post-dispatch.
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
    protected function loop()
    {
        while (count($this->children) >= $this->max_workers) {
            $this->wait();
        }

        if ($this->queue->size()) {
            $this->dispatch($this->queue->pop(), $this->worker);
        }
    }

    /**
     * Wait for a worker to finish.
     *
     * @return void
     */
    protected function wait()
    {
        $pid = pcntl_wait($status);
        $index = array_search($pid, $this->children);

        if (false !== $index) {
            unset($this->children[$index]);
        }
    }

    /**
     * Obtain the PIDs of the dispatched workers.
     *
     * @return int[]
     */
    public function getChildren()
    {
        return $this->children;
    }
}