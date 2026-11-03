<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthorRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('post') && ! $request->hasHeader('X-Author-Role')) {
            return response()->json([
                'status' => 'access_denied',
                'message' => 'Відхилено: відсутній обов\'язковий заголовок X-Author-Role.',
            ], 403);
        }

        $response = $next($request);
        $response->headers->set('X-Security-Policy', 'Enforced');

        return $response;
    }
}
