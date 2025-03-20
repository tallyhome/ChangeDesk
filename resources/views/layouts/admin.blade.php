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
        .admin-tabs {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            margin-bottom: 20px;
        }
        .admin-tabs .nav-link {
            color: #495057;
            border-radius: 0;
            padding: 12px 20px;
            font-weight: 500;
            border: none;
            border-bottom: 3px solid transparent;
            transition: all 0.2s ease;
        }
        .admin-tabs .nav-link:hover {
            color: #007bff;
            background-color: rgba(0, 123, 255, 0.05);
            border-bottom: 3px solid rgba(0, 123, 255, 0.3);
        }
        .admin-tabs .nav-link.active {
            color: #007bff;
            background-color: transparent;
            border-bottom: 3px solid #007bff;
        }
        .admin-tabs .nav-link i {
            margin-right: 8px;
        }
    </style>
    @yield('head')
    @stack('styles')
</head>
<body>
    <div id="app">
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
                            <a class="nav-link {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}" href="{{ route('admin.pages.index') }}">
                                <i class="fas fa-file-alt"></i> Pages
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
    <!-- Summernote -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    @yield('scripts')
    @stack('scripts')
</body>
</html>