<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
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
        $users = User::query()
            ->whereDoesntHave('profile')
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('profiles.create', compact('users'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', 'unique:profiles,user_id'],
            'pen_name' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
            'posts_count' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
        ]);

        Profile::create([
            'user_id' => $validated['user_id'],
            'pen_name' => $validated['pen_name'] ?? null,
            'biography' => $validated['biography'] ?? null,
            'posts_count' => $validated['posts_count'] ?? 0,
            'rating' => $validated['rating'] ?? 0,
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
        $users = User::query()
            ->where(function ($query) use ($profile): void {
                $query->whereDoesntHave('profile')
                    ->orWhere('id', $profile->user_id);
            })
            ->orderBy('name')
            ->get(['id', 'name', 'email']);

        return view('profiles.edit', compact('profile', 'users'));
    }

    public function update(Request $request, Profile $profile): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer', 'exists:users,id', Rule::unique('profiles', 'user_id')->ignore($profile->id)],
            'pen_name' => ['nullable', 'string', 'max:255'],
            'biography' => ['nullable', 'string'],
            'posts_count' => ['nullable', 'integer', 'min:0'],
            'rating' => ['nullable', 'numeric', 'min:0', 'max:5'],
        ]);

        $profile->update([
            'user_id' => $validated['user_id'],
            'pen_name' => $validated['pen_name'] ?? null,
            'biography' => $validated['biography'] ?? null,
            'posts_count' => $validated['posts_count'] ?? 0,
            'rating' => $validated['rating'] ?? 0,
        ]);

        return redirect()
            ->route('profiles.index')
            ->with('success', 'Профіль успішно оновлено!');
    }

    public function destroy(Profile $profile): RedirectResponse
    {
        $profile->delete();

        return redirect()
            ->route('profiles.index')
            ->with('success', 'Профіль успішно видалено!');
    }
}
