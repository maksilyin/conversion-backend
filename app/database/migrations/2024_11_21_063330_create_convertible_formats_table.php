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
        Schema::create('convertible_formats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('source_format_id')->constrained('file_formats')->onDelete('cascade'); // Исходный формат
            $table->foreignId('target_format_id')->constrained('file_formats')->onDelete('cascade'); // Целевой формат
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convertible_formats');
    }
};
