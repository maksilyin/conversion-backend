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
        Schema::create('files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade');
            $table->integer('status')->default(0);
            $table->string('filename');
            $table->integer('size')->nullable();
            $table->string('extension')->nullable();
            $table->string('mimetype')->nullable();
            $table->json('params')->nullable();
            $table->json('result')->nullable();
            $table->integer('index')->default(0);
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
