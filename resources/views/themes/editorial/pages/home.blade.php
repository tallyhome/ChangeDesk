@extends('themes.editorial.layouts.app')
@section('title', __('app.nav.home'))
@section('content')
<p class="ed-kicker">{{ __('app.nav.home') }}</p>
<h1 class="ed-title">{{ $currentTenant->name ?? 'Bienvenue' }}</h1>
<p class="ed-lead">Suivez les versions, la roadmap et les retours utilisateurs au même endroit.</p>
<div class="ed-card ed-prose">
  @if(isset($page) && $page)
    {!! $page->content !!}
  @else
    <p>Bienvenue sur notre site.</p>
  @endif
</div>
@endsection
