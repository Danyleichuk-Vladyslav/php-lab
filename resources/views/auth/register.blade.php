@extends('layouts.app')

@section('title', 'Реєстрація')

@section('content')
    <div class="card mx-auto shadow-sm" style="max-width: 450px;">
        <div class="card-header bg-white">
            <h3 class="mb-0">Реєстрація</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('register') }}" method="POST">
                @csrf
                <x-form.input name="name" label="Імʼя" required autofocus />
                <x-form.input name="email" label="Електронна пошта" type="email" required />
                <x-form.input name="password" label="Пароль" type="password" required />
                <x-form.input name="password_confirmation" label="Підтвердження пароля" type="password" required />
                <button type="submit" class="btn btn-success w-100">Зареєструватися</button>
            </form>
            <p class="mt-3 mb-0 text-center">
                Уже є акаунт? <a href="{{ route('login') }}">Увійти</a>
            </p>
        </div>
    </div>
@endsection
