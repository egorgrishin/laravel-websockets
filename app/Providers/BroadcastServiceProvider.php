<?php

namespace App\Providers;

use App\Broadcasters\PusherBroadcaster;
use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Container\Container;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;
use Psr\Log\LoggerInterface;
use Pusher\Pusher;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes();
        $this->registerCustomPusherDriver();

        require base_path('routes/channels.php');
    }

    /**
     * Регистрация собственной реализации драйвера Pusher
     */
    private function registerCustomPusherDriver(): void
    {
        Broadcast::extend('pusher', function (Container $app, array $config) {
            $pusher = new Pusher(
                $config['key'],
                $config['secret'],
                $config['app_id'],
                $config['options'] ?? [],
                !empty($config['client_options'])
                    ? new GuzzleClient($config['client_options'])
                    : null,
            );

            if ($config['log'] ?? false) {
                $pusher->setLogger($this->app->make(LoggerInterface::class));
            }

            return new PusherBroadcaster($pusher);
        });
    }
}
