@extends('layouts.app')

@section('title', 'Профілі')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Профілі авторів</h1>
        <a href="{{ route('profiles.create') }}" class="btn btn-primary">Додати профіль</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Користувач</th>
                <th>Псевдонім</th>
                <th>Рейтинг</th>
                <th>Дописів</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->user->name ?? '—' }}</td>
                    <td>{{ $item->pen_name ?? '—' }}</td>
                    <td>{{ $item->rating }}</td>
                    <td>{{ $item->posts_count }}</td>
                    <td>
                        <a href="{{ route('profiles.show', $item) }}" class="btn btn-sm btn-info">Перегляд</a>
                        <a href="{{ route('profiles.edit', $item) }}" class="btn btn-sm btn-warning">Редаг.</a>
                        <form action="{{ route('profiles.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Дійсно видалити?');">
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
