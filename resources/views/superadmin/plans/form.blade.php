@extends('superadmin.layout')
@section('title', $plan->exists ? 'Éditer plan' : 'Nouveau plan')
@section('content')
<div class="sa-top">
  <div>
    <h1>{{ $plan->exists ? 'Éditer '.$plan->name : 'Nouveau plan' }}</h1>
  </div>
  <a href="{{ route('superadmin.plans.index') }}" class="btn btn-outline-secondary">Retour</a>
</div>

<form method="POST" action="{{ $plan->exists ? route('superadmin.plans.update', $plan) : route('superadmin.plans.store') }}" class="sa-card p-4" style="max-width:820px">
  @csrf
  @if($plan->exists) @method('PUT') @endif

  <div class="row g-3">
    <div class="col-md-6">
      <label class="form-label fw-semibold">Nom</label>
      <input name="name" class="form-control" value="{{ old('name', $plan->name) }}" required>
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">Slug</label>
      <input name="slug" class="form-control" value="{{ old('slug', $plan->slug) }}" required>
    </div>
    <div class="col-md-4">
      <label class="form-label fw-semibold">Prix (€)</label>
      <input type="number" step="0.01" min="0" name="price_euros" class="form-control" value="{{ old('price_euros', $plan->price_cents ? $plan->price_cents/100 : 0) }}">
    </div>
    <div class="col-md-4">
      <label class="form-label fw-semibold">Intervalle</label>
      <select name="interval" class="form-select">
        <option value="month" @selected(old('interval', $plan->interval)==='month')>Mensuel</option>
        <option value="year" @selected(old('interval', $plan->interval)==='year')>Annuel</option>
      </select>
    </div>
    <div class="col-md-4">
      <label class="form-label fw-semibold">Ordre</label>
      <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $plan->sort_order ?? 0) }}">
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">Stripe Price ID</label>
      <input name="stripe_price_id" class="form-control" value="{{ old('stripe_price_id', $plan->stripe_price_id) }}">
    </div>
    <div class="col-md-6">
      <label class="form-label fw-semibold">PayPal Plan ID</label>
      <input name="paypal_plan_id" class="form-control" value="{{ old('paypal_plan_id', $plan->paypal_plan_id) }}">
    </div>
  </div>

  <hr class="my-4">
  <h2 class="h5 mb-3">Modules inclus</h2>
  <div class="row g-2">
    @foreach(['changelog'=>'Changelog','todolist'=>'Roadmap / Todo','bugs'=>'Bugs','wiki'=>'Wiki','pages'=>'Pages','stats'=>'Stats','custom_domain'=>'Domaine custom','branding'=>'Branding','priority_support'=>'Support prioritaire'] as $key=>$label)
      <div class="col-md-4">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" name="features[{{ $key }}]" value="1" id="f_{{ $key }}"
            @checked(old("features.$key", data_get($plan->features, $key, false)))
            @if($key==='changelog') disabled @endif>
          @if($key==='changelog')<input type="hidden" name="features[changelog]" value="1">@endif
          <label class="form-check-label" for="f_{{ $key }}">{{ $label }}</label>
        </div>
      </div>
    @endforeach
  </div>

  <h2 class="h5 mt-4 mb-3">Thèmes autorisés</h2>
  <div class="d-flex flex-wrap gap-3">
    @foreach(['classic','midnight','editorial','aurora'] as $theme)
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="themes[]" value="{{ $theme }}" id="t_{{ $theme }}"
          @checked(in_array($theme, old('themes', $plan->allowedThemes()), true))>
        <label class="form-check-label" for="t_{{ $theme }}">{{ ucfirst($theme) }}</label>
      </div>
    @endforeach
  </div>

  <div class="form-check form-switch mt-4">
    <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" @checked(old('is_active', $plan->is_active ?? true))>
    <label class="form-check-label" for="is_active">Plan actif (visible à la vente)</label>
  </div>

  <div class="mt-4">
    <button class="btn btn-accent">Enregistrer</button>
  </div>
</form>
@endsection
