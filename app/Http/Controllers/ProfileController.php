<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(): JsonResponse
    {
        $profiles = Profile::query()
            ->with('user:id,name,email,role')
            ->orderBy('id')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $profiles,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:profiles,user_id'],
            'pen_name' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
            'posts_count' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
        ]);

        $profile = Profile::create([
            'user_id' => $validated['user_id'],
            'pen_name' => $validated['pen_name'] ?? null,
            'biography' => $validated['biography'] ?? null,
            'posts_count' => $validated['posts_count'] ?? 0,
            'rating' => $validated['rating'] ?? 0,
        ]);

        $profile->load('user:id,name,email,role');

        return response()->json([
            'status' => 'created',
            'data' => $profile,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $profile = Profile::with('user:id,name,email,role')->findOrFail((int) $id);

        return response()->json([
            'status' => 'success',
            'data' => $profile,
        ], 200);
    }
}
