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
            <form action="{{ route('admin.settings.update') }}" method="POST" id="settingsForm">
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
                
                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input toggle-setting" type="checkbox" id="external_link_active" name="external_link_active" value="1" data-key="external_link_active" {{ ($settings['external_link_active']->value ?? '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="external_link_active">Afficher le lien externe dans le menu</label>
                </div>
                
                <div class="mb-3">
                    <label for="app_store_url" class="form-label">URL de l'App Store</label>
                    <input type="url" class="form-control" id="app_store_url" name="app_store_url" 
                           value="{{ $settings['app_store_url']->value ?? '' }}">
                </div>
                
                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input toggle-setting" type="checkbox" id="app_store_active" name="app_store_active" value="1" data-key="app_store_active" {{ ($settings['app_store_active']->value ?? '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="app_store_active">Afficher l'icône App Store dans le footer</label>
                </div>
                
                <div class="mb-3">
                    <label for="play_store_url" class="form-label">URL du Google Play Store</label>
                    <input type="url" class="form-control" id="play_store_url" name="play_store_url" 
                           value="{{ $settings['play_store_url']->value ?? '' }}">
                </div>
                
                <div class="mb-3 form-check form-switch">
                    <input class="form-check-input toggle-setting" type="checkbox" id="play_store_active" name="play_store_active" value="1" data-key="play_store_active" {{ ($settings['play_store_active']->value ?? '1') == '1' ? 'checked' : '' }}>
                    <label class="form-check-label" for="play_store_active">Afficher l'icône Play Store dans le footer</label>
                </div>
                
                <button type="submit" class="btn btn-primary">Enregistrer</button>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Ajouter le token CSRF à toutes les requêtes AJAX
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        // Gérer les interrupteurs d'activation/désactivation
        $('.toggle-setting').on('change', function() {
            const key = $(this).data('key');
            const value = this.checked ? '1' : '0';
            
            // Afficher un indicateur de chargement
            const label = $(this).next('label');
            const originalText = label.text();
            label.html('<i class="fas fa-spinner fa-spin"></i> ' + originalText);
            
            // Envoyer la requête AJAX
            $.ajax({
                url: '{{ route("admin.settings.toggle") }}',
                method: 'POST',
                data: {
                    key: key,
                    value: value
                },
                success: function(response) {
                    // Afficher un message de succès temporaire
                    label.html('<i class="fas fa-check text-success"></i> ' + originalText);
                    setTimeout(function() {
                        label.text(originalText);
                    }, 2000);
                },
                error: function(xhr) {
                    // Rétablir l'état précédent en cas d'erreur
                    label.html('<i class="fas fa-times text-danger"></i> ' + originalText);
                    setTimeout(function() {
                        label.text(originalText);
                    }, 2000);
                    console.error('Erreur lors de la mise à jour du paramètre:', xhr.responseText);
                }
            });
        });
    });
</script>
@endsection