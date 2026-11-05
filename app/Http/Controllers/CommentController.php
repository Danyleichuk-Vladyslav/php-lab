<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Comment::query()
            ->with(['post:id,title', 'user:id,name'])
            ->where('status', 'visible')
            ->latest();

        if ($request->filled('post_id')) {
            $query->where('post_id', (int) $request->query('post_id'));
        }

        return response()->json([
            'status' => 'success',
            'data' => $query->paginate(20),
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:visible,hidden'],
        ]);

        $comment = Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => $validated['user_id'] ?? null,
            'author_name' => $validated['author_name'] ?? null,
            'body' => $validated['body'],
            'status' => $validated['status'] ?? 'visible',
        ]);

        $comment->load(['post:id,title', 'user:id,name']);

        return response()->json([
            'status' => 'created',
            'data' => $comment,
        ], 201);
    }
}
