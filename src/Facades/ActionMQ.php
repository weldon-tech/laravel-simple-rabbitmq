<?php

namespace Weldon\SimpleRabbit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static array getActions()
 * @method static void register(string $action, callable|array $handler)
 *
 * @see \Weldon\SimpleRabbit\ActionMQ::class
 */
class ActionMQ extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return \Weldon\SimpleRabbit\ActionMQ::class;
    }
}
