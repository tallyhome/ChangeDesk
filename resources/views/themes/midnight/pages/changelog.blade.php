@extends('themes.midnight.layouts.app')
@section('title', 'Changelog')
@section('content')
<h1 class="md-title">Mises à jour</h1>
<p class="md-muted">Transparence et progression produit.</p>
@forelse($versions as $version)
  <article class="md-card">
    <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap">
      <div>
        <strong style="color:var(--md-accent)">v{{ $version->version_number }}</strong>
        <div class="md-muted">{{ $version->release_date?->format('d M Y') }}</div>
        <p>{{ $version->description }}</p>
        <div>{!! $version->content !!}</div>
      </div>
      <span class="md-badge"><i class="fas fa-check-circle"></i> Publié</span>
    </div>
  </article>
@empty
  <div class="md-card md-muted">Aucune version pour le moment.</div>
@endforelse
@endsection
