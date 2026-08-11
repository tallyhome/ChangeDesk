@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Créer mon projet ChangeDesk</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="name">Votre nom</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">Email</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">Mot de passe</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">Confirmation</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label" for="project_name">Nom du projet</label>
                            <input id="project_name" type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" value="{{ old('project_name') }}" required>
                            @error('project_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="slug">Sous-domaine</label>
                            <div class="input-group">
                                <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug') }}" required pattern="[A-Za-z0-9\-]+">
                                <span class="input-group-text">.{{ config('tenancy.central_domain') }}</span>
                            </div>
                            @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <div class="form-text">Ex. : monapp → https://monapp.{{ config('tenancy.central_domain') }}</div>
                        </div>

                        <button type="submit" class="btn btn-primary">Créer mon compte</button>
                        <a href="{{ route('login') }}" class="btn btn-link">Déjà inscrit ?</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
