<?php
namespace CDavison\Queue;

interface WorkerInterface
{
    /**
     * Run a job on this worker.
     * 
     * @param JobInterface $job
     * @throws WorkerBusyException If this worker is busy.
     * @return void The supplied job's status should be modified to indicate
     * progress.
     */
    public function run(JobInterface $job);

    /**
     * Attempt to retrieve the status of this job.
     * 
     * @return mixed The worker's status.
     */
    public function getStatus();

    /**
     * Identify if this worker is able to support a job.
     * 
     * @param JobInterface $job
     * @return bool True if this worker can run $job.
     */
    public function supports(JobInterface $job);
}