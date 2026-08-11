@extends('layouts.admin')
@section('title', 'Administration')
@section('content')
@if(session('impersonator_id') || session()->has('impersonator_id'))
@endif
@if(session()->has('impersonator_id'))
<div class="alert alert-warning d-flex justify-content-between align-items-center">
  <span>Mode impersonation actif</span>
  <form method="POST" action="{{ route('impersonation.leave') }}">@csrf<button class="btn btn-sm btn-dark">Retour superadmin</button></form>
</div>
@endif

<div class="container">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
    <div>
      <h1 class="mb-1">Dashboard</h1>
      <p class="text-muted mb-0">{{ $tenant->name }} · Plan <strong>{{ $tenant->plan?->name ?? 'Free' }}</strong></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a class="btn btn-outline-primary" target="_blank" href="{{ $tenant->subdomainUrl() }}">Aperçu sous-domaine</a>
      @if($tenant->isCustomDomainVerified())
        <a class="btn btn-outline-success" target="_blank" href="{{ $tenant->publicBaseUrl() }}">Aperçu domaine custom</a>
      @endif
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">Domaine</div>
        <div class="fw-semibold">
          @switch($tenant->domain_status)
            @case('verified') <span class="text-success">Vérifié</span> @break
            @case('pending') <span class="text-warning">En attente</span> @break
            @default <span class="text-muted">Non configuré</span>
          @endswitch
        </div>
        <a href="{{ route('admin.domain.edit') }}" class="small">Configurer →</a>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">Thème public</div>
        <div class="fw-semibold">{{ $tenant->visual_theme }}</div>
        <a href="{{ route('admin.appearance.edit') }}" class="small">Apparence →</a>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">Facturation</div>
        <div class="fw-semibold">{{ $tenant->plan?->formattedPrice() ?? 'Gratuit' }}</div>
        <a href="{{ route('admin.billing.index') }}" class="small">Gérer →</a>
      </div></div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">Onboarding</div>
    <ul class="list-group list-group-flush">
      @foreach($checklist as $item)
        <li class="list-group-item d-flex justify-content-between">
          <span>{{ $item['label'] }}</span>
          @if($item['done'])
            <span class="badge text-bg-success">OK</span>
          @else
            <a href="{{ $item['url'] }}" class="btn btn-sm btn-outline-primary">Faire</a>
          @endif
        </li>
      @endforeach
    </ul>
  </div>

  <div class="card">
    <div class="card-header">Pages</div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead><tr><th>Titre</th><th>Modifié</th><th></th></tr></thead>
        <tbody>
        @foreach($pages as $page)
          <tr>
            <td>{{ $page->title }}</td>
            <td>{{ $page->updated_at?->format('d/m/Y H:i') }}</td>
            <td><a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-primary">Éditer</a></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
