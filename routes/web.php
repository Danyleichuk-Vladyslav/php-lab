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
    return view('home');
})->name('home');

Route::get('/health', fn () => response()->json([
    'status' => 'OK',
    'timestamp' => now(),
]));

Route::get('/about', fn () => response()->json([
    'project' => 'Інтернет-блог',
    'lab' => 7,
    'framework' => 'Laravel',
]));

Route::resource('posts', PostController::class);
Route::resource('categories', CategoryController::class);
Route::resource('tags', TagController::class);
Route::resource('comments', CommentController::class);
Route::resource('attachments', AttachmentController::class);
Route::resource('profiles', ProfileController::class);
