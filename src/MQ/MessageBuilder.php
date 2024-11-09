<?php

namespace Usmonaliyev\SimpleRabbit\MQ;

use PhpAmqpLib\Message\AMQPMessage;
use PhpAmqpLib\Wire\AMQPTable;

class MessageBuilder
{
    /**
     * RabbitMq connection name
     */
    private string $connectionName;

    /**
     * The message will send to this queue or exchange
     */
    private string $to;

    /**
     * QUEUE or EXCHANGE
     */
    private string $type;

    /**
     * This is a message attribute used in exchanges
     */
    private string $routingKey = '';

    private array $body = [];

    private ?AMQPMessage $message = null;

    // Properties array containing message properties with default values
    private array $properties = [
        'content_type' => 'application/json',
        'content_encoding' => 'utf-8',
        'application_headers' => [],
        'priority' => 0,
        'correlation_id' => null,
        'reply_to' => null,
        'timestamp' => null,
    ];

    public function __construct(string $connectionName, string $to, string $type)
    {
        $this->connectionName = $connectionName;
        $this->to = $to;
        $this->type = $type;

        $this->properties['timestamp'] = time();
    }

    /**
     * Creates a Publisher instance to handle specific actions.
     */
    public function handler(string $name): Publisher
    {
        return new Publisher($this, $name);
    }

    /**
     * Get the AMQPMessage instance
     */
    public function getMessage(): AMQPMessage
    {
        if ($this->message === null) {
            $body = $this->getBodyAsJson();
            $properties = $this->toProperties();

            $this->message = new AMQPMessage($body, $properties);
        }

        return $this->message;
    }

    /**
     * Set headers for the message
     */
    public function setHeaders(array $headers): self
    {
        $this->properties['application_headers'] = $headers;

        return $this;
    }

    /**
     * Add key value pair to existing headers
     */
    public function addHeader($key, $value): self
    {
        $this->properties['application_headers'][$key] = $value;

        return $this;
    }

    public function getRoutingKey(): string
    {
        return $this->routingKey;
    }

    /**
     * Get the headers of the message
     */
    public function getHeaders(): array
    {
        return $this->properties['application_headers'];
    }

    /**
     * Set the message body
     *
     * Stores the body data and invalidates the cached AMQPMessage.
     */
    public function setBody(array $body): self
    {
        $this->body = $body;
        $this->message = null;

        return $this;
    }

    /**
     * Get the body as a JSON string
     */
    public function getBodyAsJson(): string
    {
        return json_encode($this->body);
    }

    protected function toProperties(): array
    {
        $header = $this->buildHeader();
        $this->properties['application_headers'] = $header;

        return $this->properties;
    }

    /**
     * Builds and returns message header
     */
    private function buildHeader(): AMQPTable
    {
        return new AMQPTable($this->properties['application_headers']);
    }

    /**
     * Returns QUEUE or EXCHANGE
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Returns connection name
     */
    public function getConnectionName(): string
    {
        return $this->connectionName;
    }

    /**
     * If returns name of ("QUEUE" or "EXCHANGE")
     */
    public function to(): string
    {
        return $this->to;
    }
}
