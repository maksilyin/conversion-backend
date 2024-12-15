<?php

namespace App\Models;

use App\Traits\HasSEO;
use App\Traits\HasTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FileFormat extends Model
{
    use HasFactory;
    use HasTranslatable;
    use HasSEO;

    protected $fillable = ['name', 'extended_name', 'sort', 'category_id', 'extension', 'mime_type', 'excerpt', 'description', 'icon_image', 'icon', 'active', 'color'];

    public function convertible(): BelongsToMany
    {
        return $this->belongsToMany(
            FileFormat::class,
            'convertible_formats',
            'source_format_id',
            'target_format_id'
        );
    }

    public function convertibleFrom(): BelongsToMany
    {
        return $this->belongsToMany(
            FileFormat::class,
            'convertible_formats',
            'target_format_id',
            'source_format_id'
        );
    }

    public function convertibleCategory(): BelongsToMany
    {
        return $this->belongsToMany(
            FileCategory::class,
            'file_format_category',
            'file_format_id',
            'file_category_id'
        );
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FileCategory::class, 'category_id');
    }
}
