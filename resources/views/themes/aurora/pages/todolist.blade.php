@extends('themes.aurora.layouts.app')
@section('title', 'Fonctionnalités à venir')
@section('content')
@php
  use App\Support\ThemeUi;
  $total = $todoItems->count();
  $inProgress = $todoItems->where('status', 'in_progress')->count();
  $done = $todoItems->whereIn('status', ['completed', 'done'])->count();
  $icons = ['fa-chart-line','fa-users','fa-bell','fa-rocket','fa-puzzle-piece','fa-bolt','fa-layer-group','fa-wand-magic-sparkles'];
@endphp

<h1 class="au-title">Fonctionnalités à venir</h1>

<div class="au-panel">
  <div class="au-panel-pad">
    <div class="au-stats">
      <span><i class="fas fa-list"></i> {{ $total }} fonctionnalité{{ $total > 1 ? 's' : '' }}</span>
      <span><i class="fas fa-circle-notch"></i> {{ $inProgress }} en cours</span>
      <span><i class="fas fa-check-circle"></i> {{ $done }} terminée{{ $done > 1 ? 's' : '' }}</span>
    </div>

    <div class="au-filters" id="auRoadFilters">
      <button type="button" class="au-filter active" data-filter="all">Toutes</button>
      <button type="button" class="au-filter" data-filter="in_progress">En cours</button>
      <button type="button" class="au-filter" data-filter="pending">En attente</button>
      <button type="button" class="au-filter" data-filter="completed">Terminées</button>
    </div>

    <div class="au-road-list">
      @forelse($todoItems as $i => $item)
        @php
          $progress = (int) ($item->progress ?? $item->completion_percentage ?? 0);
          $barColor = ThemeUi::progressColor($item->color ?? 'primary');
          $status = strtolower((string) $item->status);
          $filterKey = match (true) {
            in_array($status, ['completed', 'done'], true) => 'completed',
            in_array($status, ['pending', 'planned'], true) => 'pending',
            $status === 'in_progress' => 'in_progress',
            default => 'pending',
          };
          $statusClass = match ($filterKey) {
            'completed' => 'done',
            'in_progress' => 'progress',
            default => 'pending',
          };
          $icon = $icons[$i % count($icons)];
          $excerpt = \Illuminate\Support\Str::limit(trim(strip_tags((string) $item->description)), 90);
        @endphp
        <article class="au-road-item" data-status="{{ $filterKey }}">
          <div class="au-road-ico" aria-hidden="true"><i class="fas {{ $icon }}"></i></div>
          <div class="au-road-body">
            <strong>{{ $item->title }}</strong>
            @if($excerpt)<p>{{ $excerpt }}</p>@endif
          </div>
          <div class="au-road-prog">
            <span class="pct">{{ $progress }}%</span>
            <div class="bar"><i style="width:{{ $progress }}%;background:{{ $barColor }}"></i></div>
          </div>
          <span class="au-status {{ $statusClass }}">
            @if($statusClass === 'done')
              <i class="fas fa-check-circle"></i>
            @elseif($statusClass === 'progress')
              <span class="dot"></span>
            @else
              <i class="fas fa-clock"></i>
            @endif
            {{ ThemeUi::statusLabel($item->status) }}
          </span>
        </article>
      @empty
        <p class="au-muted" style="margin:0">Aucune fonctionnalité pour le moment.</p>
      @endforelse
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const filters = document.getElementById('auRoadFilters');
  if (!filters) return;
  const items = [...document.querySelectorAll('.au-road-item')];
  filters.addEventListener('click', (e) => {
    const btn = e.target.closest('.au-filter');
    if (!btn) return;
    filters.querySelectorAll('.au-filter').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    const key = btn.dataset.filter;
    items.forEach(item => {
      item.style.display = (key === 'all' || item.dataset.status === key) ? '' : 'none';
    });
  });
})();
</script>
@endpush
