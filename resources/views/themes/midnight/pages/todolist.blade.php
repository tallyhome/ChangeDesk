@extends('themes.midnight.layouts.app')
@section('title', 'Roadmap')
@section('content')
@php use App\Support\ThemeUi; @endphp
<h1 class="md-title">Notre roadmap</h1>
<p class="md-muted">Fonctionnalités à venir, en cours et terminées.</p>
@foreach($todoItems as $item)
  @php
    $progress = $item->progress ?? $item->completion_percentage ?? 0;
    $barColor = ThemeUi::progressColor($item->color ?? 'primary');
  @endphp
  <article class="md-card">
    <div style="display:flex;justify-content:space-between;gap:1rem">
      <div style="flex:1">
        <strong>{{ $item->title }}</strong>
        <div class="md-muted">{!! $item->description !!}</div>
        <div class="md-progress"><span style="width: {{ $progress }}%;background:{{ $barColor }}"></span></div>
      </div>
      <span class="md-badge">{{ ThemeUi::statusLabel($item->status) }} · {{ $progress }}%</span>
    </div>
  </article>
@endforeach
@endsection
