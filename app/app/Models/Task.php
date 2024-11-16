<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'user_id', 'type', 'status', 'payload'];

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
}
