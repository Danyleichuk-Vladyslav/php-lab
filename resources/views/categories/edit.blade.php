@extends('layouts.app')

@section('title', 'Редагувати категорію')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Редагування категорії #{{ $category->id }}</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif
            <form action="{{ route('categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                <x-form.input name="name" label="Назва" :value="$category->name" required />
                <x-form.input name="slug" label="Slug" :value="$category->slug" />
                <x-form.textarea name="description" label="Опис" :value="$category->description" rows="4" />
                <button type="submit" class="btn btn-primary">Оновити</button>
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
