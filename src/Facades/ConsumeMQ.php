<?php

namespace Usmonaliyev\SimpleRabbit\Facades;

use Illuminate\Support\Facades\Facade;
use RuntimeException;

/**
 * @method static void listen(callable $callback)
 * @method static \Usmonaliyev\SimpleRabbit\ConsumeMQ queue(string $name = null)
 * @method static \Usmonaliyev\SimpleRabbit\ConsumeMQ connection(string $name = null)
 *
 * @see \Usmonaliyev\SimpleRabbit\ConsumeMQ
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
        return \Usmonaliyev\SimpleRabbit\ConsumeMQ::class;
    }
}
