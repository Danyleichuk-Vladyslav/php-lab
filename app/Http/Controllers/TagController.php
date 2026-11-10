<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TagController extends Controller
{
    public function index(): View
    {
        $items = Tag::query()
            ->withCount('posts')
            ->orderBy('name')
            ->paginate(10);

        return view('tags.index', compact('items'));
    }

    public function create(): View
    {
        return view('tags.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', 'unique:tags,name'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:tags,slug'],
            'color' => ['nullable', 'string', 'max:32'],
        ]);

        Tag::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'color' => $validated['color'] ?? null,
        ]);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Тег успішно створено!');
    }

    public function show(Tag $tag): View
    {
        $tag->load(['posts' => function ($query): void {
            $query->with(['category', 'user'])->latest('published_at');
        }]);

        return view('tags.show', ['item' => $tag]);
    }

    public function edit(Tag $tag): View
    {
        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:2', 'max:255', Rule::unique('tags', 'name')->ignore($tag->id)],
            'slug' => ['nullable', 'string', 'max:255', Rule::unique('tags', 'slug')->ignore($tag->id)],
            'color' => ['nullable', 'string', 'max:32'],
        ]);

        $tag->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'color' => $validated['color'] ?? null,
        ]);

        return redirect()
            ->route('tags.index')
            ->with('success', 'Тег успішно оновлено!');
    }

    public function destroy(Tag $tag): RedirectResponse
    {
        $tag->posts()->detach();
        $tag->delete();

        return redirect()
            ->route('tags.index')
            ->with('success', 'Тег успішно видалено!');
    }
}
