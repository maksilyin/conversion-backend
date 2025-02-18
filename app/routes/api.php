<?php

use App\Http\Controllers\FileFormatsController;
use App\Http\Controllers\FileUploadController;
use App\Http\Controllers\TaskController;
use App\Http\Middleware\CheckFileType;
use App\Http\Middleware\CheckTask;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\ValidateChunkSize;
use Illuminate\Support\Facades\Route;

Route::prefix('file')->group(function () {
    Route::post('/upload/', [FileUploadController::class, 'uploadChunk'])
        ->middleware([CheckFileType::class, ValidateChunkSize::class]);
    Route::delete('/delete/', [FileUploadController::class, 'deleteFile']);

    Route::get('/download/{task}/all/', [FileUploadController::class, 'downloadZip']);
    Route::get('/download/{task}/{hash}/', [FileUploadController::class, 'download']);
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

//Route::get('/test/', [\App\Http\Controllers\TestFormatController::class, 'test']);
//Route::get('/test/task/', [\App\Http\Controllers\TestFormatController::class, 'testTask']);
Route::get('/page/', [\App\Http\Controllers\PageController::class, 'index'])->middleware(SetLocale::class);
Route::get('/text/{key}/', [\App\Http\Controllers\TextBlockController::class, 'show'])->middleware(SetLocale::class);

Route::get('/sitemap/', [\App\Http\Controllers\SitemapController::class, 'index']);
Route::get('/lang/', [\App\Http\Controllers\LanguageController::class, 'index']);
