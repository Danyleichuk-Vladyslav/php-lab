<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use OpenApi\Attributes as OA;

class CategoryApiController extends Controller
{
    #[OA\Get(path: '/categories', summary: 'Список категорій', tags: ['Categories'], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(
            Category::query()->withCount('posts')->orderBy('name')->paginate(10)
        );
    }

    #[OA\Post(path: '/categories', summary: 'Створити категорію', security: [['bearerAuth' => []]], tags: ['Categories'], responses: [new OA\Response(response: 201, description: 'Created'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:categories,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:categories,slug'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    #[OA\Get(path: '/categories/{id}', summary: 'Показати категорію', tags: ['Categories'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function show(Category $category): CategoryResource
    {
        $category->load(['posts' => fn ($q) => $q->with(['tags', 'user'])]);

        return new CategoryResource($category);
    }

    #[OA\Put(path: '/categories/{id}', summary: 'Оновити категорію', security: [['bearerAuth' => []]], tags: ['Categories'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function update(Request $request, Category $category): CategoryResource
    {
        $this->authorize('update', $category);

        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('categories', 'name')->ignore($category->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('categories', 'slug')->ignore($category->id)],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'description' => $validated['description'] ?? null,
        ]);

        return new CategoryResource($category);
    }

    #[OA\Delete(path: '/categories/{id}', summary: 'Видалити категорію', security: [['bearerAuth' => []]], tags: ['Categories'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 204, description: 'Deleted'), new OA\Response(response: 401, description: 'Unauthorized')])]
    public function destroy(Category $category): JsonResponse
    {
        $this->authorize('delete', $category);
        $category->delete();

        return response()->json(null, 204);
    }
}
