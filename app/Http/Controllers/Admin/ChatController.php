<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $rooms = DB::table('chat_rooms')
            ->leftJoin('chat_participants as cp1', 'chat_rooms.id', '=', 'cp1.room_id')
            ->leftJoin('chat_participants as cp2', function ($q) use ($userId) {
                $q->on('chat_rooms.id', '=', 'cp2.room_id')
                  ->where('cp2.user_id', '!=', $userId);
            })
            ->leftJoin('users', 'cp2.user_id', '=', 'users.id')
            ->where(function ($q) use ($userId) {
                $q->where('cp1.user_id', $userId)
                  ->orWhere('chat_rooms.type', 'guest');
            })
            ->select(
                'chat_rooms.id',
                'chat_rooms.name',
                'chat_rooms.type',
                'chat_rooms.guest_name',
                'chat_rooms.guest_email',
                'chat_rooms.last_message',
                'chat_rooms.last_message_at',
                'users.name as other_user_name'
            )
            ->orderBy('chat_rooms.last_message_at', 'desc')
            ->get()
            ->map(function ($room) use ($userId) {
                $unreadCount = DB::table('chat_messages')
                    ->where('room_id', $room->id)
                    ->where(function ($q) use ($userId) {
                        $q->whereNull('user_id')
                          ->orWhere('user_id', '!=', $userId);
                    })
                    ->whereNull('read_at')
                    ->count();

                return [
                    'id' => $room->id,
                    'name' => $room->name ?? $room->other_user_name ?? $room->guest_name ?? 'Unknown',
                    'type' => $room->type,
                    'guest_name' => $room->guest_name,
                    'guest_email' => $room->guest_email,
                    'last_message' => $room->last_message,
                    'last_message_at' => $room->last_message_at,
                    'unread_count' => $unreadCount,
                ];
            });

        return Inertia::render('admin/Chat', [
            'rooms' => $rooms,
            'auth' => [
                'user' => [
                    'id' => auth()->id(),
                    'name' => auth()->user()->name,
                ],
            ],
        ]);
    }

    public function messages(Request $request, int $id)
    {
        $userId = auth()->id();

        DB::table('chat_messages')
            ->where('room_id', $id)
            ->where(function ($q) use ($userId) {
                $q->whereNull('user_id')
                  ->orWhere('user_id', '!=', $userId);
            })
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $messages = DB::table('chat_messages')
            ->where('room_id', $id)
            ->leftJoin('users', 'chat_messages.user_id', '=', 'users.id')
            ->orderBy('chat_messages.created_at', 'asc')
            ->select(
                'chat_messages.id',
                'chat_messages.message',
                'chat_messages.created_at',
                'chat_messages.is_guest',
                'chat_messages.user_id',
                'users.name as user_name'
            )
            ->get()
            ->map(fn ($msg) => [
                'id' => $msg->id,
                'message' => $msg->message,
                'created_at' => $msg->created_at,
                'is_guest' => $msg->is_guest,
                'user' => [
                    'id' => $msg->user_id ?? 0,
                    'name' => $msg->user_name ?? 'Guest',
                ],
            ]);

        return response()->json($messages);
    }

    public function send(Request $request, int $id)
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        DB::table('chat_messages')->insert([
            'room_id' => $id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('chat_rooms')->where('id', $id)->update([
            'last_message' => $validated['message'],
            'last_message_at' => now(),
        ]);

        return response()->json([
            'id' => DB::getPdo()->lastInsertId(),
            'message' => $validated['message'],
            'created_at' => now()->toISOString(),
            'user' => [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
            ],
        ]);
    }
}
