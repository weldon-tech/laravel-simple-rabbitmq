<?php

namespace Weldon\SimpleRabbit\Exceptions;

use Exception;
use Throwable;

class DefaultConnectionNotFoundException extends Exception
{
    /**
     * NoNameQueueException constructor.
     */
    public function __construct(string $message = "No queue name provided and 'queue' not defined in configuration.", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
