<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * @var list<array{id: int, post_id: int, author: string, body: string}>
     */
    private array $comments = [
        [
            'id' => 1,
            'post_id' => 1,
            'author' => 'Олена',
            'body' => 'Дуже цікавий допис про Карпати.',
        ],
        [
            'id' => 2,
            'post_id' => 1,
            'author' => 'Іван',
            'body' => 'Коли плануєте наступну подорож?',
        ],
        [
            'id' => 3,
            'post_id' => 2,
            'author' => 'Марія',
            'body' => 'Дякую за огляд технологій.',
        ],
    ];

    public function index(Request $request): JsonResponse
    {
        $postId = $request->query('post_id');

        $items = $this->comments;

        if ($postId !== null) {
            $target = (int) $postId;
            $items = array_values(array_filter(
                $this->comments,
                static fn (array $comment): bool => $comment['post_id'] === $target,
            ));
        }

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ], 200);
    }
}
