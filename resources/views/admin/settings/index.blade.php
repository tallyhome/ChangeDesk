@extends('layouts.admin')

@section('title', __('app.admin.external_links'))

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Liens externes</h1>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="external_link_text" class="form-label">Texte du lien dans le menu</label>
                            <input type="text" class="form-control" id="external_link_text" name="external_link_text"
                                   value="{{ old('external_link_text', $settings['external_link_text'] ?? '') }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="external_link_url" class="form-label">URL du lien externe</label>
                            <input type="url" class="form-control @error('external_link_url') is-invalid @enderror" id="external_link_url" name="external_link_url"
                                   value="{{ old('external_link_url', $settings['external_link_url'] ?? '') }}">
                            @error('external_link_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            <div class="form-text">Exemple: https://play.google.com/store/apps/details?id=…</div>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label me-3 mb-0">Afficher le lien externe dans le menu</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="external_link_enabled" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="external_link_enabled" 
                                       name="external_link_enabled" value="1"
                                       {{ old('external_link_enabled', $settings['external_link_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="app_store_url" class="form-label">URL de l'App Store</label>
                            <input type="url" class="form-control @error('app_store_url') is-invalid @enderror" id="app_store_url" name="app_store_url"
                                   value="{{ old('app_store_url', $settings['app_store_url'] ?? '') }}"
                                   placeholder="https://apps.apple.com/app/id…">
                            @error('app_store_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label me-3 mb-0">Afficher l'icône App Store dans le footer</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="app_store_enabled" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="app_store_enabled"
                                       name="app_store_enabled" value="1"
                                       {{ old('app_store_enabled', $settings['app_store_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="play_store_url" class="form-label">URL du Google Play Store</label>
                            <input type="url" class="form-control @error('play_store_url') is-invalid @enderror" id="play_store_url" name="play_store_url"
                                   value="{{ old('play_store_url', $settings['play_store_url'] ?? '') }}"
                                   placeholder="https://play.google.com/store/apps/details?id=…">
                            @error('play_store_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label me-3 mb-0">Afficher l'icône Play Store dans le footer</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="play_store_enabled" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="play_store_enabled"
                                       name="play_store_enabled" value="1"
                                       {{ old('play_store_enabled', $settings['play_store_enabled'] ?? '0') == '1' ? 'checked' : '' }}>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>{{ __('app.common.update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection