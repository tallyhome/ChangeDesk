<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Administration</title>

    <!-- Styles -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --bg-color: #ffffff;
            --text-color: #000000;
            --nav-bg: #343a40;
            --nav-text: rgba(255, 255, 255, 0.9);
            --tab-bg: #f8f9fa;
            --tab-border: #dee2e6;
            --tab-text: #495057;
            --tab-hover: #007bff;
        }

        [data-theme="dark"] {
            --bg-color: #1a1a1a;
            --text-color: #ffffff;
            --nav-bg: #121212;
            --nav-text: rgba(255, 255, 255, 0.9);
            --tab-bg: #2d2d2d;
            --tab-border: #404040;
            --tab-text: #e0e0e0;
            --tab-hover: #4da3ff;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            transition: all 0.3s ease-in-out;
            padding-top: 56px;
        }

        body.page-transition {
            opacity: 1;
            transition: opacity 0.15s ease-out;
        }

        body.page-transition.loading {
            opacity: 0.7;
            pointer-events: none;
        }

        .preload * {
            transition: none !important;
        }

        .navbar-dark {
            background-color: var(--nav-bg) !important;
        }

        .navbar-dark .navbar-nav .nav-link {
            color: var(--nav-text);
        }

        .admin-tabs {
            background-color: var(--tab-bg);
            border-bottom: 1px solid var(--tab-border);
            margin-bottom: 20px;
        }
        .admin-tabs .nav-link {
            color: var(--tab-text);
            border-radius: 0;
            padding: 12px 20px;
            font-weight: 500;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .admin-tabs .nav-link:hover {
            color: var(--tab-hover);
            background-color: rgba(0, 123, 255, 0.05);
            border-bottom: 3px solid rgba(0, 123, 255, 0.3);
        }
        .admin-tabs .nav-link.active {
            color: var(--tab-hover);
            background-color: transparent;
            border-bottom: 3px solid var(--tab-hover);
        }
        .admin-tabs .nav-link i {
            margin-right: 8px;
        }

        /* Styles pour l'icône du thème */
        #theme-icon {
            font-size: 1.2rem;
            color: var(--nav-text) !important;
            transition: all 0.3s ease;
            display: inline-block;
            text-shadow: 0 0 5px rgba(255, 255, 255, 0.2);
        }
        
        #theme-icon:hover {
            transform: rotate(360deg);
            color: var(--nav-text) !important;
            text-shadow: 0 0 8px rgba(255, 255, 255, 0.4);
        }
    </style>
    @yield('head')
    @stack('styles')
</head>
<body class="{{ request()->is('*') ? 'page-transition' : '' }}">
    <script>
    // Appliquer immédiatement le thème sombre si nécessaire
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'dark' || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
        document.documentElement.setAttribute('data-theme', 'dark');
        document.documentElement.setAttribute('data-bs-theme', 'dark');
        document.body.setAttribute('data-bs-theme', 'dark');
    } else {
        document.documentElement.setAttribute('data-theme', 'light');
        document.documentElement.setAttribute('data-bs-theme', 'light');
        document.body.setAttribute('data-bs-theme', 'light');
    }
    </script>
    <div id="app">
        <script>
            // Fonction pour basculer le thème
            function toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
                document.documentElement.setAttribute('data-bs-theme', newTheme);
                document.body.setAttribute('data-bs-theme', newTheme);
                localStorage.setItem('theme', newTheme);
                updateThemeIcon(newTheme);
            }

            // Fonction pour mettre à jour l'icône du thème
            function updateThemeIcon(theme) {
                const icon = document.getElementById('theme-icon');
                if (icon) {
                    icon.className = theme === 'dark' ? 'fas fa-sun' : 'fas fa-moon';
                }
            }

            // Initialiser le thème au chargement de la page
            document.addEventListener('DOMContentLoaded', () => {
                const theme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', theme);
                document.documentElement.setAttribute('data-bs-theme', theme);
                document.body.setAttribute('data-bs-theme', theme);
                updateThemeIcon(theme);

                // Gérer les transitions de page
                const links = document.querySelectorAll('a[href]:not([target="_blank"])');
                links.forEach(link => {
                    link.addEventListener('click', (e) => {
                        if (link.href && link.href.startsWith(window.location.origin)) {
                            e.preventDefault();
                            document.body.style.transition = 'opacity 0.15s ease-out';
                            document.body.style.opacity = '0.7';
                            document.body.style.pointerEvents = 'none';
                            requestAnimationFrame(() => {
                                window.location.href = link.href;
                            });
                        }
                    });
                });

                // Réinitialiser l'opacité après le chargement de la page
                window.addEventListener('pageshow', (event) => {
                    if (event.persisted) {
                        document.body.style.opacity = '1';
                        document.body.style.pointerEvents = 'auto';
                    }
                });
            });
        </script>
        @include('layouts.partials.admin-navbar')
        
        <div class="container-fluid" style="margin-top: 56px;">
            <div class="row">
                <div class="col-12">
                    <ul class="nav admin-tabs" style="z-index: 1000; position: fixed; top: 56px; left: 0; right: 0; margin: 0; padding: 5px 20px; background-color: var(--tab-bg); box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.changelog*') ? 'active' : '' }}" href="{{ route('admin.changelog') }}">
                                <i class="fas fa-history"></i> Changelog
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.todolist*') ? 'active' : '' }}" href="{{ route('admin.todolist') }}">
                                <i class="fas fa-tasks"></i> Fonctionnalités à venir
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.bug_reports*') ? 'active' : '' }}" href="{{ route('admin.bug_reports') }}">
                                <i class="fas fa-bug"></i> Rapports de bugs
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                                <i class="fas fa-cog"></i> Liens externes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.wiki.*') ? 'active' : '' }}" href="{{ route('admin.wiki.index') }}">
                                <i class="fas fa-book"></i> Wiki
                            </a>
                        </li>

                    </ul>
                </div>
            </div>
            
            <div class="row" style="margin-top: 20px;">
                <div class="col-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.tinymce')
    @yield('scripts')
    @stack('scripts')
</body>
</html>