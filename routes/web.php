<?php

declare(strict_types=1);

use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
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
    'lab' => 5,
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
});

Route::get('/comments', [CommentController::class, 'index'])->name('comments.index');
