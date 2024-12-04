<?php
namespace App\Traits;

use App\Models\Translation;

trait HasTranslatable
{
    protected $translationCache = [];

    public function translations(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Translation::class, 'translatable');
    }

    public function translation($locale = null)
    {
        $locale = $locale ?: app()->getLocale();

        if (!isset($this->translationCache[$locale]) || !$this->translationCache[$locale]) {
            $this->translationCache[$locale] = $this->translations()->where('locale', $locale)->first();
        }

        return $this->translationCache[$locale];
    }

    public function getTranslate(string $field, string $locale = null, $defaultField = null)
    {
        if (!$defaultField) {
            $defaultField = $field;
        }

        $translation = $this->translation($locale);

        if ($translation && isset($translation->{$field})) {
            return $translation->{$field};
        }

        return $this->{$defaultField};
    }

    protected static function bootHasTranslatable(): void
    {
        static::deleting(function ($model) {
            $model->translations()->delete();
        });
    }
}
