@extends('layouts.app')

@section('title', 'Додати тег')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Створення тега</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif
            <form action="{{ route('tags.store') }}" method="POST">
                @csrf
                <x-form.input name="name" label="Назва" required />
                <x-form.input name="slug" label="Slug (необовʼязково)" />
                <x-form.input name="color" label="Колір (#hex)" type="text" placeholder="#3366ff" />
                <button type="submit" class="btn btn-success">Зберегти</button>
                <a href="{{ route('tags.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
