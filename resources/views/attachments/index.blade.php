@extends('layouts.app')

@section('title', 'Вкладення')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1>Вкладення</h1>
        <a href="{{ route('attachments.create') }}" class="btn btn-primary">Додати вкладення</a>
    </div>
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Допис</th>
                <th>Файл</th>
                <th>MIME</th>
                <th>Розмір</th>
                <th>Дії</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($items as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->post->title ?? '—' }}</td>
                    <td>{{ $item->file_name }}</td>
                    <td>{{ $item->mime_type }}</td>
                    <td>{{ $item->size_bytes }}</td>
                    <td>
                        <a href="{{ route('attachments.show', $item) }}" class="btn btn-sm btn-info">Перегляд</a>
                        <a href="{{ route('attachments.edit', $item) }}" class="btn btn-sm btn-warning">Редаг.</a>
                        <form action="{{ route('attachments.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Дійсно видалити?');">
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
