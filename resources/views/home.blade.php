@extends('layouts.app')

@section('title', 'Головна сторінка')

@section('content')
    <div class="p-5 mb-4 bg-light rounded-3 shadow-sm">
        <h1 class="display-5 fw-bold">Система управління інтернет-блогом</h1>
        <p class="col-md-8 fs-4">Лабораторна робота №7: шаблонізатор Blade, SSR, форми та CRUD.</p>
        <a href="{{ route('posts.index') }}" class="btn btn-primary btn-lg">Перейти до дописів</a>
        <a href="{{ route('posts.create') }}" class="btn btn-outline-primary btn-lg">Додати допис</a>
    </div>
@endsection
