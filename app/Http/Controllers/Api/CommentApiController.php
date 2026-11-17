<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class CommentApiController extends Controller
{
    #[OA\Get(path: '/comments', summary: 'Список коментарів', tags: ['Comments'], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function index(): AnonymousResourceCollection
    {
        return CommentResource::collection(
            Comment::query()->with(['post', 'user'])->latest()->paginate(10)
        );
    }

    #[OA\Post(path: '/comments', summary: 'Створити коментар', security: [['bearerAuth' => []]], tags: ['Comments'], responses: [new OA\Response(response: 201, description: 'Created'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Comment::class);

        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:visible,hidden'],
        ]);

        $comment = Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => $request->user()->id,
            'author_name' => $request->user()->name,
            'body' => $validated['body'],
            'status' => $validated['status'] ?? 'visible',
        ]);

        $comment->load(['post', 'user']);

        return (new CommentResource($comment))->response()->setStatusCode(201);
    }

    #[OA\Get(path: '/comments/{id}', summary: 'Показати коментар', tags: ['Comments'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function show(Comment $comment): CommentResource
    {
        $comment->load(['post', 'user']);

        return new CommentResource($comment);
    }

    #[OA\Put(path: '/comments/{id}', summary: 'Оновити власний коментар', security: [['bearerAuth' => []]], tags: ['Comments'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function update(Request $request, Comment $comment): CommentResource
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:visible,hidden'],
        ]);

        $comment->update([
            'post_id' => $validated['post_id'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? $comment->status,
        ]);

        $comment->load(['post', 'user']);

        return new CommentResource($comment);
    }

    #[OA\Delete(path: '/comments/{id}', summary: 'Видалити власний коментар', security: [['bearerAuth' => []]], tags: ['Comments'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 204, description: 'Deleted'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function destroy(Comment $comment): JsonResponse
    {
        $this->authorize('delete', $comment);
        $comment->delete();

        return response()->json(null, 204);
    }
}
