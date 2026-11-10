@extends('layouts.app')

@section('title', 'Редагувати вкладення')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Редагування вкладення #{{ $attachment->id }}</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif
            <form action="{{ route('attachments.update', $attachment) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="post_id" class="form-label">Допис (1:M)</label>
                    <select name="post_id" id="post_id" class="form-select @error('post_id') is-invalid @enderror" required>
                        @foreach ($posts as $post)
                            <option value="{{ $post->id }}" {{ (string) old('post_id', $attachment->post_id) === (string) $post->id ? 'selected' : '' }}>{{ $post->title }}</option>
                        @endforeach
                    </select>
                    @error('post_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <x-form.input name="file_name" label="Імʼя файлу" :value="$attachment->file_name" required />
                <x-form.input name="mime_type" label="MIME-тип" :value="$attachment->mime_type" required />
                <x-form.input name="size_bytes" label="Розмір (байти)" type="number" :value="$attachment->size_bytes" required />
                <x-form.input name="path" label="Шлях" :value="$attachment->path" required />
                <button type="submit" class="btn btn-primary">Оновити</button>
                <a href="{{ route('attachments.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
