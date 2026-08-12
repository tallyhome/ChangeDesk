@extends('themes.editorial.layouts.app')
@section('title', 'Fonctionnalités à venir')
@section('content')
<p class="ed-kicker">Roadmap</p>
<h1 class="ed-title">Fonctionnalités à venir</h1>
<p class="ed-lead">Ce qui est prévu, en cours, ou déjà livré.</p>

<div class="ed-todo-grid">
  @forelse($todoItems as $item)
    @php $progress = $item->progress ?? $item->completion_percentage ?? 0; @endphp
    <article class="ed-card">
      <h3 style="margin:0 0 .5rem;font-family:var(--ed-display)">{{ $item->title }}</h3>
      <div class="ed-prose">{!! $item->description !!}</div>
      <div class="ed-progress"><span style="width: {{ $progress }}%"></span></div>
      <div class="ed-meta">
        <span>
          Date estimée :
          @if(is_string($item->expected_date))
            {{ $item->expected_date }}
          @elseif($item->expected_date)
            {{ $item->expected_date->format('d/m/Y') }}
          @else
            Non définie
          @endif
        </span>
        <span class="ed-badge">
          @if($item->status == 'pending') En attente
          @elseif($item->status == 'in_progress') En cours
          @elseif($item->status == 'completed') Terminé
          @else {{ $item->status }} @endif
          · {{ $progress }}%
        </span>
      </div>
    </article>
  @empty
    <div class="ed-card ed-muted">Aucune fonctionnalité pour le moment.</div>
  @endforelse
</div>
@endsection
