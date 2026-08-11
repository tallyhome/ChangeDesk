<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', $currentTenant->name ?? 'ChanLog')</title>
@include('partials.favicon')
<link rel="stylesheet" href="{{ asset('css/themes/aurora.css') }}">
</head>
<body>
@php
  $gate = app(\App\Services\PlanGate::class);
  $t = $currentTenant ?? null;
  $brand = $t->name ?? 'ChanLog';
  $central = rtrim(config('app.url'), '/');
@endphp
<div class="au-bg"></div>
<header class="au-top">
  <a href="{{ url('/') }}" style="color:inherit;text-decoration:none;font-weight:700">{{ $brand }}</a>
  <nav>
    <a href="{{ url('/') }}">Accueil</a>
    <a href="{{ route('changelog') }}">Changelog</a>
    @if($gate->can($t,'todolist'))<a href="{{ route('todolist') }}">Fonctionnalités</a>@endif
    @if($gate->can($t,'bugs'))<a href="{{ route('bug-report') }}">Bugs</a>@endif
    @if($gate->can($t,'wiki'))<a href="{{ route('wiki') }}">Wiki</a>@endif
    @guest<a href="{{ $central }}/login">Connexion</a>@else<a href="{{ $central }}/admin">Admin</a>@endguest
  </nav>
</header>
<main class="au-main">@yield('content')</main>
</body>
</html>
