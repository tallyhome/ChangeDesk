@extends('themes.aurora.layouts.app')
@section('title', 'Fonctionnalités à venir')
@section('content')
<span class="au-chip">Roadmap</span>
<h1 class="au-title" style="margin-top:.75rem">Fonctionnalités à venir</h1>
<p class="au-lead">La progression produit, en verre et lumière.</p>

<div class="au-todo-grid">
  @forelse($todoItems as $item)
    @php $progress = $item->progress ?? $item->completion_percentage ?? 0; @endphp
    <article class="au-card">
      <h3 style="margin:0 0 .5rem;font-weight:800">{{ $item->title }}</h3>
      <div class="au-prose">{!! $item->description !!}</div>
      <div class="au-progress"><span style="width: {{ $progress }}%"></span></div>
      <div class="au-meta">
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
        <span class="au-chip">
          @if($item->status == 'pending') En attente
          @elseif($item->status == 'in_progress') En cours
          @elseif($item->status == 'completed') Terminé
          @else {{ $item->status }} @endif
          · {{ $progress }}%
        </span>
      </div>
    </article>
  @empty
    <div class="au-card au-muted">Aucune fonctionnalité pour le moment.</div>
  @endforelse
</div>
@endsection
