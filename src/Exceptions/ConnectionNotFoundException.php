<?php

namespace Usmonaliyev\SimpleRabbit\Exceptions;

use Exception;
use Throwable;

class ConnectionNotFoundException extends Exception
{
    /**
     * ConnectionNotFoundException constructor.
     */
    public function __construct(string $name, int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("RabbitMq connection configuration for '{$name}' not found.", $code, $previous);
    }
}
