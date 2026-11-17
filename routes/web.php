<?php

declare(strict_types=1);

use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\WebAuthController;
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
    'lab' => 8,
    'framework' => 'Laravel',
]));

Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

Route::post('/logout', [WebAuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

Route::middleware('auth')->group(function () {
    Route::resource('posts', PostController::class)->except(['index', 'show']);
    Route::resource('categories', CategoryController::class)->except(['index', 'show']);
    Route::resource('tags', TagController::class)->except(['index', 'show']);
    Route::resource('comments', CommentController::class)->except(['index', 'show']);
    Route::resource('attachments', AttachmentController::class)->except(['index', 'show']);
    Route::resource('profiles', ProfileController::class)->except(['index', 'show']);
});

Route::resource('posts', PostController::class)->only(['index', 'show']);
Route::resource('categories', CategoryController::class)->only(['index', 'show']);
Route::resource('tags', TagController::class)->only(['index', 'show']);
Route::resource('comments', CommentController::class)->only(['index', 'show']);
Route::resource('attachments', AttachmentController::class)->only(['index', 'show']);
Route::resource('profiles', ProfileController::class)->only(['index', 'show']);
