<?php

namespace App\Support\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class SupportTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number',
        'parcel_id',
        'user_id',
        'assigned_to',
        'subject',
        'description',
        'priority',
        'status',
        'channel',
        'category',
        'resolved_at',
        'closed_at',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public static function generateTicketNumber(): string
    {
        return 'TKT-' . strtoupper(Str::random(8));
    }

    public function parcel(): BelongsTo
    {
        return $this->belongsTo(config('support.parcel_model', 'App\\Models\\Parcel'));
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(config('support.user_model', 'App\\Models\\User'));
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(config('support.user_model', 'App\\Models\\User'), 'assigned_to');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(SupportMessage::class, 'ticket_id')->orderBy('created_at', 'asc');
    }

    public function reads(): HasMany
    {
        return $this->hasMany(SupportTicketRead::class, 'ticket_id');
    }
}
