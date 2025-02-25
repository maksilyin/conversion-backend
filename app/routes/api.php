<?php

use App\Http\Controllers\FileFormatsController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TextBlockController;
use App\Http\Middleware\CheckFileType;
use App\Http\Middleware\CheckTask;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ValidateTaskSize;
use Illuminate\Support\Facades\Route;

Route::prefix('file')->group(function () {
    Route::post('/upload/', [FileUploadController::class, 'uploadChunk'])
        ->middleware([CheckTask::class, CheckFileType::class, ValidateTaskSize::class]);

    Route::delete('/delete/{task}/{hash}/', [FileUploadController::class, 'deleteFile'])->name('file.delete');

    Route::get('/download/{task}/all/', [FileUploadController::class, 'downloadZip']);
    Route::get('/download/{task}/{hash}/', [FileUploadController::class, 'download']);
    Route::get('/img/{task}/{filename}', [FileUploadController::class, 'showImg']);
})->middleware(CheckTask::class);

Route::prefix('task')->group(function () {
    Route::get('/{task_id}', [TaskController::class, 'get']);
    Route::put('/', [TaskController::class, 'start']);
    Route::post('/create/', [TaskController::class, 'create']);
    Route::post('/file/create/', [TaskController::class, 'createFile']);
    Route::delete('/{task}/', [FileUploadController::class, 'clear']);
});

Route::get('/formats/', [FileFormatsController::class, 'formats'])->middleware(SetLocale::class);
Route::get('/formats/{format}/', [FileFormatsController::class, 'show'])->middleware(SetLocale::class);
Route::get('/formats/type/{type}/', [FileFormatsController::class, 'fileType'])->middleware(SetLocale::class);

//Route::get('/test/', [\App\Http\Controllers\TestFormatController::class, 'test']);
//Route::get('/test/task/', [\App\Http\Controllers\TestFormatController::class, 'testTask']);
Route::get('/page/', [PageController::class, 'index'])->middleware(SetLocale::class);
Route::get('/text/{key}/', [TextBlockController::class, 'show'])->middleware(SetLocale::class);

Route::get('/sitemap/', [SitemapController::class, 'index']);
Route::get('/lang/', [LanguageController::class, 'index']);
