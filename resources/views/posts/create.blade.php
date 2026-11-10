@extends('layouts.app')

@section('title', 'Додати допис')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h2>Створення нового допису</h2>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif

            <form action="{{ route('posts.store') }}" method="POST">
                @csrf

                <x-form.input name="title" label="Заголовок" />
                <x-form.textarea name="body" label="Текст допису" rows="6" />

                <div class="mb-3">
                    <label for="user_id" class="form-label">Автор</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror">
                        <option value="">-- Оберіть автора --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="category_id" class="form-label">Категорія (звʼязок 1:M)</label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Оберіть категорію --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ (string) old('category_id') === (string) $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label d-block">Теги / Маркери (звʼязок M:M)</label>
                    @foreach ($tags as $tag)
                        <div class="form-check form-check-inline">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                name="tags[]"
                                id="tag_{{ $tag->id }}"
                                value="{{ $tag->id }}"
                                {{ in_array($tag->id, old('tags', []), false) ? 'checked' : '' }}
                            >
                            <label class="form-check-label" for="tag_{{ $tag->id }}">{{ $tag->name }}</label>
                        </div>
                    @endforeach
                    @error('tags')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Статус</label>
                    <select name="status" id="status" class="form-select">
                        @foreach (['published' => 'Опубліковано', 'draft' => 'Чернетка', 'archived' => 'Архів'] as $value => $label)
                            <option value="{{ $value }}" {{ old('status', 'published') === $value ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-success mt-3">Зберегти</button>
                <a href="{{ route('posts.index') }}" class="btn btn-secondary mt-3">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
