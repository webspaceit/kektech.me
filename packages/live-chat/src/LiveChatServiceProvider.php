<?php

namespace App\LiveChat;

use Illuminate\Support\ServiceProvider;

class LiveChatServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPublishables();
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/guest.php');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/live-chat.php', 'live-chat');
    }

    protected function registerPublishables(): void
    {
        if (! $this->app->runningInConsole()) {
            return;
        }

        $this->publishes([
            __DIR__ . '/../config/live-chat.php' => config_path('live-chat.php'),
        ], 'live-chat-config');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'live-chat-migrations');

        $this->publishes([
            __DIR__ . '/../resources/js' => resource_path('js/vendor/live-chat'),
        ], 'live-chat-frontend');
    }
}
