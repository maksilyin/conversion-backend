<?php

namespace App\Models;

use App\Traits\HasTranslatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TextBlock extends Model
{
    use HasFactory;
    use HasTranslatable;

    protected $fillable = ['name', 'key', 'description'];

    public function getRouteKeyName()
    {
        return 'key';
    }
}
