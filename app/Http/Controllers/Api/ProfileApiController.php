<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use OpenApi\Attributes as OA;

class ProfileApiController extends Controller
{
    #[OA\Get(path: '/profiles', summary: 'Список профілів', tags: ['Profiles'], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function index(): AnonymousResourceCollection
    {
        return ProfileResource::collection(
            Profile::query()->with('user')->orderBy('id')->paginate(10)
        );
    }

    #[OA\Post(path: '/profiles', summary: 'Створити власний профіль', security: [['bearerAuth' => []]], tags: ['Profiles'], responses: [new OA\Response(response: 201, description: 'Created'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function store(Request $request): JsonResponse
    {
        $this->authorize('create', Profile::class);

        $validated = $request->validate([
            'pen_name' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ]);

        $profile = Profile::create([
            'user_id' => $request->user()->id,
            'pen_name' => $validated['pen_name'] ?? null,
            'biography' => $validated['biography'] ?? null,
            'posts_count' => 0,
            'rating' => 0,
        ]);

        $profile->load('user');

        return (new ProfileResource($profile))->response()->setStatusCode(201);
    }

    #[OA\Get(path: '/profiles/{id}', summary: 'Показати профіль', tags: ['Profiles'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK')])]
    public function show(Profile $profile): ProfileResource
    {
        $profile->load('user');

        return new ProfileResource($profile);
    }

    #[OA\Put(path: '/profiles/{id}', summary: 'Оновити власний профіль', security: [['bearerAuth' => []]], tags: ['Profiles'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 200, description: 'OK'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function update(Request $request, Profile $profile): ProfileResource
    {
        $this->authorize('update', $profile);

        $validated = $request->validate([
            'pen_name' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ]);

        $profile->update([
            'pen_name' => $validated['pen_name'] ?? null,
            'biography' => $validated['biography'] ?? null,
        ]);

        $profile->load('user');

        return new ProfileResource($profile);
    }

    #[OA\Delete(path: '/profiles/{id}', summary: 'Видалити власний профіль', security: [['bearerAuth' => []]], tags: ['Profiles'], parameters: [new OA\Parameter(name: 'id', in: 'path', required: true, schema: new OA\Schema(type: 'integer'))], responses: [new OA\Response(response: 204, description: 'Deleted'), new OA\Response(response: 401, description: 'Unauthorized'), new OA\Response(response: 403, description: 'Forbidden')])]
    public function destroy(Profile $profile): JsonResponse
    {
        $this->authorize('delete', $profile);
        $profile->delete();

        return response()->json(null, 204);
    }
}
