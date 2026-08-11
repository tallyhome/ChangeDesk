@extends('superadmin.layout')

@section('title', $tenant->name)

@section('content')
<div class="mb-3">
    <a href="{{ route('superadmin.tenants.index') }}" class="btn btn-link px-0">&larr; Retour</a>
</div>

<h1 class="mb-3">{{ $tenant->name }}</h1>

<div class="card mb-4">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Slug</dt>
            <dd class="col-sm-9"><code>{{ $tenant->slug }}</code> — <a href="{{ $tenant->subdomainUrl() }}" target="_blank">{{ $tenant->subdomainUrl() }}</a></dd>
            <dt class="col-sm-3">Domaine custom</dt>
            <dd class="col-sm-9">{{ $tenant->custom_domain ?: '—' }} ({{ $tenant->domain_status }})</dd>
            <dt class="col-sm-3">Actif</dt>
            <dd class="col-sm-9">{{ $tenant->is_active ? 'Oui' : 'Non' }}</dd>
            <dt class="col-sm-3">Créé le</dt>
            <dd class="col-sm-9">{{ $tenant->created_at }}</dd>
        </dl>
    </div>
</div>

<div class="card">
    <div class="card-header">Utilisateurs</div>
    <ul class="list-group list-group-flush">
        @forelse($tenant->users as $user)
            <li class="list-group-item d-flex justify-content-between">
                <span>{{ $user->name }} &lt;{{ $user->email }}&gt;</span>
                <span class="badge text-bg-secondary">{{ $user->role }}</span>
            </li>
        @empty
            <li class="list-group-item text-muted">Aucun utilisateur</li>
        @endforelse
    </ul>
</div>
@endsection
