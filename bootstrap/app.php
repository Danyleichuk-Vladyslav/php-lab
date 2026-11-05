<?php

use App\Http\Middleware\CheckAuthorRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'post.access' => CheckAuthorRole::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'posts',
            'posts/*',
            'categories',
            'categories/*',
            'tags',
            'tags/*',
            'comments',
            'comments/*',
            'attachments',
            'attachments/*',
            'profiles',
            'profiles/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
