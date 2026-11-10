@extends('layouts.app')

@section('title', 'Коментар #'.$item->id)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Коментар #{{ $item->id }}</h1>
        <div>
            <a href="{{ route('comments.edit', $item) }}" class="btn btn-warning">Редагувати</a>
            <a href="{{ route('comments.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    <p><strong>Допис:</strong> {{ $item->post->title ?? '—' }}</p>
    <p><strong>Автор:</strong> {{ $item->author_name ?? $item->user->name ?? 'Гість' }}</p>
    <p><strong>Статус:</strong> {{ $item->status }}</p>
    <div class="card"><div class="card-body">{{ $item->body }}</div></div>
@endsection
