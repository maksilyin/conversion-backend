<?php

namespace App\Models;

use App\Helpers\FileUploadHelper;
use App\Services\FileService;
use Illuminate\Broadcasting\Channel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\BroadcastsEvents;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Route;

class Task extends Model
{
    const STATUS_CREATED = 'created';
    const STATUS_PENDING = 'pending';
    const STATUS_COMPLETE = 'complete';
    const STATUS_LOCK = 'lock';
    const STATUS_CLEAR = 'clear';

    use BroadcastsEvents, HasFactory, SoftDeletes;

    protected $fillable = ['uuid', 'user_id', 'type', 'status', 'payload'];
    protected $hidden = ['created_at', 'updated_at', 'user_id', 'deleted_at'];

    protected $disableBroadcastOnRout = [
        'file.delete'
    ];

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

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
        $currentRoute = Route::currentRouteName();

        if ($this->status === self::STATUS_CREATED
            || $this->status === self::STATUS_CLEAR
            || in_array($currentRoute, $this->disableBroadcastOnRout)
        ) {
            return [];
        }
        return [
            new Channel('task.'.$this->uuid)
        ];
    }

    public function broadcastWith(string $event): array
    {
        return match ($event) {
            'updated' => [
                'uuid' => $this->uuid,
                'type' => $this->type,
                'status' => $this->status,
                'payload' => $this->getPayload(),
            ],
            default => [],
        };
    }

    public static function getForResult(string $uuid)
    {
        $task = self::where('uuid', $uuid)->firstOrFail();
        $task->payload = $task->getPayload();
        return $task;
    }

    public function getPayload()
    {
        return [
            ...$this->payload,
            'files' => $this->files()
                ->where(function ($query) {
                    $query->where('status', '>=', FileUploadHelper::FILE_STATUS_UPLOADED)
                          ->orWhere('status', '=', FileUploadHelper::FILE_STATUS_ERROR);
                })
                ->orderBy('index')
                ->get()
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::deleting(function ($task) {
            $fileService = new FileService($task->uuid);
            $fileService->deleteTaskFolder();

            if ($task->isForceDeleting()) {
                $task->files()->forceDelete();
            }
            else {
                $task->files()->where('status', FileUploadHelper::FILE_STATUS_CREATED)->forceDelete();
                $task->files()->delete();
            }
        });
    }

    public function files()
    {
        return $this->hasMany(File::class, 'task_id');
    }
}
