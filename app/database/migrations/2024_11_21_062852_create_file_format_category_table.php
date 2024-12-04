<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('file_format_category', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_format_id')->constrained('file_formats')->onDelete('cascade');
            $table->foreignId('file_category_id')->constrained('file_categories')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_format_category');
    }
};
