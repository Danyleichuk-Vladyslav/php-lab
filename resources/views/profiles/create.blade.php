@extends('layouts.app')

@section('title', 'Додати профіль')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Створення профілю (1:1 з User)</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif
            <form action="{{ route('profiles.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="user_id" class="form-label">Користувач без профілю</label>
                    <select name="user_id" id="user_id" class="form-select @error('user_id') is-invalid @enderror" required>
                        <option value="">-- Оберіть користувача --</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}" {{ (string) old('user_id') === (string) $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <x-form.input name="pen_name" label="Псевдонім" />
                <x-form.textarea name="biography" label="Біографія" rows="4" />
                <x-form.input name="posts_count" label="Кількість дописів" type="number" value="0" />
                <x-form.input name="rating" label="Рейтинг" type="number" step="0.1" value="0" />
                <button type="submit" class="btn btn-success">Зберегти</button>
                <a href="{{ route('profiles.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
