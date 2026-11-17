@extends('layouts.app')

@section('title', 'Редагувати профіль')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Редагування профілю #{{ $profile->id }}</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif

            <p class="text-muted">
                Користувач (не змінюється): <strong>{{ $profile->user->name ?? '—' }}</strong>
            </p>
            <p class="text-muted small">
                Рейтинг: {{ $profile->rating }} · Дописів: {{ $profile->posts_count }}
                (тільки для перегляду)
            </p>

            <form action="{{ route('profiles.update', $profile) }}" method="POST">
                @csrf
                @method('PUT')
                <x-form.input name="pen_name" label="Псевдонім" :value="$profile->pen_name" />
                <x-form.textarea name="biography" label="Біографія" :value="$profile->biography" rows="4" />
                <button type="submit" class="btn btn-primary">Оновити</button>
                <a href="{{ route('profiles.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
