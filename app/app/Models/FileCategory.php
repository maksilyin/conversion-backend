<?php

namespace App\Models;

use App\Traits\HasSEO;
use App\Traits\HasTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileCategory extends Model
{
    use HasFactory;
    use HasTranslatable;
    use HasSEO;

    protected $fillable = ['name', 'sort', 'slug', 'description', 'icon_image', 'icon'];
    public function formats()
    {
        return $this->hasMany(FileFormat::class, 'category_id');
    }
}
