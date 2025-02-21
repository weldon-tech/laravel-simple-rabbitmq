<?php

namespace Weldon\SimpleRabbit\MQ;

use Exception;
use Illuminate\Support\Facades\Config;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Weldon\SimpleRabbit\Exceptions\ConnectionNotFoundException;

class Connection
{
    /**
     * Connection name which is in ~/config/simple-mq.php
     */
    private $name;

    private AMQPStreamConnection $connection;

    /**
     * Initializes the RabbitMQ connection with the provided configuration.
     *
     * @throws Exception If required configuration is missing.
     */
    public function __construct(string $name)
    {
        $this->name = $name;

        $config = Config::get("simple-mq.connections.$name");

        if (! isset($config)) {
            throw new ConnectionNotFoundException($name);
        }

        $this->connection = new AMQPStreamConnection(
            $config['host'],
            $config['port'],
            $config['username'],
            $config['password'],
            $config['vhost'],
            $config['insist'],
            $config['login_method'],
            $config['login_response'],
            $config['locale'],
            $config['connection_timeout'],
            $config['read_write_timeout'],
            $config['context'],
            $config['keepalive'],
            $config['heartbeat'],
            $config['channel_rpc_timeout'],
            $config['ssl_protocol']
        );
    }

    /**
     * Get a publisher for the specified queue.
     */
    public function queue(?string $queueName = null): MessageBuilder
    {
        $queueName = $queueName ?? Config::get('simple-mq.queue');

        return new MessageBuilder($this->name, $queueName, 'QUEUE');
    }

    /**
     * Get a publisher for the specified exchange.
     */
    public function exchange($exchangeName): MessageBuilder
    {
        return new MessageBuilder($this->name, $exchangeName, 'EXCHANGE');
    }

    /**
     * Returns the active AMQPStreamConnection connection.
     *
     * @throws Exception
     */
    public function getAMQPConnection(): AMQPStreamConnection
    {
        return $this->connection;
    }

    /**
     * Retrieves an AMQP channel for the current connection.
     * If no channel exists, a new channel is created and returned.
     * Channels are used to interact with RabbitMQ, allowing you to
     * publish messages, consume messages, and perform other operations.
     *
     * @return AMQPChannel The channel associated with the current connection.
     *
     * @throws Exception If the channel cannot be created or the connection is invalid.
     */
    public function getChannel(): AMQPChannel
    {
        return $this->connection->channel();
    }

    /**
     * Definition section manager queues and exchange
     */
    public function getDefinition(): Definition
    {
        return new Definition($this->connection);
    }

    /**
     * Close channel and connection before destruction
     *
     * @throws Exception
     */
    public function __destruct()
    {
        $this->connection->close();
    }
}
