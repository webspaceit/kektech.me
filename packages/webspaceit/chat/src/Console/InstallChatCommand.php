<?php

namespace WebspaceIt\Chat\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallChatCommand extends Command
{
    protected $signature = 'chat:install';
    protected $description = 'Install the live chat module (migrations, config, assets)';

    public function handle()
    {
        $this->info('Installing Live Chat Module...');

        // 1. Publish config
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-config',
        ]);
        $this->info('Config published.');

        // 2. Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-migrations',
        ]);
        $this->info('Migrations published.');

        // 3. Run migrations
        $this->call('migrate');
        $this->info('Migrations run.');

        // 4. Publish React components
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-react',
        ]);
        $this->info('React components published.');

        // 5. Publish Blade views
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-views',
        ]);
        $this->info('Blade views published.');

        $this->info('');
        $this->info('Live Chat Module installed successfully!');
        $this->info('');
        $this->info('Next steps:');
        $this->info('1. Add ChatWidget to your layout:');
        $this->info('   import ChatWidget from "../components/ChatWidget";');
        $this->info('   <ChatWidget accentColor="emerald" title="Live Chat" />');
        $this->info('');
        $this->info('2. Add the admin route to your admin panel:');
        $this->info('   <a href="/wsdashboard/chat">Live Chat</a>');
        $this->info('');
        $this->info('3. Import Chat page in your Inertia routes:');
        $this->info('   use Chat from "../pages/admin/Chat";');
    }
}
