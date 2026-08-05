<?php

namespace App\LiveChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'message',
        'is_guest',
        'guest_session_id',
        'read_at',
    ];

    protected $casts = [
        'is_guest' => 'boolean',
        'read_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('live-chat.user_model', 'App\\Models\\User'));
    }
}
