@extends('themes.editorial.layouts.app')
@section('title', 'Changelog')
@section('content')
<div class="ed-kicker">Journal des versions</div>
<h1 class="ed-title">Ce qui change, clairement.</h1>
@forelse($versions as $version)
  <article class="ed-item">
    <div class="ed-date">{{ $version->release_date?->format('d M Y') }}</div>
    <div>
      <h2>v{{ $version->version_number }} — {{ $version->description }}</h2>
      <div>{!! $version->content !!}</div>
    </div>
  </article>
@empty
  <p>Aucune version publiée.</p>
@endforelse
@endsection
