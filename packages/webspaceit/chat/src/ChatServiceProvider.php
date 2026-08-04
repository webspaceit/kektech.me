<?php

namespace WebspaceIt\Chat;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class ChatServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/chat.php', 'chat');
    }

    public function boot(): void
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/chat.php' => config_path('chat.php'),
        ], 'chat-config');

        // Migrations
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'chat-migrations');

        // Views (Blade templates for admin)
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'chat');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/chat'),
        ], 'chat-views');

        // React components
        $this->publishes([
            __DIR__ . '/../resources/js/components' => resource_path('js/components'),
            __DIR__ . '/../resources/js/pages' => resource_path('js/pages'),
        ], 'chat-react');

        // Routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/chat.php');

        // Artisan commands
        if ($this->app->runningInConsole()) {
            $this->commands([
                \WebspaceIt\Chat\Console\InstallChatCommand::class,
            ]);
        }
    }
}
