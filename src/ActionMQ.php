<?php

namespace Weldon\SimpleRabbit;

use Exception;
use Illuminate\Support\Facades\App;
use PhpAmqpLib\Message\AMQPMessage;
use Weldon\SimpleRabbit\MQ\Message;

class ActionMQ
{
    /**
     * Stores the registered actions and their handlers.
     *
     * @var array<string, array|callable>
     */
    private array $handlers = [];

    /**
     * Register a new action
     */
    public function register(string $handler, array|callable $callback): void
    {
        $this->handlers[$handler] = $callback;
    }

    /**
     * Getter of actions property
     *
     * @return array[]|callable[]
     */
    public function getHandlers(): array
    {
        return $this->handlers;
    }

    /**
     * Loading actions from route/amqp-handlers.php
     */
    public function load(): void
    {
        $callback = fn () => include_once base_path('routes/amqp-handlers.php');

        $callback();
    }

    /**
     * Main consumer
     */
    public function consume(AMQPMessage $amqpMessage): mixed
    {
        $message = new Message($amqpMessage);

        // If there is no handler which match to message, message is deleted
        if (! isset($this->handlers[$message->getHandler()])) {
            $message->ack();

            return null;
        }

        return $this->dispatch($message);
    }

    /**
     * Dispatcher to execute handler
     */
    protected function dispatch($message): mixed
    {
        $handler = $this->handlers[$message->getHandler()];

        try {
            if (is_callable($handler)) {
                return call_user_func_array($handler, [$message]);
            }

            [$class, $method] = $handler;
            $instance = App::make($class);

            return $instance->{$method}($message);

        } catch (Exception $e) {
            $error = sprintf('ERROR [%s] %s: %s'.PHP_EOL, gmdate('Y-m-d H:i:s'), get_class($e), $e->getMessage());
            echo $error;

            return null;
        }
    }
}
