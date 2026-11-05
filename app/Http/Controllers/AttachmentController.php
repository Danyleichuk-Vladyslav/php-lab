<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AttachmentController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Attachment::query()->with('post:id,title')->latest();

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
            'file_name' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:100'],
            'size_bytes' => ['required', 'integer', 'min:1'],
            'path' => ['required', 'string', 'max:255'],
        ]);

        $attachment = Attachment::create($validated);
        $attachment->load('post:id,title');

        return response()->json([
            'status' => 'created',
            'data' => $attachment,
        ], 201);
    }
}
