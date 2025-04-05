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
use App\Http\Middleware\TaskAccess;
use App\Http\Middleware\TaskAccessIP;
use App\Http\Middleware\ValidateTaskSize;
use Illuminate\Support\Facades\Route;

Route::prefix('file')
    ->middleware([CheckTask::class, TaskAccess::class])
    ->group(function () {
        Route::post('/', [FileUploadController::class, 'create']);

        Route::post('/upload/', [FileUploadController::class, 'uploadChunk'])
            ->middleware([CheckFile::class, CheckFileType::class, ValidateTaskSize::class]);

        Route::delete('/delete/{task}/{hash}/', [FileUploadController::class, 'deleteFile'])
            ->name('file.delete');

        Route::get('/download/{task}/', [FileUploadController::class, 'downloadZip']);
        Route::get('/download/{task}/{hash}/', [FileUploadController::class, 'download']);
        Route::get('/img/{task}/{filename}/', [FileUploadController::class, 'showImg']);
    });

Route::prefix('task')->group(function () {
    Route::post('/', [TaskController::class, 'store']);

    Route::middleware(TaskAccess::class)->group(function () {
        Route::put('/', [TaskController::class, 'start']);
        Route::get('/{task}', [TaskController::class, 'show'])
            ->middleware(TaskAccessIP::class);

        Route::delete('/{task}/', [TaskController::class, 'clear']);
    });
});

Route::get('/formats/', [FileFormatsController::class, 'formats'])->middleware(SetLocale::class);
Route::get('/formats/{format}/', [FileFormatsController::class, 'show'])->middleware(SetLocale::class);
Route::get('/formats/type/{type}/', [FileFormatsController::class, 'fileType'])->middleware(SetLocale::class);

Route::get('/page/', [PageController::class, 'index'])->middleware(SetLocale::class);
Route::get('/text/{key}/', [TextBlockController::class, 'show'])->middleware(SetLocale::class);

Route::get('/sitemap/', [SitemapController::class, 'index']);
Route::get('/lang/', [LanguageController::class, 'index']);
