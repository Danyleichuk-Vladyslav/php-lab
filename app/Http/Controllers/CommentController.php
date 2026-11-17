<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
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
        $this->authorize('create', Comment::class);

        $posts = Post::orderByDesc('id')->get(['id', 'title']);

        return view('comments.create', compact('posts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Comment::class);

        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:visible,hidden'],
        ]);

        Comment::create([
            'post_id' => $validated['post_id'],
            'user_id' => $request->user()->id,
            'author_name' => $request->user()->name,
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
        $this->authorize('update', $comment);

        $posts = Post::orderByDesc('id')->get(['id', 'title']);

        return view('comments.edit', compact('comment', 'posts'));
    }

    public function update(Request $request, Comment $comment): RedirectResponse
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:visible,hidden'],
        ]);

        $comment->update([
            'post_id' => $validated['post_id'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? $comment->status,
        ]);

        return redirect()
            ->route('comments.index')
            ->with('success', 'Коментар успішно оновлено!');
    }

    public function destroy(Comment $comment): RedirectResponse
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return redirect()
            ->route('comments.index')
            ->with('success', 'Коментар успішно видалено!');
    }
}
