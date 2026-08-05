<?php

namespace App\LiveChat\Http\Controllers;

use App\LiveChat\Models\ChatRoom;
use App\LiveChat\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class GuestChatController extends Controller
{
    public function start(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'session_id' => 'required|string|max:255',
        ]);

        $sessionId = $validated['session_id'];

        $existingRoom = ChatRoom::where('guest_session_id', $sessionId)->first();

        if ($existingRoom) {
            return response()->json([
                'id' => $existingRoom->id,
                'name' => 'Support Chat',
            ]);
        }

        return DB::transaction(function () use ($validated, $sessionId) {
            $room = ChatRoom::create([
                'name' => 'Guest: ' . $validated['name'],
                'type' => 'direct',
                'guest_name' => $validated['name'],
                'guest_email' => $validated['email'],
                'guest_session_id' => $sessionId,
            ]);

            $admin = User::where('is_admin', true)->first();

            if ($admin) {
                $room->participants()->attach($admin->id, ['last_read_at' => now()]);
            }

            ChatMessage::create([
                'room_id' => $room->id,
                'user_id' => null,
                'message' => "Guest {$validated['name']} ({$validated['email']}) has started a conversation.",
                'is_guest' => true,
                'guest_session_id' => $sessionId,
            ]);

            return response()->json([
                'id' => $room->id,
                'name' => 'Support Chat',
            ]);
        });
    }

    public function messages(Request $request, int $id): JsonResponse
    {
        $sessionId = $request->query('session_id');

        if (! $sessionId) {
            return response()->json(['error' => 'session_id required'], 422);
        }

        $hasAccess = ChatRoom::where('id', $id)
            ->where('guest_session_id', $sessionId)
            ->exists();

        if (! $hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
            'is_guest' => $msg->is_guest,
            'user' => [
                'id' => $msg->user?->id ?? 0,
                'name' => $msg->user?->name ?? 'Guest',
            ],
        ]);

        return response()->json($messages);
    }

    public function sendMessage(Request $request, int $id): JsonResponse
    {
        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'session_id' => 'required|string|max:255',
        ]);

        $hasAccess = ChatRoom::where('id', $id)
            ->where('guest_session_id', $validated['session_id'])
            ->exists();

        if (! $hasAccess) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $message = ChatMessage::create([
            'room_id' => $id,
            'user_id' => null,
            'message' => $validated['message'],
            'is_guest' => true,
            'guest_session_id' => $validated['session_id'],
        ]);

        ChatRoom::where('id', $id)->update([
            'last_message' => $validated['message'],
            'last_message_at' => now(),
        ]);

        return response()->json([
            'id' => $message->id,
            'room_id' => $message->room_id,
            'user_id' => $message->user_id,
            'message' => $message->message,
            'created_at' => $message->created_at->toISOString(),
            'is_guest' => true,
            'user' => [
                'id' => 0,
                'name' => 'Guest',
            ],
        ]);
    }
}
