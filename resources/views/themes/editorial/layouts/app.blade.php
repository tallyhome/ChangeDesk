<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', $currentTenant->name ?? config('app.name')) — {{ $currentTenant->name ?? config('app.name') }}</title>
@include('partials.favicon')
<link rel="stylesheet" href="{{ asset('css/themes/editorial.css') }}?v={{ config('version.number') }}">
</head>
<body>
@php
  $gate = app(\App\Services\PlanGate::class);
  $t = $currentTenant ?? null;
  $brand = $t->name ?? config('app.name');
  $central = rtrim(config('app.url'), '/');
@endphp
<header class="ed-header">
  <div class="ed-wrap">
    <a class="ed-logo" href="{{ url('/') }}">{{ $brand }}</a>
    <nav>
      <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">{{ __('app.nav.home') }}</a>
      <a href="{{ route('changelog') }}" class="{{ request()->routeIs('changelog') ? 'active' : '' }}">{{ __('app.nav.changelog') }}</a>
      @if($gate->can($t, 'todolist'))
        <a href="{{ route('todolist') }}" class="{{ request()->routeIs('todolist') ? 'active' : '' }}">{{ __('app.nav.features') }}</a>
      @endif
      @if($gate->can($t, 'bugs'))
        <a href="{{ route('bug-report') }}" class="{{ request()->routeIs('bug-report*') ? 'active' : '' }}">{{ __('app.nav.bugs') }}</a>
      @endif
      @if($gate->can($t, 'wiki'))
        <a href="{{ route('wiki') }}" class="{{ request()->routeIs('wiki*') ? 'active' : '' }}">{{ __('app.nav.wiki') }}</a>
      @endif
      @guest
        <a class="ed-login" href="{{ $central }}/login">{{ __('app.nav.login') }}</a>
      @else
        <a class="ed-login" href="{{ $central }}/admin">{{ __('app.nav.admin_short') }}</a>
      @endguest
      @include('partials.lang-switcher', ['variant' => 'light'])
    </nav>
  </div>
</header>
<main class="ed-wrap ed-main">@yield('content')</main>
<footer class="ed-footer">
  <div class="ed-wrap">
    <span>&copy; {{ date('Y') }} {{ $brand }}</span>
    <span>
      <a href="{{ route('terms') }}">{{ __('app.footer.terms') }}</a> ·
      <a href="{{ route('privacy') }}">{{ __('app.footer.privacy') }}</a>
    </span>
  </div>
</footer>
@stack('scripts')
</body>
</html>
