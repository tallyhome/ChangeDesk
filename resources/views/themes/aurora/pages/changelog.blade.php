@extends('themes.aurora.layouts.app')
@section('title', 'Changelog')
@section('content')
<h1 class="au-title">Changelog</h1>
@forelse($versions as $version)
  <article class="au-card">
    <span class="au-chip">v{{ $version->version_number }}</span>
    <h2 style="margin:.6rem 0">{{ $version->description }}</h2>
    <div class="au-muted">{{ $version->release_date?->format('d/m/Y') }}</div>
    <div style="margin-top:.8rem">{!! $version->content !!}</div>
  </article>
@empty
  <div class="au-card au-muted">Aucune version.</div>
@endforelse
@endsection
