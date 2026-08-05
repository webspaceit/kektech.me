<?php

namespace App\LiveChat\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ChatRoom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'guest_name',
        'guest_email',
        'guest_session_id',
        'type',
        'last_message',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'room_id');
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(
            config('live-chat.user_model', 'App\\Models\\User'),
            'chat_participants',
            'room_id',
            'user_id'
        )->withPivot('last_read_at', 'last_typing_at')->withTimestamps();
    }

    public function chatParticipants(): HasMany
    {
        return $this->hasMany(ChatParticipant::class, 'room_id');
    }
}
