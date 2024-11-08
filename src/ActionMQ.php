<?php

namespace Usmonaliyev\SimpleRabbit;

use PhpAmqpLib\Message\AMQPMessage;

class ActionMQ
{
    /**
     * Stores the registered actions and their handlers.
     *
     * @var array<string, array|callable>
     */
    private array $actions = [];

    /**
     * Register a new action
     */
    public function register(string $action, array|callable $handler): void
    {
        $this->actions[$action] = $handler;
    }

    /**
     * Getter of actions property
     *
     * @return array[]|callable[]
     */
    public function getActions(): array
    {
        return $this->actions;
    }

    /**
     * Loading actions from route/actions.php
     */
    public function load(): void
    {
        $callback = fn () => include_once base_path('routes/actions.php');

        $callback();
    }

    public function consume(AMQPMessage $message)
    {
        $message->ack();
    }

    public function dispatch() {}
}
