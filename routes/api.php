<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

Route::post('/chat/guest/start', function (Request $request) {
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

Route::get('/chat/guest/{id}/messages', function (int $id, Request $request) {
    $sessionId = $request->query('session_id');

    $room = DB::table('chat_rooms')->where('id', $id)->first();
    if (!$room || $room->guest_session_id !== $sessionId) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $messages = DB::table('chat_messages')
        ->where('room_id', $id)
        ->leftJoin('users', 'chat_messages.user_id', '=', 'users.id')
        ->orderBy('chat_messages.created_at', 'asc')
        ->select(
            'chat_messages.id',
            'chat_messages.message',
            'chat_messages.created_at',
            'chat_messages.is_guest',
            'users.name as user_name'
        )
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

Route::post('/chat/guest/{id}/messages', function (int $id, Request $request) {
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


