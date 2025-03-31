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
            $this->metaTagsCache = $this->seo()->where('locale', $locale)->select('meta_title', 'meta_description', 'meta_keywords')->first();
        }

        return [
            'title' => $this->metaTagsCache ? $this->metaTagsCache->meta_title : null,
            'description' => $this->metaTagsCache ? $this->metaTagsCache->meta_description : null,
            'keywords' => $this->metaTagsCache ? $this->metaTagsCache->meta_keywords : null,
        ];
    }

    protected static function bootHasSEO(): void
    {
        static::deleting(function ($model) {
            $model->seo()->delete();
        });
    }
}
