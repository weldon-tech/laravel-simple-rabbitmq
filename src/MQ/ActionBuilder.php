<?php

namespace Usmonaliyev\SimpleRabbit\MQ;

use Exception;
use Illuminate\Support\Facades\App;
use PhpAmqpLib\Message\AMQPMessage;

class ActionBuilder
{
    /**
     * The action type ("QUEUE" or "EXCHANGE").
     */
    private string $action;

    private MessageBuilder $messageBuilder;

    public function __construct(MessageBuilder $messageBuilder, string $action)
    {
        $this->action = $action;
        $this->messageBuilder = $messageBuilder;
    }

    /**
     * Publish the message
     */
    public function publish(): void
    {
        $type = $this->messageBuilder->getType();

        $message = $this->messageBuilder
            ->addHeader('Action', $this->action)
            ->getMessage();

        $to = $this->messageBuilder->to();

        $this->{$type}($message, $to);
    }

    /**
     * Handle publishing to a Queue.
     *
     * @throws Exception
     */
    private function QUEUE(AMQPMessage $message, string $queue): void
    {
        $connection = $this->getConnection();
        $channel = $connection->getChannel();

        $channel->basic_publish($message, '', $queue);
    }

    /**
     * Handle publishing to an Exchange.
     *
     * @throws Exception
     */
    private function EXCHANGE(AMQPMessage $message, string $exchange): void
    {
        $connection = $this->getConnection();
        $channel = $connection->getChannel();

        $routingKey = $this->messageBuilder->getRoutingKey();

        $channel->basic_publish($message, $exchange, $routingKey);
    }

    /**
     * Retrieve the active RabbitMQ connection.
     *
     * @throws Exception if connection cannot be established.
     */
    private function getConnection(): Connection
    {
        $manager = App::make(ConnectionManager::class);

        return $manager->connection($this->messageBuilder->getConnectionName());

    }
}
