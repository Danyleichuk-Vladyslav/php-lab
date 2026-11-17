<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class PostApiController extends Controller
{
    #[OA\Get(
        path: '/posts',
        summary: 'Список дописів',
        tags: ['Posts'],
        responses: [
            new OA\Response(response: 200, description: 'Список дописів'),
        ]
    )]
    public function index(): AnonymousResourceCollection
    {
        $posts = Post::query()
            ->with(['category', 'tags', 'user'])
            ->orderByDesc('id')
            ->paginate(10);

        return PostResource::collection($posts);
    }

    #[OA\Post(
        path: '/posts',
        summary: 'Створити допис',
        security: [['bearerAuth' => []]],
        tags: ['Posts'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['category_id', 'title', 'body'],
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer', example: 1),
                    new OA\Property(property: 'title', type: 'string', example: 'Мій допис'),
                    new OA\Property(property: 'body', type: 'string', example: 'Текст допису'),
                    new OA\Property(property: 'status', type: 'string', example: 'published'),
                    new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'integer')),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201, description: 'Допис створено'),
            new OA\Response(response: 401, description: 'Неавторизовано'),
            new OA\Response(response: 422, description: 'Помилка валідації'),
        ]
    )]
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? 'published',
            'published_at' => $validated['published_at'] ?? now(),
            'views_count' => 0,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);
        $post->load(['category', 'tags', 'user']);

        return (new PostResource($post))
            ->response()
            ->setStatusCode(201);
    }

    #[OA\Get(
        path: '/posts/{id}',
        summary: 'Показати допис',
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 200, description: 'Допис'),
            new OA\Response(response: 404, description: 'Не знайдено'),
        ]
    )]
    public function show(Post $post): PostResource
    {
        $post->load(['category', 'tags', 'user', 'comments.user', 'attachments']);

        return new PostResource($post);
    }

    #[OA\Put(
        path: '/posts/{id}',
        summary: 'Оновити власний допис',
        security: [['bearerAuth' => []]],
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['category_id', 'title', 'body'],
                properties: [
                    new OA\Property(property: 'category_id', type: 'integer'),
                    new OA\Property(property: 'title', type: 'string'),
                    new OA\Property(property: 'body', type: 'string'),
                    new OA\Property(property: 'status', type: 'string'),
                    new OA\Property(property: 'tags', type: 'array', items: new OA\Items(type: 'integer')),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200, description: 'Оновлено'),
            new OA\Response(response: 401, description: 'Неавторизовано'),
            new OA\Response(response: 403, description: 'Заборонено (чужий допис)'),
            new OA\Response(response: 404, description: 'Не знайдено'),
        ]
    )]
    public function update(Request $request, Post $post): PostResource
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? $post->status,
            'published_at' => $validated['published_at'] ?? $post->published_at,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);
        $post->load(['category', 'tags', 'user']);

        return new PostResource($post);
    }

    #[OA\Delete(
        path: '/posts/{id}',
        summary: 'Видалити власний допис',
        security: [['bearerAuth' => []]],
        tags: ['Posts'],
        parameters: [
            new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer')),
        ],
        responses: [
            new OA\Response(response: 204, description: 'Видалено'),
            new OA\Response(response: 401, description: 'Неавторизовано'),
            new OA\Response(response: 403, description: 'Заборонено'),
        ]
    )]
    public function destroy(Post $post): JsonResponse
    {
        $this->authorize('delete', $post);

        $post->tags()->detach();
        $post->delete();

        return response()->json(null, 204);
    }
}
