<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') — {{ $currentTenant->name ?? config('app.name') }}</title>
    @include('partials.favicon')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --bg-color:#ffffff; --text-color:#1e293b; --nav-bg:#0d9488; --nav-text:rgba(255,255,255,.95); --footer-bg:#f1f5f9; --footer-text:#475569; }
        body { background: var(--bg-color); color: var(--text-color); min-height: 100vh; display:flex; flex-direction:column; }
        .navbar { background: var(--nav-bg) !important; }
        .navbar-brand { font-weight: 700; }
        main { flex: 1; padding: 1.5rem 0 2.5rem; }
        footer { background: var(--footer-bg); color: var(--footer-text); padding: 1.25rem 0; margin-top: auto; font-size: .9rem; }
        footer a { color: inherit; }
    </style>
</head>
<body>
@include('layouts.navigation')
<main>@yield('content')</main>
<footer>
  <div class="container d-flex justify-content-between flex-wrap gap-2">
    <span>&copy; {{ date('Y') }} {{ $currentTenant->name ?? config('app.name') }}</span>
    <span>
      <a href="{{ route('terms') }}">{{ __('app.footer.terms') }}</a> ·
      <a href="{{ route('privacy') }}">{{ __('app.footer.privacy') }}</a>
    </span>
  </div>
</footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
