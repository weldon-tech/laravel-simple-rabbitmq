<?php

namespace Usmonaliyev\SimpleRabbit;

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
}
