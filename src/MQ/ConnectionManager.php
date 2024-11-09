<?php

namespace Usmonaliyev\SimpleRabbit\MQ;

use Exception;
use Illuminate\Support\Facades\Config;
use Usmonaliyev\SimpleRabbit\Exceptions\NoNameQueueException;

class ConnectionManager
{
    /**
     * @var array<string, Connection>
     */
    private array $connections = [];

    /**
     * Get a connection instance.
     *
     * @throws Exception Default queue is not configured
     */
    public function connection(?string $name = null): Connection
    {
        $name = $name ?? Config::get('simple-mq.connection');

        if ($name === null) {
            throw new NoNameQueueException;
        }

        return $this->connections[$name] ??= $this->make($name);
    }

    /**
     * Create a new AMQPStreamConnection instance.
     *
     * @throws Exception
     */
    protected function make(string $name): Connection
    {
        return new Connection($name);
    }
}
