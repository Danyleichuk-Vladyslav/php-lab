<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

class TagApiController extends Controller
{
    #[OA\Get(path: '/tags', summary: 'Список тегів', tags: ['Tags'], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function index(): AnonymousResourceCollection
    {
        return TagResource::collection(
            Tag::query()->withCount('posts')->orderBy('name')->paginate(10)
        );
    }

    #[OA\Post(path: '/tags', summary: 'Створити тег', security: [['bearerAuth' => []]], tags: ['Tags'], responses: [new OA\Response(response: 201, description: 'Created'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Tag::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:tags,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug'],
            'color' => ['nullable', 'string', 'max:32'],
        ]);

        $tag = Tag::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'color' => $validated['color'] ?? null,
        ]);

        return (new TagResource($tag))->response()->setStatusCode(201);
    }

    #[OA\Get(path: '/tags/{id}', summary: 'Показати тег', tags: ['Tags'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function show(Tag $tag): TagResource
    {
        $tag->load(['posts' => fn ($q) => $q->with(['category', 'user'])]);

        return new TagResource($tag);
    }

    #[OA\Put(path: '/tags/{id}', summary: 'Оновити тег', security: [['bearerAuth' => []]], tags: ['Tags'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function update(Request $request, Tag $tag): TagResource
    {
        $this->authorize('update', $tag);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('tags', 'name')->ignore($tag->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($tag->id)],
            'color' => ['nullable', 'string', 'max:32'],
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'color' => $validated['color'] ?? null,
        ]);

        return new TagResource($tag);
    }

    #[OA\Delete(path: '/tags/{id}', summary: 'Видалити тег', security: [['bearerAuth' => []]], tags: ['Tags'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 204, description: 'Deleted'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function destroy(Tag $tag): JsonResponse
    {
        $this->authorize('delete', $tag);
        $tag->posts()->detach();
        $tag->delete();

        return response()->json(null, 204);
    }
}
