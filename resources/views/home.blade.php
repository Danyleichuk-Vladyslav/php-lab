@extends('layouts.app')

@section('title', 'Головна сторінка')

@section('content')
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
        <h1 class="display-5 fw-bold">Система управління інтернет-блогом</h1>
        <p class="col-md-8 fs-4">Лабораторна робота №8: Sanctum API, Policies, Blade-автентифікація, Swagger.</p>
        <a href="{{ route('posts.index') }}" class="btn btn-primary btn-lg">Перейти до дописів</a>
        @auth
            <a href="{{ route('posts.create') }}" class="btn btn-outline-primary btn-lg">Додати допис</a>
        @else
            <a href="{{ route('login') }}" class="btn btn-outline-primary btn-lg">Увійти</a>
        @endauth
    </div>
@endsection
