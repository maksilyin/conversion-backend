<?php

use App\Http\Controllers\FileFormatsController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\CheckFileType;
use App\Http\Middleware\CheckTask;
use App\Http\Middleware\SetLocale;
use Illuminate\Support\Facades\Route;

Route::prefix('file')->group(function () {
    Route::post('/upload/', [FileUploadController::class, 'uploadChunk'])->middleware(CheckFileType::class);
    Route::delete('/delete/', [FileUploadController::class, 'deleteFile']);
    Route::get('/download/', [FileUploadController::class, 'download']);
    Route::get('/download/all/', [FileUploadController::class, 'downloadZip']);
    Route::get('/img/{task}/{filename}', [FileUploadController::class, 'showImg']);
})->middleware(CheckTask::class);

Route::prefix('task')->group(function () {
    Route::get('/{task_id}', [TaskController::class, 'get']);
    Route::put('/', [TaskController::class, 'start']);
    Route::post('/create/', [TaskController::class, 'create']);
});

Route::get('/formats/', [FileFormatsController::class, 'formats'])->middleware(SetLocale::class);
Route::get('/formats/{format}/', [FileFormatsController::class, 'show'])->middleware(SetLocale::class);
Route::get('/formats/type/{type}/', [FileFormatsController::class, 'fileType'])->middleware(SetLocale::class);
