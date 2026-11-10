<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttachmentController extends Controller
{
    public function index(Request $request): View
    {
        $query = Attachment::query()->with('post')->latest();

        if ($request->filled('post_id')) {
            $query->where('post_id', (int) $request->query('post_id'));
        }

        $items = $query->paginate(10)->withQueryString();

        return view('attachments.index', compact('items'));
    }

    public function create(): View
    {
        $posts = Post::orderByDesc('id')->get(['id', 'title']);

        return view('attachments.create', compact('posts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'file_name' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:100'],
            'size_bytes' => ['required', 'integer', 'min:1'],
            'path' => ['required', 'string', 'max:255'],
        ]);

        Attachment::create($validated);

        return redirect()
            ->route('attachments.index')
            ->with('success', 'Вкладення успішно створено!');
    }

    public function show(Attachment $attachment): View
    {
        $attachment->load('post');

        return view('attachments.show', ['item' => $attachment]);
    }

    public function edit(Attachment $attachment): View
    {
        $posts = Post::orderByDesc('id')->get(['id', 'title']);

        return view('attachments.edit', compact('attachment', 'posts'));
    }

    public function update(Request $request, Attachment $attachment): RedirectResponse
    {
        $validated = $request->validate([
            'post_id' => ['required', 'integer', 'exists:posts,id'],
            'file_name' => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string', 'max:100'],
            'size_bytes' => ['required', 'integer', 'min:1'],
            'path' => ['required', 'string', 'max:255'],
        ]);

        $attachment->update($validated);

        return redirect()
            ->route('attachments.index')
            ->with('success', 'Вкладення успішно оновлено!');
    }

    public function destroy(Attachment $attachment): RedirectResponse
    {
        $attachment->delete();

        return redirect()
            ->route('attachments.index')
            ->with('success', 'Вкладення успішно видалено!');
    }
}
