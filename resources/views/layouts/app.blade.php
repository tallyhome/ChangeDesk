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
        @include('layouts.navigation')

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
