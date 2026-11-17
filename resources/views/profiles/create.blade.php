@extends('layouts.app')

@section('title', 'Додати профіль')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Створення профілю (1:1 з User)</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif

            <p class="text-muted">
                Користувач: <strong>{{ auth()->user()->name }}</strong>
                (звʼязок 1:1 встановлюється автоматично)
            </p>
            <p class="text-muted small">Рейтинг і кількість дописів обчислюються системою, їх не можна задати вручну.</p>

            <form action="{{ route('profiles.store') }}" method="POST">
                @csrf
                <x-form.input name="pen_name" label="Псевдонім" />
                <x-form.textarea name="biography" label="Біографія" rows="4" />
                <button type="submit" class="btn btn-success">Зберегти</button>
                <a href="{{ route('profiles.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
