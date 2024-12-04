<?php

namespace App\Models;

use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\BroadcastsEvents;

class Task extends Model
{
    use BroadcastsEvents, HasFactory;

    protected $fillable = ['uuid', 'user_id', 'type', 'status', 'payload'];
    protected $hidden = ['created_at', 'updated_at', 'user_id'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public static function getByUuid(string $uuid)
    {
        return self::where('uuid', $uuid)->first();
    }

    public static function isExists(string $uuid)
    {
        return Task::where('uuid', $uuid)->exists();
    }

    public function broadcastOn(string $event): array
    {
        return [
            new Channel('task.'.$this->uuid)
        ];
    }

    public function broadcastWith(string $event): array
    {
        return match ($event) {
            'updated' => [
                'id' => $this->id,
                'uuid' => $this->uuid,
                'type' => $this->type,
                'status' => $this->status,
                'payload' => $this->payload,
            ],
            default => [],
        };
    }
}
