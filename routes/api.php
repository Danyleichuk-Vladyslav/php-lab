<?php

declare(strict_types=1);

use App\Http\Controllers\Api\AttachmentApiController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\CommentApiController;
use App\Http\Controllers\Api\PostApiController;
use App\Http\Controllers\Api\ProfileApiController;
use App\Http\Controllers\Api\TagApiController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function (): void {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/posts', [PostApiController::class, 'index']);
    Route::get('/posts/{post}', [PostApiController::class, 'show']);
    Route::get('/categories', [CategoryApiController::class, 'index']);
    Route::get('/categories/{category}', [CategoryApiController::class, 'show']);
    Route::get('/tags', [TagApiController::class, 'index']);
    Route::get('/tags/{tag}', [TagApiController::class, 'show']);
    Route::get('/comments', [CommentApiController::class, 'index']);
    Route::get('/comments/{comment}', [CommentApiController::class, 'show']);
    Route::get('/attachments', [AttachmentApiController::class, 'index']);
    Route::get('/attachments/{attachment}', [AttachmentApiController::class, 'show']);
    Route::get('/profiles', [ProfileApiController::class, 'index']);
    Route::get('/profiles/{profile}', [ProfileApiController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function (): void {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);

        Route::post('/posts', [PostApiController::class, 'store']);
        Route::put('/posts/{post}', [PostApiController::class, 'update']);
        Route::patch('/posts/{post}', [PostApiController::class, 'update']);
        Route::delete('/posts/{post}', [PostApiController::class, 'destroy']);

        Route::post('/categories', [CategoryApiController::class, 'store']);
        Route::put('/categories/{category}', [CategoryApiController::class, 'update']);
        Route::patch('/categories/{category}', [CategoryApiController::class, 'update']);
        Route::delete('/categories/{category}', [CategoryApiController::class, 'destroy']);

        Route::post('/tags', [TagApiController::class, 'store']);
        Route::put('/tags/{tag}', [TagApiController::class, 'update']);
        Route::patch('/tags/{tag}', [TagApiController::class, 'update']);
        Route::delete('/tags/{tag}', [TagApiController::class, 'destroy']);

        Route::post('/comments', [CommentApiController::class, 'store']);
        Route::put('/comments/{comment}', [CommentApiController::class, 'update']);
        Route::patch('/comments/{comment}', [CommentApiController::class, 'update']);
        Route::delete('/comments/{comment}', [CommentApiController::class, 'destroy']);

        Route::post('/attachments', [AttachmentApiController::class, 'store']);
        Route::put('/attachments/{attachment}', [AttachmentApiController::class, 'update']);
        Route::patch('/attachments/{attachment}', [AttachmentApiController::class, 'update']);
        Route::delete('/attachments/{attachment}', [AttachmentApiController::class, 'destroy']);

        Route::post('/profiles', [ProfileApiController::class, 'store']);
        Route::put('/profiles/{profile}', [ProfileApiController::class, 'update']);
        Route::patch('/profiles/{profile}', [ProfileApiController::class, 'update']);
        Route::delete('/profiles/{profile}', [ProfileApiController::class, 'destroy']);
    });
});
