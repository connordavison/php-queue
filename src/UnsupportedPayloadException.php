<?php

namespace CDavison\Queue;

class UnsupportedPayloadException extends \UnexpectedValueException
{
    /**
     * The payload that caused this exception.
     *
     * @var mixed
     */
    protected $payload;

    /**
     * Create an UnsupportedPayloadException.
     *
     * @param mixed $payload The payload not supported by the worker.
     * @param string|null $message
     * @param int $code
     * @param \Exception|null $previous
     */
    public function __construct(
        $payload,
        $message = null,
        $code = 0,
        \Exception $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->payload = $payload;
    }

    /**
     * Retrieve the payload that caused this exception.
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }
}
