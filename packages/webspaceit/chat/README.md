# Laravel Live Chat

A complete live chat module for Laravel + Inertia.js + React projects.

## Features

- Guest chat widget (visitors can start conversations)
- Admin chat dashboard with real-time updates
- Desktop notifications + sound alerts
- Auto-scroll with smart scroll detection
- Unread message badges
- Session persistence across page refreshes
- Customizable accent colors

## Requirements

- Laravel 10/11/12
- Inertia.js + React
- Auth system (for admin)

## Installation

```bash
# 1. Install via composer (local path)
composer require webspaceit/laravel-live-chat

# 2. Run the install command
php artisan chat:install
```

That's it! The command will:
- Publish config file
- Run database migrations
- Publish React components
- Publish Blade views

## Manual Installation

If you prefer manual setup:

```bash
# 1. Copy the package to your packages directory
cp -r packages/webspaceit/chat your-project/packages/

# 2. Add to composer.json
"repositories": [
    {
        "type": "path",
        "url": "packages/webspaceit/chat"
    }
],
"require": {
    "webspaceit/laravel-live-chat": "*"
}

# 3. Publish assets
php artisan vendor:publish --provider="WebspaceIt\Chat\ChatServiceProvider" --tag=chat-config
php artisan vendor:publish --provider="WebspaceIt\Chat\ChatServiceProvider" --tag=chat-migrations
php artisan vendor:publish --provider="WebspaceIt\Chat\ChatServiceProvider" --tag=chat-react
php artisan vendor:publish --provider="WebspaceIt\Chat\ChatServiceProvider" --tag=chat-views

# 4. Run migrations
php artisan migrate
```

## Usage

### Add Chat Widget to Frontend

In your layout or page component:

```jsx
import ChatWidget from '../components/ChatWidget';

export default function Layout({ children }) {
    return (
        <div>
            {children}
            <ChatWidget accentColor="emerald" title="Live Chat" />
        </div>
    );
}
```

### Accent Colors

Available colors: `emerald`, `blue`, `purple`, `red`, `orange`

### Add Admin Chat Page

In your admin routes:

```php
use WebspaceIt\Chat\Controllers\ChatController;

Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
Route::get('chat/rooms', [ChatController::class, 'rooms'])->name('chat.rooms');
Route::get('chat/{id}/messages', [ChatController::class, 'messages'])->name('chat.messages');
Route::post('chat/{id}/send', [ChatController::class, 'send'])->name('chat.send');
```

### Configuration

Edit `config/chat.php`:

```php
return [
    'guest_chat' => true,
    'poll_interval' => 1000,
    'max_message_length' => 5000,
    'notifications' => [
        'desktop' => true,
        'sound' => true,
    ],
    'admin_prefix' => 'wsdashboard',
];
```

## Database Tables

The module creates 3 tables:

- `chat_rooms` - Chat rooms (guest or user-to-user)
- `chat_messages` - Messages in rooms
- `chat_participants` - Room participants (for user-to-user chats)

## License

MIT
