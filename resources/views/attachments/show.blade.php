@extends('layouts.app')

@section('title', $item->file_name)

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>{{ $item->file_name }}</h1>
        <div>
            <a href="{{ route('attachments.edit', $item) }}" class="btn btn-warning">Редагувати</a>
            <a href="{{ route('attachments.index') }}" class="btn btn-secondary">Назад</a>
        </div>
    </div>
    <p><strong>Допис:</strong> {{ $item->post->title ?? '—' }}</p>
    <p><strong>MIME:</strong> {{ $item->mime_type }}</p>
    <p><strong>Розмір:</strong> {{ $item->size_bytes }} байт</p>
    <p><strong>Шлях:</strong> {{ $item->path }}</p>
@endsection
