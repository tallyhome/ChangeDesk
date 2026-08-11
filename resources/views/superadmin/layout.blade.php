<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title') — Superadmin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
    <div class="container">
        <a class="navbar-brand" href="{{ route('superadmin.tenants.index') }}">ChangeDesk Superadmin</a>
        <div class="navbar-nav me-auto">
            <a class="nav-link text-white" href="{{ route('superadmin.tenants.index') }}">Tenants</a>
            <a class="nav-link text-white" href="{{ route('superadmin.backups.index') }}">Backups</a>
        </div>
        <div class="d-flex">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="btn btn-outline-light btn-sm">Déconnexion</button>
            </form>
        </div>
    </div>
</nav>
<main class="container pb-5">
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @yield('content')
</main>
</body>
</html>
