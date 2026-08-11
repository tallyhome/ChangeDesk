@extends('layouts.admin')
@section('title', 'Créer mon projet')
@section('content')
<div class="container" style="max-width:640px">
  <h1 class="h3 mb-2">Créer votre projet</h1>
  <p class="text-muted mb-4">
    Votre compte est prêt
    @if($plan) — plan <strong>{{ $plan->name }}</strong>@endif.
    Choisissez le nom et le sous-domaine publics.
  </p>
  <form method="POST" action="{{ route('admin.onboarding.store') }}" class="card card-body">
    @csrf
    <div class="mb-3">
      <label class="form-label fw-semibold">Nom du projet</label>
      <input name="project_name" class="form-control @error('project_name') is-invalid @enderror" value="{{ old('project_name') }}" required>
      @error('project_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="mb-3">
      <label class="form-label fw-semibold">Sous-domaine</label>
      <div class="input-group">
        <input name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
        <span class="input-group-text">.{{ config('tenancy.central_domain') }}</span>
      </div>
      @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
    </div>
    <button class="btn btn-primary">Créer le projet</button>
  </form>
</div>
@endsection
