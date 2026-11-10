<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Comment::query()
            ->with(['post', 'user'])
            ->latest();

        if ($request->filled('post_id')) {
            $query->where('post_id', (int) $request->query('post_id'));
        }

        $items = $query->paginate(10)->withQueryString();

        return view('comments.index', compact('items'));
    }

    public function create(): View
    {
        $posts = Post::orderByDesc('id')->get(['id', 'title']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('comments.create', compact('posts', 'users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:visible,hidden'],
        ]);

        Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => $validated['user_id'] ?? null,
            'author_name' => $validated['author_name'] ?? null,
            'body' => $validated['body'],
            'status' => $validated['status'] ?? 'visible',
        ]);

        return redirect()
            ->route('comments.index')
            ->with('success', 'Коментар успішно створено!');
    }

    public function show(Comment $comment): View
    {
        $comment->load(['post', 'user']);

        return view('comments.show', ['item' => $comment]);
    }

    public function edit(Comment $comment): View
    {
        $posts = Post::orderByDesc('id')->get(['id', 'title']);
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('comments.edit', compact('comment', 'posts', 'users'));
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
            'author_name' => ['nullable', 'string', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:visible,hidden'],
        ]);

        $comment->update([
            'post_id' => $validated['post_id'],
            'user_id' => $validated['user_id'] ?? null,
            'author_name' => $validated['author_name'] ?? null,
            'body' => $validated['body'],
            'status' => $validated['status'] ?? $comment->status,
        ]);

        return redirect()
            ->route('comments.index')
            ->with('success', 'Коментар успішно оновлено!');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $comment->delete();

        return redirect()
            ->route('comments.index')
            ->with('success', 'Коментар успішно видалено!');
    }
}
