<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - MyVcard MyPredict</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .navbar-dark .navbar-nav .nav-link {
            color: rgba(255, 255, 255, 0.9);
            background-color: transparent !important;
            box-shadow: none !important;
            outline: none !important;
            padding: 8px 15px;
            margin: 0 2px;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        .navbar-dark .navbar-nav .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1) !important;
            transform: translateY(-2px);
        }
        .navbar-dark .navbar-nav .nav-link.active {
            font-weight: bold;
            color: #fff;
            background-color: rgba(255, 255, 255, 0.15) !important;
            box-shadow: none !important;
            outline: none !important;
        }
        .navbar-dark .navbar-nav .nav-link:focus {
            color: #fff;
            background-color: transparent !important;
            box-shadow: none !important;
            outline: none !important;
        }
        .navbar-dark .navbar-nav .nav-link:active {
            background-color: rgba(255, 255, 255, 0.2) !important;
            transform: translateY(0);
            box-shadow: none !important;
            outline: none !important;
        }
        
        /* Style pour un pied de page plus compact et toujours en bas */
        html, body {
            height: 100%;
            margin: 0;
        }
        
        #app {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        
        main {
            flex: 1;
        }
        
        footer {
            padding: 0.5rem 0 !important;
            background-color: #f8f9fa !important;
            font-size: 0.85rem;
        }

        /* Style pour le menu déroulant Admin */
        .dropdown-menu {
            z-index: 1050 !important;
            position: absolute !important;
        }
    </style>
    @yield('head')
    @stack('styles')
</head>
<body>
    <div id="app">
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="{{ route('home') }}">MyVcard MyPredict ChanLog</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('changelog') ? 'active' : '' }}" href="{{ route('changelog') }}">Changelog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('todolist') ? 'active' : '' }}" href="{{ route('todolist') }}">Prochaines fonctionnalités</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('bug-report') ? 'active' : '' }}" href="{{ route('bug-report') }}">Signaler un bug</a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        @auth
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Admin
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.pages.index') }}">Pages</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.changelog') }}">Changelog</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.todolist') }}">Prochaines fonctionnalités</a></li>
                                    <li><a class="dropdown-item" href="{{ route('admin.bug_reports') }}">Rapports de bugs</a></li>
                                </ul>
                            </li>
                            <li class="nav-item">
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-link nav-link">Déconnexion</button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">Connexion</a>
                            </li>
                        @endauth
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>

    <footer>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0">&copy; {{ date('Y') }} MyVcard MyPredict. Tous droits réservés. <span class="text-muted">v{{ $appVersion }}</span></p>
                </div>
                <div class="col-md-6 text-md-end">
                    <a href="{{ route('terms') }}" class="text-decoration-none me-3">Conditions d'utilisation</a>
                    <a href="{{ route('privacy') }}" class="text-decoration-none">Politique de confidentialité</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TinyMCE est chargé dans le fichier partials/tinymce.blade.php -->
    @yield('scripts')
    @stack('scripts')
</body>
</html>
