@extends('layouts.app')

@section('title', 'Коментарі')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Коментарі</h1>
        <a href="{{ route('comments.create') }}" class="btn btn-primary">Додати коментар</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Допис</th>
                <th>Автор</th>
                <th>Текст</th>
                <th>Статус</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->post->title ?? '—' }}</td>
                    <td>{{ $item->author_name ?? $item->user->name ?? 'Гість' }}</td>
                    <td>{{ \Illuminate\Support\Str::limit($item->body, 60) }}</td>
                    <td>{{ $item->status }}</td>
                    <td>
                        <a href="{{ route('comments.show', $item) }}" class="btn btn-sm btn-info">Перегляд</a>
                        <a href="{{ route('comments.edit', $item) }}" class="btn btn-sm btn-warning">Редаг.</a>
                        <form action="{{ route('comments.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Дійсно видалити?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Видалити</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center">Записів не знайдено.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $items->links('pagination::bootstrap-5') }}
@endsection
