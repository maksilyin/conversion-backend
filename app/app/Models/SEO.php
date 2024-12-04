<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SEO extends Model
{
    use HasFactory;

    protected $table = 'seo';
    protected $fillable = [
        'meta_title',
        'meta_description',
        'meta_keywords',
        'locale'
    ];

    public function seoable()
    {
        return $this->morphTo();
    }

    public static function forLocale($seoableType, $seoableId, $locale = null)
    {
        $locale = $locale ?: app()->getLocale();
        return self::where('seoable_type', $seoableType)
            ->where('seoable_id', $seoableId)
            ->where('locale', $locale)
            ->first();
    }
}
