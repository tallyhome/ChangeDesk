@extends('superadmin.layout')
@section('title', 'Utilisateurs')
@section('content')
<div class="sa-top">
  <div>
    <h1>Utilisateurs</h1>
    <div class="text-muted">Clients et superadmins</div>
  </div>
  <a href="{{ route('superadmin.users.create') }}" class="btn btn-accent"><i class="fas fa-user-plus me-1"></i> Nouvel utilisateur</a>
</div>

<form class="sa-card p-3 mb-3" method="GET">
  <div class="row g-2 align-items-end">
    <div class="col-md-5"><label class="form-label small">Recherche</label><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Nom ou email"></div>
    <div class="col-md-3"><label class="form-label small">Rôle</label>
      <select name="role" class="form-select">
        <option value="">Tous</option>
        <option value="client" @selected(request('role')==='client')>Client</option>
        <option value="superadmin" @selected(request('role')==='superadmin')>Superadmin</option>
      </select>
    </div>
    <div class="col-md-2"><label class="form-label small">Statut</label>
      <select name="status" class="form-select">
        <option value="">Tous</option>
        <option value="active" @selected(request('status')==='active')>Actifs</option>
        <option value="inactive" @selected(request('status')==='inactive')>Inactifs</option>
      </select>
    </div>
    <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filtrer</button></div>
  </div>
</form>

<div class="sa-card table-responsive">
<table class="table mb-0">
<thead><tr><th>Utilisateur</th><th>Rôle</th><th>Tenant</th><th>Statut</th><th></th></tr></thead>
<tbody>
@foreach($users as $user)
<tr>
  <td>
    <div class="fw-semibold">{{ $user->name }}</div>
    <div class="small text-muted">{{ $user->email }}</div>
  </td>
  <td><span class="badge text-bg-light">{{ $user->role }}</span></td>
  <td>{{ $user->tenant?->name ?? '—' }}</td>
  <td>
    @if($user->is_active)<span class="badge text-bg-success">Actif</span>
    @else<span class="badge text-bg-secondary">Inactif</span>@endif
  </td>
  <td class="text-end"><a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-outline-primary">Éditer</a></td>
</tr>
@endforeach
</tbody>
</table>
<div class="p-3">{{ $users->links() }}</div>
</div>
@endsection
