<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TagController extends Controller
{
    public function index(): JsonResponse
    {
        $tags = Tag::query()
            ->withCount('posts')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $tags,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
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

        return response()->json([
            'status' => 'created',
            'data' => $tag,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $tag = Tag::with(['posts' => function ($query): void {
            $query->with(['category:id,name'])->latest('published_at');
        }])->findOrFail((int) $id);

        return response()->json([
            'status' => 'success',
            'data' => $tag,
        ], 200);
    }
}
