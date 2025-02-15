<?php

namespace App\Models;

use App\Traits\HasSEO;
use App\Traits\HasTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory;
    use HasTranslatable;
    use HasSEO;

    protected $fillable = ['name', 'sort', 'url', 'description', 'image', 'active'];
}
