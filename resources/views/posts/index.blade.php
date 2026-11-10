@extends('layouts.app')

@section('title', 'Дописи блогу')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Дописи</h1>
        <a href="{{ route('posts.create') }}" class="btn btn-primary">Додати новий запис</a>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Назва</th>
                <th>Категорія</th>
                <th>Теги</th>
                <th>Автор</th>
                <th>Статус</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->category->name ?? 'Немає' }}</td>
                    <td>
                        @forelse ($item->tags as $tag)
                            <span class="badge bg-secondary">{{ $tag->name }}</span>
                        @empty
                            <span class="text-muted small">—</span>
                        @endforelse
                    </td>
                    <td>{{ $item->user->name ?? '—' }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <a href="{{ route('posts.show', $item) }}" class="btn btn-sm btn-info">Перегляд</a>
                        <a href="{{ route('posts.edit', $item) }}" class="btn btn-sm btn-warning">Редаг.</a>
                        <form action="{{ route('posts.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Дійсно видалити?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Видалити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Записів не знайдено.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $items->links('pagination::bootstrap-5') }}
@endsection
