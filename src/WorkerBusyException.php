<?php
namespace CDavison\Queue;

use CDavison\Queue\WorkerInterface;

class WorkerBusyException extends \Exception
{
    /**
     * The busy worker.
     *
     * @var WorkerInterface
     */
    protected $worker;

    /**
     * Create a busy worker exception.
     *
     * @param WorkerInterface The busy worker.
     * @param string|null $message
     * @param integer $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $worker,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->setWorker($worker);
    }

    /**
     * Attach a worker to this exception.
     *
     * @param WorkerInterface $worker
     */
    protected function setWorker(WorkerInterface $worker)
    {
        $this->worker = $worker;
    }

    /**
     * Retrieve the worker that generated this error.
     * 
     * @return WorkerInterface
     */
    public function getWorker()
    {
        return $this->worker;
    }
}