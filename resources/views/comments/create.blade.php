@extends('layouts.app')

@section('title', 'Додати коментар')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Створення коментаря</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif

            <p class="text-muted">Автор: <strong>{{ auth()->user()->name }}</strong> (встановлюється автоматично)</p>

            <form action="{{ route('comments.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="post_id" class="form-label">Допис (1:M)</label>
                    <select name="post_id" id="post_id" class="form-select @error('post_id') is-invalid @enderror">
                        <option value="">-- Оберіть допис --</option>
                        @foreach ($posts as $post)
                            <option value="{{ $post->id }}" {{ (string) old('post_id') === (string) $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                        @endforeach
                    </select>
                    @error('post_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <x-form.textarea name="body" label="Текст коментаря" rows="4" />
                <div class="mb-3">
                    <label for="status" class="form-label">Статус</label>
                    <select name="status" id="status" class="form-select">
                        <option value="visible" @selected(old('status', 'visible') === 'visible')>visible</option>
                        <option value="hidden" @selected(old('status') === 'hidden')>hidden</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-success">Зберегти</button>
                <a href="{{ route('comments.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
