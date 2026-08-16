@extends('themes.editorial.layouts.app')
@section('title', __('app.public.todolist_title'))
@section('content')
@php use App\Support\ThemeUi; @endphp
<p class="ed-kicker">Roadmap</p>
<h1 class="ed-title">{{ __('app.public.todolist_title') }}</h1>
<p class="ed-lead">Ce qui est prévu, en cours, ou déjà livré.</p>

<div class="ed-todo-grid">
  @forelse($todoItems as $item)
    @php
      $progress = $item->progress ?? $item->completion_percentage ?? 0;
      $barColor = ThemeUi::progressColor($item->color ?? 'primary');
    @endphp
    <article class="ed-card">
      <h3 style="margin:0 0 .5rem;font-family:var(--ed-display)">{{ $item->title }}</h3>
      <div class="ed-prose">{!! $item->description !!}</div>
      <div class="ed-progress"><span style="width: {{ $progress }}%;background:{{ $barColor }}"></span></div>
      <div class="ed-meta">
        <span>
          {{ __('app.common.estimated_date') }} :
          @if(is_string($item->expected_date))
            {{ $item->expected_date }}
          @elseif($item->expected_date)
            {{ \App\Support\Locale::formatDate($item->expected_date) }}
          @else
            {{ __('app.common.undefined') }}
          @endif
        </span>
        <span class="ed-badge">{{ ThemeUi::statusLabel($item->status) }} · {{ $progress }}%</span>
      </div>
    </article>
  @empty
    <div class="ed-card ed-muted">{{ __('app.common.empty') }}</div>
  @endforelse
</div>
@endsection
