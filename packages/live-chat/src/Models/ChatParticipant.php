<?php

namespace App\LiveChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatParticipant extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'last_typing_at',
        'last_read_at',
    ];

    protected $casts = [
        'last_typing_at' => 'datetime',
        'last_read_at' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(ChatRoom::class, 'room_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('live-chat.user_model', 'App\\Models\\User'));
    }

    public function getIsOnlineAttribute(): bool
    {
        return $this->last_read_at && $this->last_read_at->diffInSeconds(now()) < 30;
    }

    public function getIsTypingAttribute(): bool
    {
        return $this->last_typing_at && $this->last_typing_at->diffInSeconds(now()) < 5;
    }
}
