<?php

namespace WebspaceIt\Chat\Console;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class InstallChatCommand extends Command
{
    protected $signature = 'chat:install';
    protected $description = 'Install the live chat module (migrations, config, assets, routes)';

    public function handle()
    {
        $fs = new Filesystem;

        $this->info('Installing Live Chat Module...');
        $this->newLine();

        // 1. Publish config
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-config',
            '--force' => true,
        ]);

        // 2. Publish migrations
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-migrations',
            '--force' => true,
        ]);

        // 3. Run migrations
        $this->call('migrate');

        // 4. Publish React components
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-react',
            '--force' => true,
        ]);

        // 5. Publish Blade views
        $this->call('vendor:publish', [
            '--provider' => 'WebspaceIt\Chat\ChatServiceProvider',
            '--tag' => 'chat-views',
            '--force' => true,
        ]);

        // 6. Auto-add routes to web.php
        $this->addRoutes();

        // 7. Clear caches
        $this->call('route:clear');
        $this->call('config:clear');

        $this->newLine();
        $this->info('Live Chat Module installed successfully!');
        $this->newLine();
        $this->info('Next steps:');
        $this->line('  1. Add to any React page/layout:');
        $this->line('     import ChatWidget from "../components/ChatWidget";');
        $this->line('     <ChatWidget accentColor="emerald" title="Live Chat" />');
        $this->newLine();
        $this->line('  2. Add to your admin sidebar (Blade):');
        $this->line('     <a href="/wsdashboard/chat">Live Chat</a>');
        $this->newLine();
        $this->line('  3. Add to your Inertia admin routes:');
        $this->line('     Route::get("chat", [ChatController::class, "index"])->name("chat.index");');
    }

    private function addRoutes()
    {
        $webPath = base_path('routes/web.php');
        $apiPath = base_path('routes/api.php');

        // Add to web.php
        if ($fs->exists($webPath)) {
            $web = $fs->get($webPath);

            if (!str_contains($web, 'chat.index')) {
                $chatRoutes = <<<'ROUTES'

// Live Chat - Admin Routes
Route::middleware(['web', 'auth'])->prefix('wsdashboard')->group(function () {
    Route::get('chat', [\WebspaceIt\Chat\Controllers\ChatController::class, 'index'])->name('chat.index');
    Route::get('chat/rooms', [\WebspaceIt\Chat\Controllers\ChatController::class, 'rooms'])->name('chat.rooms');
    Route::get('chat/{id}/messages', [\WebspaceIt\Chat\Controllers\ChatController::class, 'messages'])->name('chat.messages');
    Route::post('chat/{id}/send', [\WebspaceIt\Chat\Controllers\ChatController::class, 'send'])->name('chat.send');
    Route::get('unread-chat-count', function () {
        $userId = auth()->id();
        $count = \Illuminate\Support\Facades\DB::table('chat_messages')
            ->join('chat_rooms', 'chat_messages.room_id', '=', 'chat_rooms.id')
            ->leftJoin('chat_participants', 'chat_rooms.id', '=', 'chat_participants.room_id')
            ->where(function ($q) use ($userId) {
                $q->whereNull('chat_messages.user_id')
                  ->orWhere('chat_messages.user_id', '!=', $userId);
            })
            ->whereNull('chat_messages.read_at')
            ->where(function ($q) use ($userId) {
                $q->where('chat_rooms.type', 'guest')
                  ->orWhere('chat_participants.user_id', $userId);
            })
            ->count();
        return response()->json(['count' => $count]);
    })->name('chat.unread-count');
});
ROUTES;
                $fs->put($webPath, $web . $chatRoutes);
                $this->info('Admin routes added to routes/web.php');
            } else {
                $this->warn('Admin routes already exist in routes/web.php');
            }
        }

        // Add to api.php
        if ($fs->exists($apiPath)) {
            $api = $fs->get($apiPath);

            if (!str_contains($api, 'chat/guest/start')) {
                $guestRoutes = <<<'ROUTES'

// Live Chat - Guest API Routes (no auth required)
Route::post('/api/chat/guest/start', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'session_id' => 'required|string|max:255',
    ]);

    $existingRoom = DB::table('chat_rooms')
        ->where('type', 'guest')
        ->where('guest_session_id', $validated['session_id'])
        ->first();

    if ($existingRoom) {
        return response()->json(['room_id' => $existingRoom->id]);
    }

    $roomId = DB::table('chat_rooms')->insertGetId([
        'type' => 'guest',
        'guest_name' => $validated['name'],
        'guest_email' => $validated['email'],
        'guest_session_id' => $validated['session_id'],
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return response()->json(['room_id' => $roomId]);
});

Route::get('/api/chat/guest/{id}/messages', function (int $id, Request $request) {
    $sessionId = $request->query('session_id');
    $room = DB::table('chat_rooms')->where('id', $id)->first();

    if (!$room || $room->guest_session_id !== $sessionId) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $messages = DB::table('chat_messages')
        ->where('room_id', $id)
        ->leftJoin('users', 'chat_messages.user_id', '=', 'users.id')
        ->orderBy('chat_messages.created_at', 'asc')
        ->select('chat_messages.id', 'chat_messages.message', 'chat_messages.created_at', 'chat_messages.is_guest', 'users.name as user_name')
        ->get()
        ->map(fn ($msg) => [
            'id' => $msg->id,
            'message' => $msg->message,
            'created_at' => $msg->created_at,
            'is_guest' => $msg->is_guest,
            'user' => ['name' => $msg->user_name ?? 'Guest'],
        ]);

    return response()->json($messages);
});

Route::post('/api/chat/guest/{id}/messages', function (int $id, Request $request) {
    $validated = $request->validate([
        'message' => 'required|string|max:5000',
        'session_id' => 'required|string|max:255',
    ]);

    $room = DB::table('chat_rooms')->where('id', $id)->first();
    if (!$room || $room->guest_session_id !== $validated['session_id']) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $msgId = DB::table('chat_messages')->insertGetId([
        'room_id' => $id,
        'user_id' => null,
        'message' => $validated['message'],
        'is_guest' => true,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    DB::table('chat_rooms')->where('id', $id)->update([
        'last_message' => $validated['message'],
        'last_message_at' => now(),
    ]);

    return response()->json([
        'id' => $msgId,
        'message' => $validated['message'],
        'created_at' => now()->toISOString(),
        'is_guest' => true,
    ]);
});
ROUTES;
                $fs->put($apiPath, $api . $guestRoutes);
                $this->info('Guest API routes added to routes/api.php');
            } else {
                $this->warn('Guest API routes already exist in routes/api.php');
            }
        }
    }
}
