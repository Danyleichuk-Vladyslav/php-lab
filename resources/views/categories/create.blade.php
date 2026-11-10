@extends('layouts.app')

@section('title', 'Додати категорію')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Створення категорії</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif
            <form action="{{ route('categories.store') }}" method="POST">
                @csrf
                <x-form.input name="name" label="Назва" required />
                <x-form.input name="slug" label="Slug (необовʼязково)" />
                <x-form.textarea name="description" label="Опис" rows="4" />
                <button type="submit" class="btn btn-success">Зберегти</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
