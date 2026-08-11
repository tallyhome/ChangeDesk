@extends('superadmin.layout')
@section('title', 'Dashboard')
@section('content')
<div class="sa-top">
  <div>
    <h1>Dashboard plateforme</h1>
    <div class="text-muted">Pilotage de ChanLog en un coup d’œil</div>
  </div>
  <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-accent">Gérer les tenants</a>
</div>

<div class="row g-3 mb-4">
@foreach([
  ['Tenants', $stats['tenants'], 'fa-building'],
  ['Actifs', $stats['active_tenants'], 'fa-circle-check'],
  ['Clients', $stats['users'], 'fa-users'],
  ['Versions', $stats['versions'], 'fa-code-branch'],
  ['Bugs', $stats['bugs'], 'fa-bug'],
  ['Visites', $stats['visits'], 'fa-chart-line'],
  ['Abonnements', $stats['subscriptions'], 'fa-receipt'],
  ['MRR', number_format($stats['mrr']/100, 2, ',', ' ').' €', 'fa-euro-sign'],
] as [$label,$value,$icon])
  <div class="col-6 col-lg-3">
    <div class="sa-card sa-stat h-100">
      <div class="label"><i class="fas {{ $icon }} me-1"></i> {{ $label }}</div>
      <div class="value">{{ $value }}</div>
    </div>
  </div>
@endforeach
</div>

<div class="row g-4">
  <div class="col-lg-6">
    <div class="sa-card">
      <div class="p-3 border-bottom fw-bold">Derniers tenants</div>
      <ul class="list-group list-group-flush">
        @foreach($recentTenants as $t)
          <li class="list-group-item d-flex justify-content-between align-items-center">
            <div>
              <a href="{{ route('superadmin.tenants.show', $t) }}" class="fw-semibold text-decoration-none">{{ $t->name }}</a>
              <div class="small text-muted"><code>{{ $t->slug }}</code></div>
            </div>
            <span class="badge text-bg-light">{{ $t->plan?->name ?? '—' }}</span>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
  <div class="col-lg-6">
    <div class="sa-card">
      <div class="p-3 border-bottom fw-bold">Paiements récents</div>
      <ul class="list-group list-group-flush">
        @forelse($recentPayments as $p)
          <li class="list-group-item d-flex justify-content-between">
            <span>{{ $p->tenant?->name }} · {{ $p->provider }}</span>
            <span>{{ number_format($p->amount_cents/100,2,',',' ') }} € <span class="badge text-bg-secondary">{{ $p->status }}</span></span>
          </li>
        @empty
          <li class="list-group-item text-muted">Aucun paiement</li>
        @endforelse
      </ul>
    </div>
  </div>
</div>
@endsection
