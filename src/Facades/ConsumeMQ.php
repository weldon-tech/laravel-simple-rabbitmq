<?php

namespace Weldon\SimpleRabbit\Facades;

use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @method static void listen(callable $callback)
 * @method static \Weldon\SimpleRabbit\ConsumeMQ queue(string $name = null)
 * @method static \Weldon\SimpleRabbit\ConsumeMQ connection(string $name = null)
 *
 * @see \Weldon\SimpleRabbit\ConsumeMQ
 */
class ConsumeMQ extends Facade
{
    /**
     * Get the registered name of the component.
     *
     *
     * @throws RuntimeException
     */
    protected static function getFacadeAccessor(): string
    {
        return \Weldon\SimpleRabbit\ConsumeMQ::class;
    }
}
