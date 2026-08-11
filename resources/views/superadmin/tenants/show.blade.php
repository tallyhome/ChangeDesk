@extends('superadmin.layout')
@section('title', $tenant->name)
@section('content')
<div class="mb-3"><a href="{{ route('superadmin.tenants.index') }}">&larr; Retour</a></div>
<div class="d-flex justify-content-between align-items-start mb-3 flex-wrap gap-2">
  <div>
    <h1 class="mb-1">{{ $tenant->name }}</h1>
    <p class="text-muted mb-0"><code>{{ $tenant->slug }}</code> · Plan {{ $tenant->plan?->name }} · Thème {{ $tenant->visual_theme }}</p>
  </div>
  <div class="d-flex flex-wrap gap-2">
    <a href="{{ route('superadmin.tenants.edit', $tenant) }}" class="btn btn-primary">Éditer</a>
    <form method="POST" action="{{ route('superadmin.tenants.impersonate', $tenant) }}">@csrf
      <button class="btn btn-warning">Se connecter comme client</button>
    </form>
    <form method="POST" action="{{ route('superadmin.tenants.toggle', $tenant) }}">@csrf
      <button class="btn btn-outline-secondary">{{ $tenant->is_active ? 'Désactiver' : 'Activer' }}</button>
    </form>
  </div>
</div>

@if($tenant->suspended_at)
  <div class="alert alert-danger">Suspendu : {{ $tenant->suspension_reason }}
    <form class="d-inline" method="POST" action="{{ route('superadmin.tenants.unsuspend', $tenant) }}">@csrf<button class="btn btn-sm btn-light ms-2">Lever</button></form>
  </div>
@else
  <form method="POST" action="{{ route('superadmin.tenants.suspend', $tenant) }}" class="card card-body mb-4">
    @csrf
    <label class="form-label">Suspendre avec motif</label>
    <div class="input-group">
      <input name="suspension_reason" class="form-control" required placeholder="Motif affiché au public">
      <button class="btn btn-danger">Suspendre</button>
    </div>
  </form>
@endif

<div class="row g-3">
  <div class="col-md-6">
    <div class="card"><div class="card-header">URLs</div><div class="card-body">
      <p><a target="_blank" href="{{ $tenant->subdomainUrl() }}">{{ $tenant->subdomainUrl() }}</a></p>
      <p>{{ $tenant->custom_domain ? $tenant->custom_domain.' ('.$tenant->domain_status.')' : 'Pas de domaine custom' }}</p>
    </div></div>
  </div>
  <div class="col-md-6">
    <div class="card"><div class="card-header">Users</div>
      <ul class="list-group list-group-flush">
        @foreach($tenant->users as $user)
          <li class="list-group-item d-flex justify-content-between">
            <span>{{ $user->name }} &lt;{{ $user->email }}&gt;</span>
            <a href="{{ route('superadmin.users.edit', $user) }}">Éditer</a>
          </li>
        @endforeach
      </ul>
    </div>
  </div>
</div>
@endsection
