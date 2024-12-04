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
        Schema::create('file_formats', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('extended_name')->nullable();
            $table->integer('sort')->default(500);
            $table->string('extension')->unique();
            $table->string('mime_type');
            $table->foreignId('category_id')->constrained('file_categories')->onDelete('cascade');
            $table->text('excerpt')->nullable();
            $table->text('description')->nullable();
            $table->string('icon_image')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_formats');
    }
};
