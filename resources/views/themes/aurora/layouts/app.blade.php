<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', $currentTenant->name ?? 'ChanLog') — {{ $currentTenant->name ?? 'ChanLog' }}</title>
@include('partials.favicon')
<link rel="stylesheet" href="{{ asset('css/themes/aurora.css') }}?v={{ config('version.number') }}">
</head>
<body>
@php
  $gate = app(\App\Services\PlanGate::class);
  $t = $currentTenant ?? null;
  $brand = $t->name ?? 'ChanLog';
  $central = rtrim(config('app.url'), '/');
@endphp
<div class="au-bg" aria-hidden="true"></div>
<div class="au-shell">
  <header class="au-top">
    <a class="au-brand" href="{{ url('/') }}">{{ $brand }}</a>
    <nav>
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">Accueil</a>
      <a href="{{ route('changelog') }}" class="{{ request()->routeIs('changelog') ? 'active' : '' }}">Changelog</a>
      @if($gate->can($t, 'todolist'))
        <a href="{{ route('todolist') }}" class="{{ request()->routeIs('todolist') ? 'active' : '' }}">Fonctionnalités</a>
      @endif
      @if($gate->can($t, 'bugs'))
        <a href="{{ route('bug-report') }}" class="{{ request()->routeIs('bug-report*') ? 'active' : '' }}">Bugs</a>
      @endif
      @if($gate->can($t, 'wiki'))
        <a href="{{ route('wiki') }}" class="{{ request()->routeIs('wiki*') ? 'active' : '' }}">Wiki</a>
      @endif
      @guest
        <a class="au-login" href="{{ $central }}/login">Connexion</a>
      @else
        <a class="au-login" href="{{ $central }}/admin">Admin</a>
      @endguest
    </nav>
  </header>
  <main class="au-main">@yield('content')</main>
  <footer class="au-footer">
    <div class="au-footer-inner">
      <span>&copy; {{ date('Y') }} {{ $brand }}</span>
      <span>
        <a href="{{ route('terms') }}">Conditions</a> ·
        <a href="{{ route('privacy') }}">Confidentialité</a>
      </span>
    </div>
  </footer>
</div>
@stack('scripts')
</body>
</html>
