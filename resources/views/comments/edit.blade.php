@extends('layouts.app')

@section('title', 'Редагувати коментар')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Редагування коментаря #{{ $comment->id }}</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif

            <p class="text-muted">Автор (не змінюється): <strong>{{ $comment->user->name ?? $comment->author_name ?? '—' }}</strong></p>

            <form action="{{ route('comments.update', $comment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="post_id" class="form-label">Допис (1:M)</label>
                    <select name="post_id" id="post_id" class="form-select @error('post_id') is-invalid @enderror">
                        @foreach ($posts as $post)
                            <option value="{{ $post->id }}" {{ (string) old('post_id', $comment->post_id) === (string) $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                        @endforeach
                    </select>
                    @error('post_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <x-form.textarea name="body" label="Текст коментаря" :value="$comment->body" rows="4" />
                <div class="mb-3">
                    <label for="status" class="form-label">Статус</label>
                    <select name="status" id="status" class="form-select">
                        <option value="visible" @selected(old('status', $comment->status) === 'visible')>visible</option>
                        <option value="hidden" @selected(old('status', $comment->status) === 'hidden')>hidden</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Оновити</button>
                <a href="{{ route('comments.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
