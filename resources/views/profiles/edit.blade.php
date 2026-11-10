@extends('layouts.app')

@section('title', 'Редагувати профіль')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Редагування профілю #{{ $profile->id }}</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif
            <form action="{{ route('profiles.update', $profile) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="user_id" class="form-label">Користувач (1:1)</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ (string) old('user_id', $profile->user_id) === (string) $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <x-form.input name="pen_name" label="Псевдонім" :value="$profile->pen_name" />
                <x-form.textarea name="biography" label="Біографія" :value="$profile->biography" rows="4" />
                <x-form.input name="posts_count" label="Кількість дописів" type="number" :value="$profile->posts_count" />
                <x-form.input name="rating" label="Рейтинг" type="number" step="0.1" :value="$profile->rating" />
                <button type="submit" class="btn btn-primary">Оновити</button>
                <a href="{{ route('profiles.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
