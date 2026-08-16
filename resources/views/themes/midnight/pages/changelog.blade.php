@extends('themes.midnight.layouts.app')
@section('title', __('app.public.changelog_title'))
@section('content')
<h1 class="md-title">{{ __('app.public.changelog_title') }}</h1>
<p class="md-muted">{{ __('app.landing.mod_changelog_text') }}</p>
@forelse($versions as $version)
  <article class="md-card">
    <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap">
      <div>
        <strong style="color:var(--md-accent)">v{{ $version->version_number }}</strong>
        <div class="md-muted">{{ \App\Support\Locale::formatDate($version->release_date) }}</div>
        <p>{{ $version->description }}</p>
        <div>{!! $version->content !!}</div>
      </div>
      <span class="md-badge"><i class="fas fa-check-circle"></i> {{ __('app.common.published') }}</span>
    </div>
  </article>
@empty
  <div class="md-card md-muted">{{ __('app.common.empty') }}</div>
@endforelse
@endsection
