@extends('layouts.app')

@section('title', 'Категорії')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Категорії</h1>
        <a href="{{ route('categories.create') }}" class="btn btn-primary">Додати категорію</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Slug</th>
                <th>Дописів</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->slug }}</td>
                    <td>{{ $item->posts_count }}</td>
                    <td>
                        <a href="{{ route('categories.show', $item) }}" class="btn btn-sm btn-info">Перегляд</a>
                        <a href="{{ route('categories.edit', $item) }}" class="btn btn-sm btn-warning">Редаг.</a>
                        <form action="{{ route('categories.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Дійсно видалити?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Видалити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" class="text-center">Записів не знайдено.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $items->links('pagination::bootstrap-5') }}
@endsection
