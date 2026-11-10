@extends('layouts.app')

@section('title', $item->pen_name ?? 'Профіль')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $item->pen_name ?? 'Профіль #'.$item->id }}</h1>
        <div>
            <a href="{{ route('profiles.edit', $item) }}" class="btn btn-warning">Редагувати</a>
            <a href="{{ route('profiles.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    <p><strong>Користувач:</strong> {{ $item->user->name ?? '—' }} ({{ $item->user->email ?? '' }})</p>
    <p><strong>Рейтинг:</strong> {{ $item->rating }}</p>
    <p><strong>Дописів:</strong> {{ $item->posts_count }}</p>
    <div class="card"><div class="card-body">{{ $item->biography ?: 'Біографія відсутня.' }}</div></div>
@endsection
