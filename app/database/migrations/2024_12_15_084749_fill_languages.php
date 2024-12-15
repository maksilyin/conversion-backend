<?php

use App\Models\Language;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $data = [
        ['name' => 'Bahasa Indonesia', 'code' => 'id'],
        ['name' => 'Čeština', 'code' => 'cs'],
        ['name' => 'Dansk', 'code' => 'da'],
        ['name' => 'Deutsch', 'code' => 'de'],
        ['name' => 'English', 'code' => 'en'],
        ['name' => 'Español', 'code' => 'es'],
        ['name' => 'Français', 'code' => 'fr'],
        ['name' => 'Italiano', 'code' => 'it'],
        ['name' => 'Magyar', 'code' => 'hu'],
        ['name' => 'Nederlands', 'code' => 'nl'],
        ['name' => 'Norsk', 'code' => 'no'],
        ['name' => 'Polski', 'code' => 'pl'],
        ['name' => 'Português', 'code' => 'pt'],
        ['name' => 'Русский', 'code' => 'ru'],
        ['name' => 'Suomi', 'code' => 'fi'],
        ['name' => 'Svenska', 'code' => 'sv'],
        ['name' => 'Tiếng Việt', 'code' => 'vi'],
        ['name' => 'Türkçe', 'code' => 'tr'],
        ['name' => 'العربية', 'code' => 'ar'],
        ['name' => 'ไทย', 'code' => 'th'],
        ['name' => '日本語', 'code' => 'ja'],
        ['name' => '简体中文', 'code' => 'zh'],
        ['name' => '繁體中文', 'code' => 'zt'],
        ['name' => '한국어', 'code' => 'ko'],
        ['name' => 'Українська', 'code' => 'uk'],
        ['name' => 'Ελληνικά', 'code' => 'el']
    ];
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->data as $langItem) {
            Language::firstOrCreate(
                ['code' => $langItem['code']],
                ['name' => $langItem['name']]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
