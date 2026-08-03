<?php

namespace App\Providers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Schema::defaultStringLength(191);

        Gate::define('admin', fn (User $user) => $user->is_admin);

        Inertia::share([
            'logo' => fn () => Setting::get()->logo ?? null,
            'appName' => fn () => config('app.name'),
            'unreadChatCount' => fn () => $userId = auth()->id() ? DB::table('chat_messages')
                ->join('chat_rooms', 'chat_messages.room_id', '=', 'chat_rooms.id')
                ->leftJoin('chat_participants', 'chat_rooms.id', '=', 'chat_participants.room_id')
                ->where('chat_messages.user_id', '!=', auth()->id())
                ->whereNull('chat_messages.read_at')
                ->where(function ($q) {
                    $q->where('chat_rooms.type', 'guest')
                      ->orWhere('chat_participants.user_id', auth()->id());
                })
                ->count() : 0,
        ]);
    }
}
