<?php

declare(strict_types=1);

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/health', fn () => response()->json([
    'status' => 'OK',
    'timestamp' => now(),
]));

Route::get('/about', fn () => response()->json([
    'project' => 'Інтернет-блог',
    'lab' => 6,
    'framework' => 'Laravel',
]));

Route::prefix('posts')->name('posts.')->group(function () {
    Route::get('/', [PostController::class, 'index'])->name('index');
    Route::post('/', [PostController::class, 'store'])
        ->middleware('post.access')
        ->name('store');
    Route::get('/{id}', [PostController::class, 'show'])
        ->whereNumber('id')
        ->name('show');
    Route::put('/{id}', [PostController::class, 'update'])
        ->middleware('post.access')
        ->whereNumber('id')
        ->name('update');
});

Route::prefix('profiles')->name('profiles.')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])->name('index');
    Route::post('/', [ProfileController::class, 'store'])->name('store');
    Route::get('/{id}', [ProfileController::class, 'show'])
        ->whereNumber('id')
        ->name('show');
});

Route::prefix('categories')->name('categories.')->group(function () {
    Route::get('/', [CategoryController::class, 'index'])->name('index');
    Route::post('/', [CategoryController::class, 'store'])->name('store');
    Route::get('/{id}', [CategoryController::class, 'show'])
        ->whereNumber('id')
        ->name('show');
});

Route::prefix('tags')->name('tags.')->group(function () {
    Route::get('/', [TagController::class, 'index'])->name('index');
    Route::post('/', [TagController::class, 'store'])->name('store');
    Route::get('/{id}', [TagController::class, 'show'])
        ->whereNumber('id')
        ->name('show');
});

Route::prefix('comments')->name('comments.')->group(function () {
    Route::get('/', [CommentController::class, 'index'])->name('index');
    Route::post('/', [CommentController::class, 'store'])->name('store');
});

Route::prefix('attachments')->name('attachments.')->group(function () {
    Route::get('/', [AttachmentController::class, 'index'])->name('index');
    Route::post('/', [AttachmentController::class, 'store'])->name('store');
});
