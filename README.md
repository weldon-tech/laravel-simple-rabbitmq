# laravel-simple-rabbitmq

The package for simplified RabbitMQ usage, supporting multiple connections, easy publishing, and consumer mode.

### Key Features

- **Multiple Connections**: Effortlessly manage multiple RabbitMQ connections within the same application.

- **Exchange supporting**: You can push messages to exchanges

- **Message Publishing**: Easily publish messages to queues and exchange with a fluent, chainable syntax.

- **Consumer Mode**: Enable consumers to receive and process messages from queues in real time.

- **Manage queues and exchanges in config file**: You can register queues and exchanges in `config/simple-mq.php` and
  define them in easy way which is `amqp:define-queues` command.

## Todo for `README.md` file

- [x] Short description
- [x] Long description
- [x] Installation
- [ ] Usage
- [x] Plans
- [ ] Testing

## Installation

You can install the package via composer:

```bash
composer require usmonaliyev/laravel-simple-rabbitmq
```

Next step you must publish config and action register files:

```bash
php artisan vendor:publish --provider="Usmonaliyev\SimpleRabbit\SimpleRabbitMQServiceProvider"
```

As a result of this command, you will have a configuration file `config/simple-mq.php` and a registry file
`routes/actions.php`.

The `config/simple-mq.php` config file contains RabbitMQ connections with credentials, queues, default connection and
default queue.

The next stage is configure `.env` file.

```.dotenv
SIMPLE_MQ_CONNECTION=
SIMPLE_MQ_QUEUE=

SIMPLE_MQ_HOST=
SIMPLE_MQ_PORT=
SIMPLE_MQ_USERNAME=
SIMPLE_MQ_PASSWORD=
```

## Usage

```php
// Usage description here
```

## Plans

- [x] Setup consumer mode as `routes/actions.php`
- [ ] Adding `ampq:define-queues` command.
- [ ] Adding `ampq:make-action {action} {function}` command.
- [ ] Adding `ampq:listen {connection=''} {queue=''}` command.
- [ ] Writing `README.UZ.md` and `README.RU.md` files.
- [ ] Setup testing.

## Testing

```bash
composer test
```

## License

The [MIT](LICENSE.md) License.