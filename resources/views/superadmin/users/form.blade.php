@extends('superadmin.layout')
@section('title', $user->exists ? 'Éditer utilisateur' : 'Nouvel utilisateur')
@section('content')
@php
  $currentPlanId = old('plan_id', $user->tenant?->plan_id ?? $user->preferred_plan_id);
@endphp
<div class="sa-top">
  <div>
    <h1>{{ $user->exists ? $user->name : 'Nouvel utilisateur' }}</h1>
    <div class="text-muted">{{ $user->exists ? $user->email : 'Création d\'un compte plateforme' }}</div>
  </div>
  <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">{{ __('app.common.back') }}</a>
</div>

<div class="row g-4">
  <div class="col-lg-8">
    <form method="POST" action="{{ $user->exists ? route('superadmin.users.update', $user) : route('superadmin.users.store') }}" class="sa-card p-4">
      @csrf
      @if($user->exists) @method('PUT') @endif

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label fw-semibold">Nom complet</label>
          <input name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Email</label>
          <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Rôle</label>
          <select name="role" id="role" class="form-select" required>
            <option value="client" @selected(old('role', $user->role)==='client')>Client (admin projet)</option>
            <option value="superadmin" @selected(old('role', $user->role)==='superadmin')>Superadmin</option>
          </select>
        </div>
        <div class="col-md-6" id="plan_wrap">
          <label class="form-label fw-semibold">Plan d'abonnement</label>
          <select name="plan_id" class="form-select @error('plan_id') is-invalid @enderror">
            @foreach($plans as $plan)
              <option value="{{ $plan->id }}" @selected((string)$currentPlanId === (string)$plan->id)>
                {{ $plan->name }} — {{ $plan->formattedPrice() }}
              </option>
            @endforeach
          </select>
          <div class="form-text">Si aucun projet n'est rattaché, ce plan sera appliqué à la création du projet par le client.</div>
          @error('plan_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-12" id="tenant_wrap">
          <label class="form-label fw-semibold">Projet / tenant (optionnel)</label>
          <select name="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror">
            <option value="">— Aucun (le client créera son projet) —</option>
            @foreach($tenants as $t)
              <option value="{{ $t->id }}" @selected(old('tenant_id', $user->tenant_id)==$t->id)>{{ $t->name }} ({{ $t->slug }})</option>
            @endforeach
          </select>
          @error('tenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">{{ $user->exists ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe' }}</label>
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" {{ $user->exists ? '' : 'required' }}>
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Confirmation du mot de passe</label>
          <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password" {{ $user->exists ? '' : 'required' }}>
        </div>
      </div>

      <div class="form-check form-switch mt-3">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $user->is_active ?? true))>
        <label class="form-check-label" for="is_active">Compte actif</label>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-accent">{{ __('app.common.save') }}</button>
      </div>
    </form>
  </div>

  @if($user->exists)
  <div class="col-lg-4">
    <div class="sa-card p-3 mb-3">
      <div class="fw-bold mb-2">Actions rapides</div>
      <form method="POST" action="{{ route('superadmin.users.reset', $user) }}" class="mb-2">@csrf
        <button class="btn btn-outline-warning w-100">Générer un mot de passe temporaire</button>
      </form>
      <form method="POST" action="{{ route('superadmin.users.toggle', $user) }}">@csrf
        <button class="btn btn-outline-secondary w-100">{{ $user->is_active ? 'Désactiver le compte' : 'Activer le compte' }}</button>
      </form>
    </div>
    <div class="sa-card p-3">
      <div class="fw-bold mb-2">Infos</div>
      <div class="small text-muted">Créé : {{ $user->created_at }}</div>
      <div class="small text-muted">MAJ : {{ $user->updated_at }}</div>
      @if($user->tenant)
        <div class="small mt-2">Plan actuel : <strong>{{ $user->tenant->plan?->name ?? '—' }}</strong></div>
        <a class="d-inline-block mt-2" href="{{ route('superadmin.tenants.show', $user->tenant) }}">Voir le tenant →</a>
      @elseif($user->preferredPlan)
        <div class="small mt-2">Plan prévu : <strong>{{ $user->preferredPlan->name }}</strong> (en attente de projet)</div>
      @endif
    </div>
  </div>
  @endif
</div>

<script>
const role = document.getElementById('role');
const tenantWrap = document.getElementById('tenant_wrap');
const planWrap = document.getElementById('plan_wrap');
function sync(){
  const isSa = role.value === 'superadmin';
  tenantWrap.style.display = isSa ? 'none' : '';
  planWrap.style.display = isSa ? 'none' : '';
}
role.addEventListener('change', sync); sync();
</script>
@endsection
