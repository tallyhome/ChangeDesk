@extends('themes.editorial.layouts.app')
@section('title', __('app.public.changelog_title'))
@section('content')
<p class="ed-kicker">{{ __('app.nav.changelog') }}</p>
<h1 class="ed-title">{{ __('app.public.changelog_title') }}</h1>
<p class="ed-lead">Ce qui change, clairement — une chronologie lisible et ancrée.</p>

<div class="ed-grid">
  <div class="ed-stack">
    @forelse($versions as $version)
      <article class="ed-version" id="version-{{ $version->id }}">
        <div class="ed-version-head">
          <h2>v{{ $version->version_number }}</h2>
          <div>
            <span class="ed-badge">{{ __('app.common.published') }}</span>
            <div class="ed-date" style="margin-top:.35rem;text-align:right">{{ \App\Support\Locale::formatDate($version->release_date) }}</div>
          </div>
        </div>
        @if($version->description)
          <p style="margin:.25rem 0 .75rem;font-weight:600">{{ $version->description }}</p>
        @endif
        <div class="ed-prose">{!! $version->content !!}</div>
      </article>
    @empty
      <div class="ed-card ed-muted">{{ __('app.common.empty') }}</div>
    @endforelse
  </div>

  <aside class="ed-side" aria-label="{{ __('app.common.available_versions') }}">
    <div class="ed-side-head">{{ __('app.common.available_versions') }}</div>
    <ul class="ed-side-list">
      @forelse($versions as $version)
        <li>
          <a href="#version-{{ $version->id }}">
            <div>
              <strong>v{{ $version->version_number }}</strong>
              <small>{{ \App\Support\Locale::formatDate($version->release_date) }}</small>
            </div>
            <span class="chev" aria-hidden="true">›</span>
          </a>
        </li>
      @empty
        <li style="padding:1rem" class="ed-muted">{{ __('app.common.empty') }}</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection
