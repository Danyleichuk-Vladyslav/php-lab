<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AttachmentResource;
use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class AttachmentApiController extends Controller
{
    #[OA\Get(path: '/attachments', summary: 'Список вкладень', tags: ['Attachments'], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function index(): AnonymousResourceCollection
    {
        return AttachmentResource::collection(
            Attachment::query()->with('post')->latest()->paginate(10)
        );
    }

    #[OA\Post(path: '/attachments', summary: 'Створити вкладення до власного допису', security: [['bearerAuth' => []]], tags: ['Attachments'], responses: [new OA\Response(response: 201, description: 'Created'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Attachment::class);

        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'file_name' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:100'],
            'size_bytes' => ['required', 'integer', 'min:1'],
            'path' => ['required', 'string', 'max:255'],
        ]);

        $post = Post::findOrFail($validated['post_id']);
        $this->authorize('update', $post);

        $attachment = Attachment::create($validated);
        $attachment->load('post');

        return (new AttachmentResource($attachment))->response()->setStatusCode(201);
    }

    #[OA\Get(path: '/attachments/{id}', summary: 'Показати вкладення', tags: ['Attachments'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function show(Attachment $attachment): AttachmentResource
    {
        $attachment->load('post');

        return new AttachmentResource($attachment);
    }

    #[OA\Put(path: '/attachments/{id}', summary: 'Оновити вкладення', security: [['bearerAuth' => []]], tags: ['Attachments'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function update(Request $request, Attachment $attachment): AttachmentResource
    {
        $this->authorize('update', $attachment);

        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'file_name' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:100'],
            'size_bytes' => ['required', 'integer', 'min:1'],
            'path' => ['required', 'string', 'max:255'],
        ]);

        $post = Post::findOrFail($validated['post_id']);
        $this->authorize('update', $post);

        $attachment->update($validated);
        $attachment->load('post');

        return new AttachmentResource($attachment);
    }

    #[OA\Delete(path: '/attachments/{id}', summary: 'Видалити вкладення', security: [['bearerAuth' => []]], tags: ['Attachments'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 204, description: 'Deleted'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function destroy(Attachment $attachment): JsonResponse
    {
        $this->authorize('delete', $attachment);
        $attachment->delete();

        return response()->json(null, 204);
    }
}
