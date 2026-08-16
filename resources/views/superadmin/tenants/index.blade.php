@extends('superadmin.layout')
@section('title', __('app.superadmin.tenants'))
@section('content')
<h1 class="mb-4">{{ __('app.superadmin.tenants') }}</h1>
<div class="card"><div class="table-responsive">
<table class="table table-hover mb-0">
<thead><tr><th>Nom</th><th>Slug</th><th>Plan</th><th>Thème</th><th>Domaine</th><th>Statut</th><th></th></tr></thead>
<tbody>
@foreach($tenants as $tenant)
<tr>
<td>{{ $tenant->name }}</td>
<td><code>{{ $tenant->slug }}</code></td>
<td>{{ $tenant->plan?->name ?? '—' }}</td>
<td>{{ $tenant->visual_theme }}</td>
<td>{{ $tenant->custom_domain ?: '—' }} <small class="text-muted">({{ $tenant->domain_status }})</small></td>
<td>
  @if($tenant->suspended_at)<span class="badge text-bg-danger">Suspendu</span>
  @elseif($tenant->is_active)<span class="badge text-bg-success">Actif</span>
  @else<span class="badge text-bg-secondary">Inactif</span>@endif
</td>
<td class="text-end">
  <a class="btn btn-sm btn-outline-primary" href="{{ route('superadmin.tenants.show', $tenant) }}">Voir</a>
</td>
</tr>
@endforeach
</tbody></table></div>
<div class="card-body">{{ $tenants->links() }}</div></div>
@endsection
