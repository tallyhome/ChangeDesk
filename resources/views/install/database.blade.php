@extends('install.layout')
@section('step3', 'on')
@section('content')
<h1 class="h3 mb-3">Configuration</h1>
<form method="POST" action="{{ route('install.database.store') }}">
@csrf
<div class="row g-3">
  <div class="col-12"><label class="form-label">URL de l’application</label>
    <input name="app_url" class="form-control" value="{{ old('app_url', request()->getSchemeAndHttpHost()) }}" required placeholder="https://changelog.monsite.fr"></div>
  <div class="col-12"><label class="form-label">Domaine central (sans https)</label>
    <input name="central_domain" class="form-control" value="{{ old('central_domain', request()->getHost()) }}" required placeholder="changelog.monsite.fr"></div>
  <div class="col-md-8"><label class="form-label">DB Host</label><input name="db_host" class="form-control" value="{{ old('db_host','localhost') }}" required></div>
  <div class="col-md-4"><label class="form-label">Port</label><input name="db_port" class="form-control" value="{{ old('db_port','3306') }}" required></div>
  <div class="col-md-4"><label class="form-label">Base</label><input name="db_database" class="form-control" value="{{ old('db_database') }}" required></div>
  <div class="col-md-4"><label class="form-label">User</label><input name="db_username" class="form-control" value="{{ old('db_username') }}" required></div>
  <div class="col-md-4"><label class="form-label">Mot de passe</label><input type="password" name="db_password" class="form-control" value="{{ old('db_password') }}"></div>
  <div class="col-12"><hr><strong>Compte superadmin</strong></div>
  <div class="col-md-6"><label class="form-label">Nom</label><input name="admin_name" class="form-control" value="{{ old('admin_name','Super Admin') }}" required></div>
  <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="admin_email" class="form-control" value="{{ old('admin_email') }}" required></div>
  <div class="col-md-6"><label class="form-label">Mot de passe</label><input type="password" name="admin_password" class="form-control" required></div>
  <div class="col-md-6"><label class="form-label">Confirmation</label><input type="password" name="admin_password_confirmation" class="form-control" required></div>
</div>
<button class="btn btn-success mt-4">Installer ChanLog</button>
</form>
@endsection
