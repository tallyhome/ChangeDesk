@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('app.auth.register_title') }}</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label" for="name">{{ __('app.auth.your_name') }}</label>
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autofocus>
                            @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="email">{{ __('app.auth.email') }}</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required>
                            @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password">{{ __('app.auth.password') }}</label>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="password_confirmation">{{ __('app.auth.password_confirm') }}</label>
                            <input id="password_confirmation" type="password" class="form-control" name="password_confirmation" required>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label class="form-label" for="project_name">{{ __('app.auth.project_name') }}</label>
                            <input id="project_name" type="text" class="form-control @error('project_name') is-invalid @enderror" name="project_name" value="{{ old('project_name') }}" required>
                            @error('project_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label" for="slug">{{ __('app.auth.subdomain') }}</label>
                            <div class="input-group">
                                <input id="slug" type="text" class="form-control @error('slug') is-invalid @enderror" name="slug" value="{{ old('slug') }}" required pattern="[A-Za-z0-9\-]+">
                                <span class="input-group-text">.{{ config('tenancy.central_domain') }}</span>
                            </div>
                            @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            <div class="form-text">{{ __('app.auth.subdomain_help', ['domain' => config('tenancy.central_domain')]) }}</div>
                        </div>

                        <button type="submit" class="btn btn-primary">{{ __('app.auth.create_account_btn') }}</button>
                        <a href="{{ route('login') }}" class="btn btn-link">{{ __('app.auth.already') }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
