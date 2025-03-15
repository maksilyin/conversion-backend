<?php

use App\Http\Controllers\FileFormatsController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TextBlockController;
use App\Http\Middleware\CheckFile;
use App\Http\Middleware\CheckFileType;
use App\Http\Middleware\CheckTask;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ValidateTaskSize;
use Illuminate\Support\Facades\Route;

Route::prefix('file')->group(function () {
    Route::post('/', [FileUploadController::class, 'create']);
    Route::post('/upload/', [FileUploadController::class, 'uploadChunk'])
        ->middleware([CheckTask::class, CheckFile::class, CheckFileType::class, ValidateTaskSize::class]);
    Route::delete('/delete/{task}/{hash}/', [FileUploadController::class, 'deleteFile'])
        ->middleware(CheckTask::class)
        ->name('file.delete');
    Route::get('/download/{task}/all/', [FileUploadController::class, 'downloadZip'])
        ->middleware(CheckTask::class);
    Route::get('/download/{task}/{hash}/', [FileUploadController::class, 'download'])
        ->middleware(CheckTask::class);
    Route::get('/img/{task}/{filename}', [FileUploadController::class, 'showImg'])
        ->middleware(CheckTask::class);
});

Route::prefix('task')->group(function () {
    Route::get('/{task}', [TaskController::class, 'get']);
    Route::put('/', [TaskController::class, 'start']);
    Route::post('/create/', [TaskController::class, 'create']);
    Route::delete('/{task}/', [TaskController::class, 'clear']);
});

Route::get('/formats/', [FileFormatsController::class, 'formats'])->middleware(SetLocale::class);
Route::get('/formats/{format}/', [FileFormatsController::class, 'show'])->middleware(SetLocale::class);
Route::get('/formats/type/{type}/', [FileFormatsController::class, 'fileType'])->middleware(SetLocale::class);

Route::get('/page/', [PageController::class, 'index'])->middleware(SetLocale::class);
Route::get('/text/{key}/', [TextBlockController::class, 'show'])->middleware(SetLocale::class);

Route::get('/sitemap/', [SitemapController::class, 'index']);
Route::get('/lang/', [LanguageController::class, 'index']);
