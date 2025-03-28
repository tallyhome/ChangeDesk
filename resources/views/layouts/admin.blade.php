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
            transition: background-color 0.3s ease, color 0.3s ease;
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
<body>
    <div id="app">
        <script>
            // Initialiser le thème au chargement de la page
            document.addEventListener('DOMContentLoaded', () => {
                const theme = localStorage.getItem('theme') || 'light';
                document.documentElement.setAttribute('data-theme', theme);
                updateThemeIcon(theme);
            });

            // Fonction pour basculer le thème
            function toggleTheme() {
                const currentTheme = document.documentElement.getAttribute('data-theme');
                const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                document.documentElement.setAttribute('data-theme', newTheme);
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
        </script>
        @include('layouts.partials.admin-navbar')
        
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <ul class="nav admin-tabs">
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

                    </ul>
                </div>
            </div>
            
            <div class="row">
                <div class="col-12">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <!-- TinyMCE est chargé dans le fichier partials/tinymce.blade.php -->
    @yield('scripts')
    @stack('scripts')
</body>
</html>