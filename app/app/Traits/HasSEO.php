<?php
namespace App\Traits;
use App\Models\SEO;

trait HasSEO
{
    protected $metaTagsCache = null;

    public function seo(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(SEO::class, 'seoable');
    }

    public function getMetaTagsAttribute()
    {
        if ($this->metaTagsCache === null) {
            $locale = app()->getLocale();
            $this->metaTagsCache = $this->seo()->where('locale', $locale)->first();
        }

        return $this->metaTagsCache;
    }

    protected static function bootHasSEO(): void
    {
        static::deleting(function ($model) {
            $model->seo()->delete();
        });
    }
}
