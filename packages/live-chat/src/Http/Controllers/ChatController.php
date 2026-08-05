<?php

namespace App\LiveChat\Http\Controllers;

use App\LiveChat\Models\ChatRoom;
use App\LiveChat\Models\ChatMessage;
use App\LiveChat\Models\ChatParticipant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ChatController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $rooms = ChatRoom::whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->with([
                'participants' => fn ($q) => $q->where('chat_participants.user_id', '!=', $user->id),
                'participants:id,name,email',
            ])
            ->orderBy('last_message_at', 'desc')
            ->get()
            ->map(function (ChatRoom $room) use ($user) {
                $otherUser = $room->participants->first();
                $unreadCount = ChatMessage::where('room_id', $room->id)
                    ->where('user_id', '!=', $user->id)
                    ->whereNull('read_at')
                    ->count();

                return [
                    'id' => $room->id,
                    'name' => $room->name ?? ($otherUser?->name ?? 'Unknown'),
                    'type' => $room->type,
                    'last_message' => $room->last_message,
                    'last_message_at' => $room->last_message_at?->toISOString(),
                    'unread_count' => $unreadCount,
                    'other_user' => $otherUser ? [
                        'id' => $otherUser->id,
                        'name' => $otherUser->name,
                        'email' => $otherUser->email,
                    ] : null,
                ];
            });

        return response()->json($rooms);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $currentUser = $request->user();
        $otherUserId = $validated['user_id'];

        if ($otherUserId === $currentUser->id) {
            return response()->json(['error' => 'Cannot create a room with yourself'], 422);
        }

        $existingRoom = ChatRoom::where('type', 'direct')
            ->whereHas('participants', fn ($q) => $q->where('user_id', $currentUser->id))
            ->whereHas('participants', fn ($q) => $q->where('user_id', $otherUserId))
            ->whereRaw('(SELECT COUNT(DISTINCT user_id) FROM chat_participants WHERE room_id = chat_rooms.id) = 2')
            ->first();

        if ($existingRoom) {
            $otherUser = \App\Models\User::find($otherUserId);
            return response()->json([
                'id' => $existingRoom->id,
                'name' => $otherUser->name,
                'type' => $existingRoom->type,
                'last_message' => $existingRoom->last_message,
                'last_message_at' => $existingRoom->last_message_at?->toISOString(),
                'unread_count' => 0,
                'other_user' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'email' => $otherUser->email,
                ],
            ]);
        }

        return DB::transaction(function () use ($currentUser, $otherUserId) {
            $otherUser = \App\Models\User::find($otherUserId);

            $room = ChatRoom::create([
                'name' => null,
                'type' => 'direct',
            ]);

            $room->participants()->attach([
                $currentUser->id => ['last_read_at' => now()],
                $otherUserId => ['last_read_at' => now()],
            ]);

            return response()->json([
                'id' => $room->id,
                'name' => $otherUser->name,
                'type' => $room->type,
                'last_message' => null,
                'last_message_at' => null,
                'unread_count' => 0,
                'other_user' => [
                    'id' => $otherUser->id,
                    'name' => $otherUser->name,
                    'email' => $otherUser->email,
                ],
            ]);
        });
    }

    public function messages(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $room = ChatRoom::where('id', $id)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        $query = ChatMessage::where('room_id', $id)
            ->with('user:id,name')
            ->orderBy('created_at', 'asc');

        if ($request->has('after')) {
            $query->where('created_at', '>', $request->query('after'));
        }

        $messages = $query->get()->map(fn (ChatMessage $msg) => [
            'id' => $msg->id,
            'room_id' => $msg->room_id,
            'user_id' => $msg->user_id,
            'message' => $msg->message,
            'created_at' => $msg->created_at->toISOString(),
            'user' => [
                'id' => $msg->user->id,
                'name' => $msg->user->name,
            ],
        ]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        $room = ChatRoom::where('id', $id)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        $validated = $request->validate([
            'message' => 'required|string|max:' . config('live-chat.max_message_length', 5000),
        ]);

        $message = ChatMessage::create([
            'room_id' => $id,
            'user_id' => $user->id,
            'message' => $validated['message'],
        ]);

        $room->update([
            'last_message' => $validated['message'],
            'last_message_at' => now(),
        ]);

        ChatParticipant::where('room_id', $id)
            ->where('user_id', $user->id)
            ->update(['last_read_at' => now()]);

        $message->load('user:id,name');

        return response()->json([
            'id' => $message->id,
            'room_id' => $message->room_id,
            'user_id' => $message->user_id,
            'message' => $message->message,
            'created_at' => $message->created_at->toISOString(),
            'user' => [
                'id' => $message->user->id,
                'name' => $message->user->name,
            ],
        ]);
    }

    public function typing(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        ChatParticipant::where('room_id', $id)
            ->where('user_id', $user->id)
            ->update(['last_typing_at' => now()]);

        return response()->json(['ok' => true]);
    }

    public function participants(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        ChatRoom::where('id', $id)
            ->whereHas('participants', fn ($q) => $q->where('user_id', $user->id))
            ->firstOrFail();

        $participants = ChatParticipant::where('room_id', $id)
            ->with('user:id,name,email')
            ->get()
            ->map(fn (ChatParticipant $p) => [
                'id' => $p->user->id,
                'name' => $p->user->name,
                'email' => $p->user->email,
                'is_online' => $p->is_online,
                'is_typing' => $p->is_typing,
                'last_seen' => $p->last_read_at?->toISOString(),
            ]);

        return response()->json($participants);
    }

    public function read(Request $request, int $id): JsonResponse
    {
        $user = $request->user();

        ChatMessage::where('room_id', $id)
            ->where('user_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        ChatParticipant::where('room_id', $id)
            ->where('user_id', $user->id)
            ->update(['last_read_at' => now()]);

        return response()->json(['ok' => true]);
    }
}
