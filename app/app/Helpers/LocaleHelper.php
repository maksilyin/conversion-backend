<?php

namespace App\Helpers;

use App\Models\Language;
use Illuminate\Support\Facades\Cache;

class LocaleHelper
{
    public static function getActiveLocales()
    {
        return Cache::remember('active_languages', 60, function () {
            return Language::where('is_active', 1)->pluck('code')->toArray();
        });
    }
}
