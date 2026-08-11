@extends('themes.midnight.layouts.app')
@section('title', 'Roadmap')
@section('content')
<h1 class="md-title">Notre roadmap</h1>
<p class="md-muted">Fonctionnalités à venir, en cours et terminées.</p>
@foreach($todoItems as $item)
  <article class="md-card">
    <div style="display:flex;justify-content:space-between;gap:1rem">
      <div style="flex:1">
        <strong>{{ $item->title }}</strong>
        <p class="md-muted">{{ $item->description }}</p>
        <div class="md-progress"><span style="width: {{ $item->progress ?? $item->completion_percentage ?? 0 }}%"></span></div>
      </div>
      <span class="md-badge">{{ $item->status ?? 'en cours' }} · {{ $item->progress ?? $item->completion_percentage ?? 0 }}%</span>
    </div>
  </article>
@endforeach
@endsection
