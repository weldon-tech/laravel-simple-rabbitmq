<?php

namespace Usmonaliyev\SimpleRabbit\MQ;

use Exception;
use Illuminate\Support\Facades\Config;
use Usmonaliyev\SimpleRabbit\Exceptions\NoNameQueueException;

class ConnectionManager
{
    /**
     * @var array
     */
    private mixed $config;

    /**
     * @var array<string, Connection>
     */
    private array $connections = [];

    public function __construct()
    {
        $this->config = Config::get('simple-mq');
    }

    /**
     * Get a connection instance.
     *
     * @throws Exception
     */
    public function connection(?string $name = null): Connection
    {
        $name = $name ?? $this->config['queue'] ?? null;

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
