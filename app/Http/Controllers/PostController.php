<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(): View
    {
        $items = Post::query()
            ->with(['category', 'tags', 'user'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('posts.index', compact('items'));
    }

    public function create(): View
    {
        $this->authorize('create', Post::class);

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();

        return view('posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Post::class);

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post = Post::create([
            'user_id' => $request->user()->id,
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? 'published',
            'published_at' => $validated['published_at'] ?? now(),
            'views_count' => 0,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Допис успішно створено!');
    }

    public function show(Post $post): View
    {
        $post->load(['category', 'tags', 'user', 'comments.user', 'attachments']);

        return view('posts.show', ['item' => $post]);
    }

    public function edit(Post $post): View
    {
        $this->authorize('update', $post);

        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $post->load('tags');

        return view('posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorize('update', $post);

        $validated = $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post->update([
            'category_id' => $validated['category_id'],
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => $validated['status'] ?? $post->status,
            'published_at' => $validated['published_at'] ?? $post->published_at,
        ]);

        $post->tags()->sync($validated['tags'] ?? []);

        return redirect()
            ->route('posts.index')
            ->with('success', 'Допис успішно оновлено!');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->authorize('delete', $post);

        $post->tags()->detach();
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Допис успішно видалено!');
    }
}
