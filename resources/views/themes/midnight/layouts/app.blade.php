<!DOCTYPE html>
<html lang="fr" data-theme="midnight">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', $currentTenant->name ?? 'ChanLog')</title>
@include('partials.favicon')
<link rel="stylesheet" href="{{ asset('css/themes/midnight.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
@php
  $gate = app(\App\Services\PlanGate::class);
  $t = $currentTenant ?? null;
  $brand = $t->name ?? 'ChanLog';
  $central = rtrim(config('app.url'), '/');
@endphp
<div class="md-shell">
  <aside class="md-side">
    <div class="md-brand"><span class="md-mark"></span> {{ $brand }}</div>
    <nav>
      <a class="{{ request()->routeIs('home') ? 'active' : '' }}" href="{{ url('/') }}"><i class="fas fa-home"></i> Accueil</a>
      <a class="{{ request()->routeIs('changelog') ? 'active' : '' }}" href="{{ route('changelog') }}"><i class="fas fa-stream"></i> Changelog</a>
      @if($gate->can($t, 'todolist'))
      <a class="{{ request()->routeIs('todolist') ? 'active' : '' }}" href="{{ route('todolist') }}"><i class="fas fa-road"></i> Fonctionnalités</a>
      @endif
      @if($gate->can($t, 'bugs'))
      <a class="{{ request()->routeIs('bug-report*') ? 'active' : '' }}" href="{{ route('bug-report') }}"><i class="fas fa-bug"></i> Bugs</a>
      @endif
      @if($gate->can($t, 'wiki'))
      <a class="{{ request()->routeIs('wiki*') ? 'active' : '' }}" href="{{ route('wiki') }}"><i class="fas fa-book"></i> Wiki</a>
      @endif
      @guest
      <a href="{{ $central }}/login"><i class="fas fa-sign-in-alt"></i> Connexion</a>
      @else
      <a href="{{ $central }}/admin"><i class="fas fa-cog"></i> Admin</a>
      @endguest
    </nav>
  </aside>
  <main class="md-main">@yield('content')</main>
</div>
</body>
</html>
