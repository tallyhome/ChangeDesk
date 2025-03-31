@extends('layouts.admin')

@section('title', 'Liens externes')

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
                                   value="{{ $settings['external_link_text'] ?? '' }}">
                        </div>
                        
                        <div class="mb-3">
                            <label for="external_link_url" class="form-label">URL du lien externe</label>
                            <input type="url" class="form-control" id="external_link_url" name="external_link_url" 
                                   value="{{ $settings['external_link_url'] ?? '' }}">
                            <div class="form-text">Exemple: https://myvcard.fr/</div>
                        </div>
                        
                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label me-3 mb-0">Afficher le lien externe dans le menu</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="external_link_enabled" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="external_link_enabled" 
                                       name="external_link_enabled" value="1"
                                       {{ isset($settings['external_link_enabled']) && $settings['external_link_enabled'] == '1' ? 'checked' : '' }}
                                       data-key="external_link_enabled">
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="app_store_url" class="form-label">URL de l'App Store</label>
                            <input type="url" class="form-control" id="app_store_url" name="app_store_url" 
                                   value="{{ $settings['app_store_url'] ?? '' }}">
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label me-3 mb-0">Afficher l'icône App Store dans le footer</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="app_store_enabled" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="app_store_enabled"
                                       name="app_store_enabled" value="1"
                                       {{ isset($settings['app_store_enabled']) && $settings['app_store_enabled'] == '1' ? 'checked' : '' }}
                                       data-key="app_store_enabled">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="play_store_url" class="form-label">URL du Google Play Store</label>
                            <input type="url" class="form-control" id="play_store_url" name="play_store_url" 
                                   value="{{ $settings['play_store_url'] ?? '' }}">
                        </div>

                        <div class="mb-3 d-flex align-items-center">
                            <label class="form-label me-3 mb-0">Afficher l'icône Play Store dans le footer</label>
                            <div class="form-check form-switch">
                                <input type="hidden" name="play_store_enabled" value="0">
                                <input class="form-check-input" type="checkbox" role="switch" id="play_store_enabled"
                                       name="play_store_enabled" value="1"
                                       {{ isset($settings['play_store_enabled']) && $settings['play_store_enabled'] == '1' ? 'checked' : '' }}
                                       data-key="play_store_enabled">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer les modifications
                    </button>
                </div>
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
    
    // Gérer le toggle des paramètres
    $('.form-check-input').on('change', function() {
        const $checkbox = $(this);
        const key = $checkbox.data('key');
        const isChecked = $checkbox.prop('checked');
        
        // Désactiver le checkbox pendant la requête
        $checkbox.prop('disabled', true);
        
        // Envoyer la requête AJAX
        $.ajax({
            url: "{{ route('admin.settings.toggle') }}",
            method: 'POST',
            data: { key: key },
            success: function(response) {
                if (response.success) {
                    // Afficher le message de succès
                    const message = $('<div class="alert alert-success alert-dismissible fade show">' +
                        response.message +
                        '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>' +
                        '</div>');
                    $('.container').prepend(message);
                    setTimeout(() => message.remove(), 3000);
                }
            },
            error: function(xhr) {
                // En cas d'erreur, remettre le checkbox dans son état précédent
                $checkbox.prop('checked', !isChecked);
                console.error('Erreur lors de la mise à jour du paramètre:', xhr.responseText);
                alert('Une erreur est survenue lors de la mise à jour du paramètre.');
            },
            complete: function() {
                // Réactiver le checkbox
                $checkbox.prop('disabled', false);
            }
        });
    });
});
</script>
@endsection