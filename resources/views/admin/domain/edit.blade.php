@extends('layouts.admin')

@section('title', __('app.admin.domain'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>{{ __('app.admin.domain') }}</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">URL gratuite</h5>
            <p class="mb-1">
                <a href="{{ $tenant->subdomainUrl() }}" target="_blank" rel="noopener">{{ $tenant->subdomainUrl() }}</a>
            </p>
            <p class="text-muted small mb-0">Disponible dès que le DNS wildcard <code>*.{{ $centralDomain }}</code> pointe vers ce serveur (à configurer une fois chez l’hébergeur).</p>
            <div class="alert alert-warning mt-3 mb-0 small">
                Si le lien affiche «&nbsp;site inaccessible&nbsp;», le sous-domaine n’arrive pas jusqu’à l’application.
                Il faut un enregistrement DNS <strong>wildcard</strong> <code>*.{{ $centralDomain }}</code> (A ou CNAME)
                et, idéalement, un certificat SSL wildcard — ce n’est pas un réglage dans ChanLog.
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.domain.update') }}">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label class="form-label" for="name">Nom du projet</label>
                    <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $tenant->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="slug">Sous-domaine</label>
                    <div class="input-group">
                        <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $tenant->slug) }}" required>
                        <span class="input-group-text">.{{ $centralDomain }}</span>
                    </div>
                    @error('slug')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label" for="custom_domain">Domaine personnalisé (prioritaire)</label>
                    <input id="custom_domain" name="custom_domain" type="text" class="form-control @error('custom_domain') is-invalid @enderror"
                           value="{{ old('custom_domain', $tenant->custom_domain) }}"
                           placeholder="changelog.monsite.fr">
                    @error('custom_domain')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <div class="form-text">
                        {{ __('app.common.status') }} :
                        <strong>
                            @switch($tenant->domain_status)
                                @case('verified') Vérifié @break
                                @case('pending') En attente de vérification @break
                                @default Aucun
                            @endswitch
                        </strong>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">{{ __('app.common.save') }}</button>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Configuration DNS (domaine custom)</h5>
            <ol class="mb-3">
                <li>Créez un enregistrement <code>CNAME</code> :
                    <br><code>{{ $tenant->custom_domain ?: 'changelog.monsite.fr' }} → {{ $cnameTarget }}</code>
                </li>
                <li>Option de secours — enregistrement <code>TXT</code> :
                    <br><code>_changedesk-challenge.{{ $tenant->custom_domain ?: 'changelog.monsite.fr' }} = changedesk-verify={{ $tenant->domain_verification_token }}</code>
                </li>
                <li>Attendez la propagation DNS, puis cliquez sur vérifier.</li>
            </ol>

            <form method="POST" action="{{ route('admin.domain.verify') }}">
                @csrf
                <button type="submit" class="btn btn-success" @disabled(! $tenant->custom_domain)>
                    Vérifier le DNS
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
