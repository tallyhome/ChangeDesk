@extends('layouts.admin')

@section('title', 'Liens externes')

@section('content')
<div class="container">
    <h1 class="mb-4">Liens externes</h1>
    
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <div class="card">
        <div class="card-header">
            <h5>Liens externes</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="external_link_text" class="form-label">Texte du lien dans le menu</label>
                    <input type="text" class="form-control" id="external_link_text" name="external_link_text" 
                           value="{{ $settings['external_link_text']->value ?? '' }}">
                </div>
                
                <div class="mb-3">
                    <label for="external_link_url" class="form-label">URL du lien externe</label>
                    <input type="url" class="form-control" id="external_link_url" name="external_link_url" 
                           value="{{ $settings['external_link_url']->value ?? '' }}">
                    <div class="form-text">Exemple: https://myvcard.fr/</div>
                </div>
                
                <div class="mb-3">
                    <label for="app_store_url" class="form-label">URL de l'App Store</label>
                    <input type="url" class="form-control" id="app_store_url" name="app_store_url" 
                           value="{{ $settings['app_store_url']->value ?? '' }}">
                </div>
                
                <div class="mb-3">
                    <label for="play_store_url" class="form-label">URL du Google Play Store</label>
                    <input type="url" class="form-control" id="play_store_url" name="play_store_url" 
                           value="{{ $settings['play_store_url']->value ?? '' }}">
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection