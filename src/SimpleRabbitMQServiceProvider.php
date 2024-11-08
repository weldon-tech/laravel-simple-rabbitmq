<?php

namespace Usmonaliyev\SimpleRabbit;

use Illuminate\Support\ServiceProvider;
use Usmonaliyev\SimpleRabbit\Console\Commands\DefineQueueCommand;
use Usmonaliyev\SimpleRabbit\MQ\ConnectionManager;

class SimpleRabbitMQServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config/simple-mq.php' => config_path('simple-mq.php')], 'simple-mq');

            $this->publishes([__DIR__.'/../routes/actions.php' => base_path('routes/actions.php')]);

            $this->commands([
                DefineQueueCommand::class,
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register(): void
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/simple-mq.php', 'simple-mq');

        $this->app->singleton('simple-rabbitmq', fn () => new SimpleMQ);

        $this->app->singleton(ActionMQ::class, fn () => new ActionMQ);
        $this->app->singleton(ConnectionManager::class, fn () => new ConnectionManager);
    }
}
