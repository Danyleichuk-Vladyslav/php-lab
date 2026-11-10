<!DOCTYPE html>
<html lang="uk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Інтернет-блог Laravel')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="{{ route('home') }}">Інтернет-блог</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="mainNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.index') }}">Дописи</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('categories.index') }}">Категорії</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('tags.index') }}">Теги</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('comments.index') }}">Коментарі</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('attachments.index') }}">Вкладення</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('profiles.index') }}">Профілі</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('posts.create') }}">Додати допис</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
