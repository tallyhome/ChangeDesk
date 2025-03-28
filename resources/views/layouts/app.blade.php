<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - MyVcard MyPredict</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --nav-bg: #0d6efd;
            --nav-text: rgba(255, 255, 255, 0.9);
            --footer-bg: #f8f9fa;
            --footer-text: #000000;
        }

        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #ffffff;
            --nav-bg: #0d47a1;
            --nav-text: rgba(255, 255, 255, 0.9);
            --footer-bg: #2d2d2d;
            --footer-text: #ffffff;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .navbar-dark {
            background-color: var(--nav-bg) !important;
        }

        footer {
            background-color: var(--footer-bg) !important;
            color: var(--footer-text) !important;
        }

        /* Styles existants */
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
        
        /* Styles responsives pour la navigation */
        @media (max-width: 991.98px) {
            .navbar-nav {
                padding: 1rem 0;
            }
            .navbar-nav .nav-item {
                margin-bottom: 0.5rem;
            }
            .dropdown-menu {
                border: none;
                background-color: rgba(0, 0, 0, 0.05);
                margin-top: 0.5rem;
            }
            .btn-light {
                width: 100%;
                margin: 0.5rem 0;
            }
            .d-flex.align-items-center.gap-3 {
                width: 100%;
                flex-direction: column;
            }
            .dropdown {
                width: 100%;
            }
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
            padding-bottom: 80px; /* Ajout d'un padding pour éviter que le footer ne chevauche le contenu */
        }
        
        main {
            flex: 1;
        }
        
        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            padding: 0.5rem 0 !important;
            background-color: #f8f9fa !important;
            font-size: 0.85rem;
            z-index: 1030;
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
                    <a href="{{ route('privacy') }}" class="text-decoration-none me-3">Politique de confidentialité</a>
                    <a href="{{ \App\Models\Setting::getValue('play_store_url', 'https://play.google.com/store/apps/details?id=com.myvcard.mypredict') }}" target="_blank" class="me-2">
                        <img src="{{ asset('images/google-play-badge.svg') }}" alt="Disponible sur Google Play" style="height: 40px;">
                    </a>
                    <a href="{{ \App\Models\Setting::getValue('app_store_url', 'https://apps.apple.com/app/myvcard-mypredict') }}" target="_blank">
                        <img src="{{ asset('images/app-store-badge.svg') }}" alt="Disponible sur l'App Store" style="height: 40px;">
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const theme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-theme', theme);
            updateThemeIcon(theme);
        });

        function toggleTheme() {
            const currentTheme = document.documentElement.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            updateThemeIcon(newTheme);
        }

        function updateThemeIcon(theme) {
            const icon = document.getElementById('theme-icon');
            if (icon) {
                icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
            }
        }
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TinyMCE est chargé dans le fichier partials/tinymce.blade.php -->
    @yield('scripts')
    @stack('scripts')
</body>
</html>
