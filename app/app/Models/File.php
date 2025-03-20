<?php

namespace App\Models;

use App\Helpers\FileUploadHelper;
use App\Services\FileService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class File extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'files';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $appends = ['hash'];
    protected $hidden = ['id', 'task_id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'id', 'task_id', 'status', 'filename', 'size', 'extension', 'mimetype', 'params', 'result'
    ];

    //protected $touches = ['task'];

    protected $casts = [
        'params' => 'array',
        'result' => 'array',
    ];

    public function getHashAttribute()
    {
        return $this->id;
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });

        static::saved(function ($file) {
            if ($file->status === FileUploadHelper::FILE_STATUS_PROCESSING || $file->status === FileUploadHelper::FILE_STATUS_COMPLETED || $file->status === FileUploadHelper::FILE_STATUS_ERROR) {
                $file->broadcastTaskUpdate();
            }
        });

        static::deleting(function ($file) {
            if ($file->task_id) {
                $fileService = new FileService($file->task->uuid);
                $fileService->deleteFileByHash($file->id);
            }
        });
    }

    protected function broadcastTaskUpdate(): void
    {
        if ($this->task_id) {
            event(new \App\Events\TaskUpdated($this->task));
        }
    }

    public static function isExists(string $id)
    {
        return File::where('id', $id)->exists();
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
