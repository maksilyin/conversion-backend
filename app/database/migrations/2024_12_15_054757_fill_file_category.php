<?php

use App\Models\FileCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $categories = [
        [
            'name' => 'Image',
            'slug' => 'image',
        ],
        [
            'name' => 'Document',
            'slug' => 'document',
        ],
        [
            'name' => 'Audio',
            'slug' => 'audio',
        ],
        [
            'name' => 'Video',
            'slug' => 'video',
        ],
        [
            'name' => 'E-book',
            'slug' => 'ebook',
        ],
        [
            'name' => 'Archive',
            'slug' => 'archive',
        ],
        [
            'name' => 'Font',
            'slug' => 'font',
        ],
        [
            'name' => 'Spreadsheet',
            'slug' => 'spreadsheet',
        ],
        [
            'name' => 'Presentation',
            'slug' => 'presentation',
        ],
        [
            'name' => 'Vector',
            'slug' => 'vector',
        ],
    ];
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        foreach ($this->categories as $category) {
            FileCategory::firstOrCreate(
                ['slug' => $category['slug']],
                ['name' => $category['name']]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach ($this->categories as $category) {
            FileCategory::where('slug', $category['slug'])->delete();
        }
    }
};
