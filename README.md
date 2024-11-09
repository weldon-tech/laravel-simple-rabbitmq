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

The package can publish and consume messages

### Publishing

You can publish a message with default connection and default queue:

```php
<?php

use Illuminate\Http\Request;
use Usmonaliyev\SimpleRabbit\Facades\SimpleMQ;

class FooController
{
    public function createFoo(Request $request)
    {
        // Something..
        
        SimpleMQ::queue('foo-queue')
          ->setBody(['name' => 'First Foo'])
          ->handler('create-foo')
          ->publish();
          
        return response()->json(['message' => 'OK']);
    }
}
```

Also, `exchange` function publish message to RabbitMq exchange:

```php
<?php

use Illuminate\Http\Request;
use Usmonaliyev\SimpleRabbit\Facades\SimpleMQ;

class FooController
{
    public function createFoo(Request $request)
    {
        // Something..
        
        SimpleMQ::exchange('foo-exchange')
          ->setBody(['name' => 'First Foo'])
          ->handler('create-foo')
          ->publish();
          
        return response()->json(['message' => 'OK']);
    }
}
```

If you have multiply connection to RabbitMq, you can publish a message with `connection` method.

```php
<?php

use Illuminate\Http\Request;
use Usmonaliyev\SimpleRabbit\Facades\SimpleMQ;

class FooController
{
    public function createFoo(Request $request)
    {
        // Something..
        
        SimpleMQ::connection('foo-connection')
          ->queue('foo-queue')
          ->setBody(['name' => 'First Foo'])
          ->handler('create-foo')
          ->publish();
          
        return response()->json(['message' => 'OK']);
    }
}
```

### Consuming

Create `app/AMQP/Handlers` folder and create your handler classes.

For example:

```php
<?php

namespace App\AMQP\Handlers;

class FooHandler
{
    public function handle()
    {
        // do something...
    }
}
```

Then register your handler in `routes/amqp-handlers.php` file.

```php
<?php

use \App\AMQP\Handlers\FooHandler;
use \Usmonaliyev\SimpleRabbit\Facades\ActionMQ;

ActionMQ::register('create-foo', [FooHandler::class, 'handle']);
```

To consume messages use:

```shell
php artisan amqp:consume
```

## Contacts

We have telegram group for discussion:

[You can join us with this link](https://t.me/+P7PlyAdDQAJjM2Fi)

## Plans

- [x] Setup consumer mode as `routes/actions.php`
- [ ] Adding `ampq:define-queues --exchange` command.
- [ ] Adding `ampq:make-action {action} {function}` command.
- [x] Adding `ampq:listen {connection=''} {queue=''}` command.
- [ ] Writing `README.UZ.md` and `README.RU.md` files.
- [ ] Setup testing.
- [x] Creating telegram group for discussion

## Testing

```bash
composer test
```

## License

The [MIT](LICENSE.md) License.