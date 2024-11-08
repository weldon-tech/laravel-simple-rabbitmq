<?php

namespace Usmonaliyev\SimpleRabbit\MQ;

use Exception;
use Illuminate\Support\Facades\Config;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use Usmonaliyev\SimpleRabbit\Exceptions\ConnectionNotFoundException;

class Connection
{
    private string $connectionName;

    private AMQPStreamConnection $connection;

    private ?AMQPChannel $channel = null;

    /**
     * Initializes the RabbitMQ connection with the provided configuration.
     *
     * @throws Exception If required configuration is missing.
     */
    public function __construct(string $name)
    {
        $this->connectionName = $name;

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

        return new MessageBuilder($this->connectionName, $queueName, 'QUEUE');
    }

    /**
     * Get a publisher for the specified exchange.
     */
    public function exchange($exchangeName): MessageBuilder
    {
        return new MessageBuilder($this->connectionName, $exchangeName, 'EXCHANGE');
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
     * Returns a channel, creating one if it does not already exist.
     *
     * @throws Exception
     */
    public function getChannel(): AMQPChannel
    {
        return $this->channel ??= $this->connection->channel();
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
        $this->channel?->close();
        $this->connection->close();
    }
}
