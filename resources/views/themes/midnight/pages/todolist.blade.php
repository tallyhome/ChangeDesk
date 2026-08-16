@extends('themes.midnight.layouts.app')
@section('title', __('app.public.todolist_title'))
@section('content')
@php use App\Support\ThemeUi; @endphp
<h1 class="md-title">{{ __('app.public.todolist_title') }}</h1>
<p class="md-muted">{{ __('app.landing.mod_roadmap_only_text') }}</p>
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
