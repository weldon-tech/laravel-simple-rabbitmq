<?php

namespace Usmonaliyev\SimpleRabbit;

use Exception;
use Illuminate\Support\Facades\App;
use Usmonaliyev\SimpleRabbit\MQ\Connection;
use Usmonaliyev\SimpleRabbit\MQ\ConnectionManager;

class SimpleMQ
{
    /**
     * Retrieve a connection by name or the default connection.
     *
     * @throws Exception
     */
    public function connection(?string $name = null): Connection
    {
        return $this->getManager()->connection($name);
    }

    /**
     * Retrieve a queue publisher for the specified queue.
     *
     * @throws Exception
     */
    public function queue(?string $queueName = null): MQ\MessageBuilder
    {
        return $this->connection()->queue($queueName);
    }

    /**
     * Retrieve an exchange publisher for the specified exchange.
     *
     * @throws Exception
     */
    public function exchange(string $exchangeName): MQ\MessageBuilder
    {
        return $this->connection()->exchange($exchangeName);
    }

    /**
     * Retrieve the ConnectionManager instance.
     *
     * @return ConnectionManager The connection manager.
     */
    protected function getManager(): ConnectionManager
    {
        // Resolve ConnectionManager only once and reuse it for consistency.
        return App::make(ConnectionManager::class);
    }

    /**
     * Set a message body directly with default connection and default queue
     *
     * @throws Exception
     */
    public function setBody(array $body): MQ\MessageBuilder
    {
        return $this->connection()->queue()->setBody($body);
    }

    /**
     * Set a message headers with default connection and default queue
     *
     * @throws Exception
     */
    public function setHeaders(array $headers): MQ\MessageBuilder
    {
        return $this->connection()->queue()->setHeaders($headers);
    }
}
