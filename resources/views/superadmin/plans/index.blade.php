@extends('superadmin.layout')
@section('title', 'Plans')
@section('content')
<div class="sa-top">
  <div>
    <h1>Plans d'abonnement</h1>
    <div class="text-muted">Créez et éditez Free, Pro, Business…</div>
  </div>
  <a href="{{ route('superadmin.plans.create') }}" class="btn btn-accent"><i class="fas fa-plus me-1"></i> Nouveau plan</a>
</div>
<div class="sa-card table-responsive">
<table class="table mb-0 align-middle">
<thead><tr><th>Nom</th><th>Slug</th><th>Prix</th><th>Thèmes</th><th>Actif</th><th></th></tr></thead>
<tbody>
@foreach($plans as $plan)
<tr>
  <td class="fw-semibold">{{ $plan->name }}</td>
  <td><code>{{ $plan->slug }}</code></td>
  <td>{{ $plan->formattedPrice() }}</td>
  <td class="small">{{ implode(', ', $plan->allowedThemes()) }}</td>
  <td>{{ $plan->is_active ? 'Oui' : 'Non' }}</td>
  <td class="text-end">
    <a href="{{ route('superadmin.plans.edit', $plan) }}" class="btn btn-sm btn-outline-primary">Éditer</a>
    <form class="d-inline" method="POST" action="{{ route('superadmin.plans.destroy', $plan) }}" onsubmit="return confirm('Supprimer ce plan ?')">
      @csrf @method('DELETE')
      <button class="btn btn-sm btn-outline-danger">Suppr.</button>
    </form>
  </td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection
