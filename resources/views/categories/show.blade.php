@extends('layouts.app')

@section('title', $item->name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $item->name }}</h1>
        <div>
            <a href="{{ route('categories.edit', $item) }}" class="btn btn-warning">Редагувати</a>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    <p><strong>Slug:</strong> {{ $item->slug }}</p>
    <p>{{ $item->description }}</p>
    <h3 class="mt-4">Дописи категорії (1:M)</h3>
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
