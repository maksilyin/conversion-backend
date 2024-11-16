<?php

use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\CheckFileType;
use App\Http\Middleware\CheckTask;
use Illuminate\Support\Facades\Route;

Route::prefix('file')->group(function () {
    Route::post('/upload/', [FileUploadController::class, 'uploadChunk'])->middleware(CheckFileType::class);
    Route::delete('/delete/', [FileUploadController::class, 'deleteFile']);
})->middleware(CheckTask::class);

Route::prefix('task')->group(function () {
    Route::get('/{task_id}', [TaskController::class, 'get']);
    Route::put('/', [TaskController::class, 'start']);
    Route::post('/create/', [TaskController::class, 'create']);
});
