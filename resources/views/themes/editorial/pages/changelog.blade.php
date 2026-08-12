@extends('themes.editorial.layouts.app')
@section('title', 'Historique des versions')
@section('content')
<p class="ed-kicker">Journal des versions</p>
<h1 class="ed-title">Historique des versions</h1>
<p class="ed-lead">Ce qui change, clairement — une chronologie lisible et ancrée.</p>

<div class="ed-grid">
  <div class="ed-stack">
    @forelse($versions as $version)
      <article class="ed-version" id="version-{{ $version->id }}">
        <div class="ed-version-head">
          <h2>v{{ $version->version_number }}</h2>
          <div>
            <span class="ed-badge">Publié</span>
            <div class="ed-date" style="margin-top:.35rem;text-align:right">{{ $version->release_date?->format('d/m/Y') }}</div>
          </div>
        </div>
        @if($version->description)
          <p style="margin:.25rem 0 .75rem;font-weight:600">{{ $version->description }}</p>
        @endif
        <div class="ed-prose">{!! $version->content !!}</div>
      </article>
    @empty
      <div class="ed-card ed-muted">Aucune version publiée.</div>
    @endforelse
  </div>

  <aside class="ed-side" aria-label="Versions disponibles">
    <div class="ed-side-head">Versions disponibles</div>
    <ul class="ed-side-list">
      @forelse($versions as $version)
        <li>
          <a href="#version-{{ $version->id }}">
            <div>
              <strong>v{{ $version->version_number }}</strong>
              <small>{{ $version->release_date?->format('d/m/Y') }}</small>
            </div>
            <span class="chev" aria-hidden="true">›</span>
          </a>
        </li>
      @empty
        <li style="padding:1rem" class="ed-muted">Aucune version</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection
