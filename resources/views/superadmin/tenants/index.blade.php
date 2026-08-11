@extends('superadmin.layout')

@section('title', 'Tenants')

@section('content')
<h1 class="mb-4">Tenants</h1>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Tenants</div><div class="fs-3">{{ $stats['tenants'] }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Actifs</div><div class="fs-3">{{ $stats['active'] }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Clients</div><div class="fs-3">{{ $stats['clients'] }}</div></div></div></div>
    <div class="col-md-3"><div class="card"><div class="card-body"><div class="text-muted">Domaines vérifiés</div><div class="fs-3">{{ $stats['verified_domains'] }}</div></div></div></div>
</div>

<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Slug</th>
                    <th>Domaine custom</th>
                    <th>Statut DNS</th>
                    <th>Actif</th>
                    <th>Users</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach($tenants as $tenant)
                    <tr>
                        <td>{{ $tenant->name }}</td>
                        <td><code>{{ $tenant->slug }}</code></td>
                        <td>{{ $tenant->custom_domain ?: '—' }}</td>
                        <td>{{ $tenant->domain_status }}</td>
                        <td>{{ $tenant->is_active ? 'Oui' : 'Non' }}</td>
                        <td>{{ $tenant->users_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('superadmin.tenants.show', $tenant) }}" class="btn btn-sm btn-outline-primary">Voir</a>
                            <form action="{{ route('superadmin.tenants.toggle', $tenant) }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary">
                                    {{ $tenant->is_active ? 'Désactiver' : 'Activer' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="card-body">{{ $tenants->links() }}</div>
</div>
@endsection
