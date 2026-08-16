@extends('themes.aurora.layouts.app')
@section('title', __('app.nav.home'))
@section('content')
<span class="au-chip">{{ __('app.nav.home') }}</span>
<h1 class="au-title" style="margin-top:.75rem">{{ $currentTenant->name ?? 'Bienvenue' }}</h1>
<p class="au-lead">Changelog, roadmap et bugs — une vitrine produit claire, moderne et vivante.</p>
<div class="au-panel">
  <div class="au-panel-pad au-prose" style="color:#e2e8f0">
    @if(isset($page) && $page)
      {!! $page->content !!}
    @else
      <p>Bienvenue sur notre site.</p>
    @endif
  </div>
</div>
@endsection