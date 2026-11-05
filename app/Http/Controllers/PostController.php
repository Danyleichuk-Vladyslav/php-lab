<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Список дописів із жадібним завантаженням категорії та тегів.
     */
    public function index(Request $request): JsonResponse
    {
        $posts = Post::query()
            ->with(['category:id,name,slug', 'tags:id,name,slug', 'user:id,name'])
            ->where('status', 'published')
            ->latest('published_at')
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => $posts,
        ], 200);
    }

    /**
     * Створення допису з прив'язкою до категорії (1:M) та тегів (M:M).
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post = Post::create([
            'user_id' => $validated['user_id'],
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? 'published',
            'published_at' => $validated['published_at'] ?? now(),
            'views_count' => 0,
        ]);

        if (! empty($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        $post->load(['category:id,name,slug', 'tags:id,name,slug', 'user:id,name']);

        return response()->json([
            'status' => 'created',
            'data' => $post,
        ], 201);
    }

    /**
     * Один допис зі зв'язаними сутностями.
     */
    public function show(string $id): JsonResponse
    {
        $post = Post::with([
            'category:id,name,slug',
            'tags:id,name,slug',
            'user:id,name',
            'comments',
            'attachments',
        ])->findOrFail((int) $id);

        return response()->json([
            'status' => 'success',
            'data' => $post,
        ], 200);
    }

    /**
     * Оновлення допису та синхронізація тегів M:M.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $post = Post::findOrFail((int) $id);

        $validated = $request->validate([
            'category_id' => ['sometimes', 'integer', 'exists:categories,id'],
            'title' => ['sometimes', 'string', 'min:2', 'max:255'],
            'body' => ['sometimes', 'string', 'min:2'],
            'status' => ['sometimes', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post->update(collect($validated)->except('tags')->all());

        if (array_key_exists('tags', $validated)) {
            $post->tags()->sync($validated['tags'] ?? []);
        }

        $post->load(['category:id,name,slug', 'tags:id,name,slug', 'user:id,name']);

        return response()->json([
            'status' => 'success',
            'data' => $post,
        ], 200);
    }
}
