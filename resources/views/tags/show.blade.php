@extends('layouts.app')

@section('title', $item->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $item->name }}</h1>
        <div>
            <a href="{{ route('tags.edit', $item) }}" class="btn btn-warning">Редагувати</a>
            <a href="{{ route('tags.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    <p><strong>Slug:</strong> {{ $item->slug }}</p>
    <p><strong>Колір:</strong> {{ $item->color ?? '—' }}</p>
    <h3 class="mt-4">Дописи з цим тегом (M:M)</h3>
    <ul class="list-group">
        @forelse ($item->posts as $post)
            <li class="list-group-item">
                <a href="{{ route('posts.show', $post) }}">{{ $post->title }}</a>
            </li>
        @empty
            <li class="list-group-item text-muted">Дописів немає.</li>
        @endforelse
    </ul>
@endsection
