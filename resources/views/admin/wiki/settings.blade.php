@extends('layouts.admin')

@section('title', 'Paramètres du Wiki')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Paramètres du Wiki</h1>
        <a href="{{ route('admin.wiki.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour aux articles
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light">
            <h2 class="h5 mb-0">Configuration du Wiki</h2>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wiki.settings.update') }}" method="POST">
                @csrf
                <!-- Suppression de la ligne @method('PUT') -->

                <div class="mb-3">
                    <label for="wiki_title" class="form-label">Titre du Wiki</label>
                    <input type="text" class="form-control" id="wiki_title" name="wiki_title" value="{{ $settings['wiki_title'] ?? 'Wiki' }}">
                </div>

                <div class="mb-3">
                    <label for="wiki_welcome_title" class="form-label">Titre de bienvenue</label>
                    <input type="text" class="form-control" id="wiki_welcome_title" name="wiki_welcome_title" value="{{ $settings['wiki_welcome_title'] ?? 'Bienvenue dans le Wiki' }}">
                </div>

                <div class="mb-3">
                    <label for="wiki_welcome_text" class="form-label">Texte de bienvenue</label>
                    <textarea class="form-control" id="wiki_welcome_text" name="wiki_welcome_text" rows="5">{{ $settings['wiki_welcome_text'] ?? '' }}</textarea>
                    <div class="form-text">Ce texte apparaîtra sur la page d'accueil du Wiki.</div>
                </div>

                <div class="mb-3 d-flex align-items-center">
                    <label class="form-label me-3 mb-0">Activer le Wiki</label>
                    <i class="fas fa-toggle-{{ $settings['wiki_enabled'] == '1' ? 'on text-success' : 'off text-danger' }} toggle-icon"
                       style="font-size: 1.5rem; cursor: pointer;"
                       data-key="wiki_enabled"
                       title="{{ $settings['wiki_enabled'] == '1' ? 'Désactiver le wiki' : 'Activer le wiki' }}"></i>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
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
    
    // Gérer le toggle du Wiki
    $('.toggle-icon').on('click', function() {
        const $icon = $(this);
        const key = $icon.data('key');
        
        // Afficher l'indicateur de chargement
        $icon.removeClass('fa-toggle-on fa-toggle-off').addClass('fa-spinner fa-spin');
        
        // Envoyer la requête AJAX
        $.ajax({
            url: "{{ route('admin.settings.toggle') }}",
            method: 'POST',
            data: { key: key },
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'icône
                    $icon.removeClass('fa-spinner fa-spin');
                    if (response.value === '1') {
                        $icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                        $icon.attr('title', 'Désactiver le wiki');
                    } else {
                        $icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                        $icon.attr('title', 'Activer le wiki');
                    }
                    
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
                // Rétablir l'icône en cas d'erreur
                $icon.removeClass('fa-spinner fa-spin')
                     .addClass($icon.hasClass('text-success') ? 'fa-toggle-on' : 'fa-toggle-off');
                console.error('Erreur lors de la mise à jour du paramètre:', xhr.responseText);
                alert('Une erreur est survenue lors de la mise à jour du paramètre.');
            }
        });
    });
});
</script>
@endsection