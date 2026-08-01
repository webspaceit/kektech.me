<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\LiveChat\Models\ChatRoom;
use App\LiveChat\Models\ChatMessage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ChatController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        $rooms = ChatRoom::whereHas('participants', fn ($q) => $q->where('user_id', $userId))
            ->with(['participants' => fn ($q) => $q->where('chat_participants.user_id', '!=', $userId)])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function ($room) use ($userId) {
                $otherUser = $room->participants->first();
                $unreadCount = ChatMessage::where('room_id', $room->id)
                    ->where('user_id', '!=', $userId)
                    ->whereNull('read_at')
                    ->count();

                return [
                    'id' => $room->id,
                    'name' => $room->name ?? ($otherUser?->name ?? $room->guest_name ?? 'Unknown'),
                    'type' => $room->type,
                    'guest_name' => $room->guest_name,
                    'guest_email' => $room->guest_email,
                    'last_message' => $room->last_message,
                    'last_message_at' => $room->last_message_at?->toISOString(),
                    'unread_count' => $unreadCount,
                ];
            });

        return Inertia::render('admin/Chat', [
            'rooms' => $rooms,
        ]);
    }

    public function messages(Request $request, int $id)
    {
        $room = ChatRoom::where('id', $id)->firstOrFail();

        $messages = ChatMessage::where('room_id', $id)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(fn ($msg) => [
                'id' => $msg->id,
                'message' => $msg->message,
                'created_at' => $msg->created_at->toISOString(),
                'is_guest' => $msg->is_guest,
                'user' => [
                    'id' => $msg->user?->id ?? 0,
                    'name' => $msg->user?->name ?? 'Guest',
                ],
            ]);

        return response()->json($messages);
    }

    public function send(Request $request, int $id)
    {
        $room = ChatRoom::where('id', $id)->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
        ]);

        $message = ChatMessage::create([
            'room_id' => $id,
            'user_id' => auth()->id(),
            'message' => $validated['message'],
        ]);

        $room->update([
            'last_message' => $validated['message'],
            'last_message_at' => now(),
        ]);

        return response()->json([
            'id' => $message->id,
            'message' => $message->message,
            'created_at' => $message->created_at->toISOString(),
            'user' => [
                'id' => auth()->id(),
                'name' => auth()->user()->name,
            ],
        ]);
    }
}
