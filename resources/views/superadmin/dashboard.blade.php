@extends('superadmin.layout')
@section('title', __('app.superadmin.dashboard'))
@section('content')
<div class="sa-top">
  <div>
    <h1>{{ __('app.superadmin.dashboard') }}</h1>
    <div class="text-muted">Pilotage de Evolora en un coup d’œil</div>
  </div>
  <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-accent">Gérer les tenants</a>
</div>

<div class="row g-3 mb-4">
@foreach([
  [__('app.superadmin.tenants'), $stats['tenants'], 'fa-building', __('app.superadmin.tenants')],
  ['Actifs', $stats['active_tenants'], 'fa-circle-check', 'Non suspendus'],
  ['Clients', $stats['users'], 'fa-users', 'Comptes admin projet'],
  ['Entrées changelog', $stats['versions'], 'fa-code-branch', 'Versions publiées (tous tenants)'],
  ['Bugs', $stats['bugs'], 'fa-bug', 'Signalements cumulés'],
  ['Visites', $stats['visits'], 'fa-chart-line', 'Hits enregistrés'],
  ['Abonnements', $stats['subscriptions'], 'fa-receipt', 'Actifs / essai'],
  ['MRR', number_format($stats['mrr']/100, 2, ',', ' ').' €', 'fa-euro-sign', 'Revenu mensuel estimé'],
] as [$label,$value,$icon,$hint])
  <div class="col-6 col-lg-3">
    <div class="sa-card sa-stat h-100">
      <div class="label"><i class="fas {{ $icon }} me-1"></i> {{ $label }}</div>
      <div class="value">{{ $value }}</div>
      <div class="small text-muted mt-1">{{ $hint }}</div>
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
