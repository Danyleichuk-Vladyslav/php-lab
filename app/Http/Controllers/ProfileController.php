<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $items = Profile::query()
            ->with('user')
            ->orderBy('id')
            ->paginate(10);

        return view('profiles.index', compact('items'));
    }

    public function create(): View
    {
        $this->authorize('create', Profile::class);

        return view('profiles.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Profile::class);

        $validated = $request->validate([
            'pen_name' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ]);

        Profile::create([
            'user_id' => $request->user()->id,
            'pen_name' => $validated['pen_name'] ?? null,
            'biography' => $validated['biography'] ?? null,
            'posts_count' => 0,
            'rating' => 0,
        ]);

        return redirect()
            ->route('profiles.index')
            ->with('success', 'Профіль успішно створено!');
    }

    public function show(Profile $profile): View
    {
        $profile->load('user');

        return view('profiles.show', ['item' => $profile]);
    }

    public function edit(Profile $profile): View
    {
        $this->authorize('update', $profile);

        return view('profiles.edit', compact('profile'));
    }

    public function update(Request $request, Profile $profile): RedirectResponse
    {
        $this->authorize('update', $profile);

        $validated = $request->validate([
            'pen_name' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
        ]);

        $profile->update([
            'pen_name' => $validated['pen_name'] ?? null,
            'biography' => $validated['biography'] ?? null,
        ]);

        return redirect()
            ->route('profiles.index')
            ->with('success', 'Профіль успішно оновлено!');
    }

    public function destroy(Profile $profile): RedirectResponse
    {
        $this->authorize('delete', $profile);

        $profile->delete();

        return redirect()
            ->route('profiles.index')
            ->with('success', 'Профіль успішно видалено!');
    }
}
