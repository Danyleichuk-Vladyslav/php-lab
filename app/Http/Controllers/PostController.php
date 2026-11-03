<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Тимчасове in-memory сховище дописів інтернет-блогу.
     *
     * @var list<array{id: int, title: string, body: string, category_id: int, published_at: string}>
     */
    private array $items = [
        [
            'id' => 1,
            'title' => 'Подорож Карпатами',
            'body' => 'Короткий текст про осінні гори.',
            'category_id' => 3,
            'published_at' => '2026-11-01 12:00:00',
        ],
        [
            'id' => 2,
            'title' => 'Новини технологій',
            'body' => 'Огляд оновлень для веб-розробників.',
            'category_id' => 1,
            'published_at' => '2026-11-02 09:30:00',
        ],
        [
            'id' => 3,
            'title' => 'Рецепт борщу',
            'body' => 'Класичний рецепт із домашнього блогу.',
            'category_id' => 2,
            'published_at' => '2026-11-03 08:15:00',
        ],
    ];

    public function index(Request $request): JsonResponse
    {
        return response()->json([
            'status' => 'success',
            'data' => $this->items,
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'category_id' => ['required', 'integer', 'min:1'],
            'published_at' => ['required', 'date'],
        ]);

        $newItem = [
            'id' => count($this->items) + 1,
            'title' => $validated['title'],
            'body' => $validated['body'],
            'category_id' => (int) $validated['category_id'],
            'published_at' => $validated['published_at'],
        ];

        $this->items[] = $newItem;

        return response()->json([
            'status' => 'created',
            'data' => $newItem,
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $itemId = (int) $id;

        foreach ($this->items as $item) {
            if ($item['id'] === $itemId) {
                return response()->json([
                    'status' => 'success',
                    'data' => $item,
                ], 200);
            }
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Ресурс із зазначеним ідентифікатором не знайдено.',
        ], 404);
    }
}
