<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', $currentTenant->name ?? 'ChanLog')</title>
@include('partials.favicon')
<link rel="stylesheet" href="{{ asset('css/themes/editorial.css') }}">
</head>
<body>
@php
  $gate = app(\App\Services\PlanGate::class);
  $t = $currentTenant ?? null;
  $brand = $t->name ?? 'ChanLog';
  $central = rtrim(config('app.url'), '/');
@endphp
<header class="ed-header">
  <div class="ed-wrap">
    <a class="ed-logo" href="{{ url('/') }}">{{ $brand }}</a>
    <nav>
      <a href="{{ url('/') }}">Accueil</a>
      <a href="{{ route('changelog') }}">Changelog</a>
      @if($gate->can($t, 'todolist'))<a href="{{ route('todolist') }}">Fonctionnalités</a>@endif
      @if($gate->can($t, 'bugs'))<a href="{{ route('bug-report') }}">Bugs</a>@endif
      @if($gate->can($t, 'wiki'))<a href="{{ route('wiki') }}">Wiki</a>@endif
      @guest<a href="{{ $central }}/login">Connexion</a>@else<a href="{{ $central }}/admin">Admin</a>@endguest
    </nav>
  </div>
</header>
<main class="ed-wrap ed-main">@yield('content')</main>
</body>
</html>
