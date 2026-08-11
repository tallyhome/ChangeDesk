@extends('superadmin.layout')
@section('title', $user->exists ? 'Éditer utilisateur' : 'Nouvel utilisateur')
@section('content')
<div class="sa-top">
  <div>
    <h1>{{ $user->exists ? $user->name : 'Nouvel utilisateur' }}</h1>
    <div class="text-muted">{{ $user->exists ? $user->email : 'Création d\'un compte plateforme' }}</div>
  </div>
  <a href="{{ route('superadmin.users.index') }}" class="btn btn-outline-secondary">Retour</a>
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
        <div class="col-md-6" id="tenant_wrap">
          <label class="form-label fw-semibold">Tenant rattaché</label>
          <select name="tenant_id" class="form-select @error('tenant_id') is-invalid @enderror">
            <option value="">— Aucun —</option>
            @foreach($tenants as $t)
              <option value="{{ $t->id }}" @selected(old('tenant_id', $user->tenant_id)==$t->id)>{{ $t->name }} ({{ $t->slug }})</option>
            @endforeach
          </select>
          @error('tenant_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">{{ $user->exists ? 'Nouveau mot de passe (optionnel)' : 'Mot de passe' }}</label>
          <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" {{ $user->exists ? '' : 'required' }}>
          @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-md-6">
          <label class="form-label fw-semibold">Confirmation</label>
          <input type="password" name="password_confirmation" class="form-control" {{ $user->exists ? '' : 'required' }}>
        </div>
      </div>

      <div class="form-check form-switch mt-3">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $user->is_active ?? true))>
        <label class="form-check-label" for="is_active">Compte actif</label>
      </div>

      <div class="mt-4 d-flex gap-2">
        <button class="btn btn-accent">Enregistrer</button>
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
        <a class="d-inline-block mt-2" href="{{ route('superadmin.tenants.show', $user->tenant) }}">Voir le tenant →</a>
      @endif
    </div>
  </div>
  @endif
</div>

<script>
const role = document.getElementById('role');
const wrap = document.getElementById('tenant_wrap');
function sync(){ wrap.style.opacity = role.value === 'superadmin' ? '.45' : '1'; }
role.addEventListener('change', sync); sync();
</script>
@endsection
