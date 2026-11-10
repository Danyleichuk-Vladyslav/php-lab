<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PostController extends Controller
{
    /**
     * Список дописів із жадібним завантаженням категорії та тегів.
     */
    public function index(): View
    {
        $items = Post::query()
            ->with(['category', 'tags', 'user'])
            ->orderByDesc('id')
            ->paginate(10);

        return view('posts.index', compact('items'));
    }

    /**
     * Форма створення допису.
     */
    public function create(): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $users = User::orderBy('name')->get();

        return view('posts.create', compact('categories', 'tags', 'users'));
    }

    /**
     * Збереження допису з прив'язкою до категорії (1:M) та тегів (M:M).
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post = Post::create([
            'user_id' => $validated['user_id'],
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

    /**
     * Детальна сторінка допису зі зв'язаними сутностями.
     */
    public function show(Post $post): View
    {
        $post->load(['category', 'tags', 'user', 'comments.user', 'attachments']);

        return view('posts.show', ['item' => $post]);
    }

    /**
     * Форма редагування допису.
     */
    public function edit(Post $post): View
    {
        $categories = Category::orderBy('name')->get();
        $tags = Tag::orderBy('name')->get();
        $users = User::orderBy('name')->get();
        $post->load('tags');

        return view('posts.edit', compact('post', 'categories', 'tags', 'users'));
    }

    /**
     * Оновлення допису та синхронізація тегів M:M.
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'title' => ['required', 'string', 'min:2', 'max:255'],
            'body' => ['required', 'string', 'min:2'],
            'status' => ['nullable', 'string', 'in:draft,published,archived'],
            'published_at' => ['nullable', 'date'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['integer', 'exists:tags,id'],
        ]);

        $post->update([
            'user_id' => $validated['user_id'],
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

    /**
     * Видалення допису.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->tags()->detach();
        $post->delete();

        return redirect()
            ->route('posts.index')
            ->with('success', 'Допис успішно видалено!');
    }
}
