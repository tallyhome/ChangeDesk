@extends('superadmin.layout')
@section('title', 'Éditer '.$tenant->name)
@section('content')
<h1 class="mb-4">Éditer {{ $tenant->name }}</h1>
<form method="POST" action="{{ route('superadmin.tenants.update', $tenant) }}" class="card card-body" style="max-width:720px">
@csrf @method('PUT')
<div class="mb-3"><label class="form-label">Nom</label><input name="name" class="form-control" value="{{ old('name', $tenant->name) }}" required></div>
<div class="mb-3"><label class="form-label">Slug</label><input name="slug" class="form-control" value="{{ old('slug', $tenant->slug) }}" required></div>
<div class="mb-3"><label class="form-label">Domaine custom</label><input name="custom_domain" class="form-control" value="{{ old('custom_domain', $tenant->custom_domain) }}"></div>
<div class="mb-3"><label class="form-label">Statut domaine</label>
<select name="domain_status" class="form-select">
@foreach(['none','pending','verified'] as $s)
<option value="{{ $s }}" @selected(old('domain_status', $tenant->domain_status)===$s)>{{ $s }}</option>
@endforeach
</select></div>
<div class="mb-3"><label class="form-label">Thème</label>
<select name="visual_theme" class="form-select">
@foreach(\App\Models\Tenant::THEMES as $theme)
<option value="{{ $theme }}" @selected(old('visual_theme', $tenant->visual_theme)===$theme)>{{ $theme }}</option>
@endforeach
</select></div>
<div class="mb-3"><label class="form-label">Plan</label>
<select name="plan_id" class="form-select">
@foreach($plans as $plan)
<option value="{{ $plan->id }}" @selected(old('plan_id', $tenant->plan_id)==$plan->id)>{{ $plan->name }}</option>
@endforeach
</select></div>
<div class="mb-3"><label class="form-label">Couleur branding</label><input name="branding_primary" class="form-control" value="{{ old('branding_primary', $tenant->branding['primary'] ?? '') }}" placeholder="#0d9488"></div>
<div class="form-check mb-3"><input type="checkbox" class="form-check-input" name="is_active" value="1" @checked(old('is_active', $tenant->is_active))><label class="form-check-label">Actif</label></div>
<button class="btn btn-primary">{{ __('app.common.save') }}</button>
</form>
@endsection
