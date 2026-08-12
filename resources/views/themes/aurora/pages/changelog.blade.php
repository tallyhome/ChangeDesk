@extends('themes.aurora.layouts.app')
@section('title', 'Historique des versions')
@section('content')
<span class="au-chip">Journal</span>
<h1 class="au-title" style="margin-top:.75rem">Historique des versions</h1>
<p class="au-lead">Chaque release, ancrée et accessible en un clic.</p>

<div class="au-grid">
  <div>
    @forelse($versions as $version)
      <article class="au-card au-version" id="version-{{ $version->id }}">
        <div class="au-version-head">
          <div>
            <span class="au-chip">v{{ $version->version_number }}</span>
            @if($version->description)
              <h2 style="margin:.55rem 0 0">{{ $version->description }}</h2>
            @else
              <h2 style="margin:.55rem 0 0">Version {{ $version->version_number }}</h2>
            @endif
          </div>
          <div class="au-muted" style="font-weight:600">{{ $version->release_date?->format('d/m/Y') }}</div>
        </div>
        <div class="au-prose">{!! $version->content !!}</div>
      </article>
    @empty
      <div class="au-card au-muted">Aucune version publiée.</div>
    @endforelse
  </div>

  <aside class="au-side" aria-label="Versions disponibles">
    <div class="au-side-head">Versions disponibles</div>
    <ul class="au-side-list">
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
        <li style="padding:1rem" class="au-muted">Aucune version</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection
