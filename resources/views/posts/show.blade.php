@extends('layouts.app')

@section('title', $item->title)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $item->title }}</h1>
        <div>
            <a href="{{ route('posts.edit', $item) }}" class="btn btn-warning">Редагувати</a>
            <a href="{{ route('posts.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <p><strong>ID:</strong> {{ $item->id }}</p>
            <p><strong>Автор:</strong> {{ $item->user->name ?? '—' }}</p>
            <p><strong>Категорія (1:M):</strong> {{ $item->category->name ?? 'Немає' }}</p>
            <p><strong>Статус:</strong> {{ $item->status }}</p>
            <p><strong>Опубліковано:</strong> {{ optional($item->published_at)->format('d.m.Y H:i') ?? '—' }}</p>
            <p><strong>Перегляди:</strong> {{ $item->views_count }}</p>
            <p><strong>Теги (M:M):</strong>
                @forelse ($item->tags as $tag)
                    <span class="badge bg-secondary">{{ $tag->name }}</span>
                @empty
                    <span class="text-muted">немає</span>
                @endforelse
            </p>
            <hr>
            <div>{!! nl2br(e($item->body)) !!}</div>
        </div>
    </div>

    <h3>Коментарі</h3>
    <ul class="list-group mb-4">
        @forelse ($item->comments as $comment)
            <li class="list-group-item">
                <strong>{{ $comment->author_name ?? $comment->user->name ?? 'Гість' }}:</strong>
                {{ $comment->body }}
            </li>
        @empty
            <li class="list-group-item text-muted">Коментарів немає.</li>
        @endforelse
    </ul>

    <h3>Вкладення</h3>
    <ul class="list-group">
        @forelse ($item->attachments as $attachment)
            <li class="list-group-item">{{ $attachment->file_name }} ({{ $attachment->mime_type }})</li>
        @empty
            <li class="list-group-item text-muted">Вкладень немає.</li>
        @endforelse
    </ul>
@endsection
