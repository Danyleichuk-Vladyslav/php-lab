@extends('layouts.app')

@section('title', 'Редагувати тег')

@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white"><h2>Редагування тега #{{ $tag->id }}</h2></div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger">Виправте помилки у формі нижче.</div>
            @endif
            <form action="{{ route('tags.update', $tag) }}" method="POST">
                @csrf
                @method('PUT')
                <x-form.input name="name" label="Назва" :value="$tag->name" required />
                <x-form.input name="slug" label="Slug" :value="$tag->slug" />
                <x-form.input name="color" label="Колір" :value="$tag->color" />
                <button type="submit" class="btn btn-primary">Оновити</button>
                <a href="{{ route('tags.index') }}" class="btn btn-secondary">Скасувати</a>
            </form>
        </div>
    </div>
@endsection
