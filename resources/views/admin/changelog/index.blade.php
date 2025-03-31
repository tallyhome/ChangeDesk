@extends('layouts.admin')

@section('title', 'Gestion des Versions')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des Versions</h1>
        <div class="d-flex align-items-center gap-3">
            @php
                $changelogEnabled = \App\Models\Setting::getValue('changelog_enabled', '1');
            @endphp
            <i class="fas fa-toggle-{{ $changelogEnabled == '1' ? 'on text-success' : 'off text-danger' }} toggle-icon"
               style="font-size: 1.5rem; cursor: pointer;"
               data-key="changelog_enabled"
               title="{{ $changelogEnabled == '1' ? 'Désactiver le changelog' : 'Activer le changelog' }}"></i>
            <a href="{{ route('admin.changelog.create') }}" class="btn btn-success">
                Nouvelle Version
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Version</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($versions as $version)
                    <tr>
                        <td>{{ $version->version_number }}</td>
                        <td>{{ $version->release_date->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.changelog.edit', $version->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                            <form action="{{ route('admin.changelog.destroy', $version->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
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
    
    // Gérer le toggle du système de changelog
    $('.toggle-icon').on('click', function() {
        const $icon = $(this);
        const key = $icon.data('key');
        
        // Afficher l'indicateur de chargement
        $icon.removeClass('fa-toggle-on fa-toggle-off').addClass('fa-spinner fa-spin');
        
        // Envoyer la requête AJAX
        $.ajax({
            url: "{{ route('admin.changelog.toggle-status') }}",
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'icône
                    $icon.removeClass('fa-spinner fa-spin');
                    if (response.value === '1') {
                        $icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                        $icon.attr('title', 'Désactiver le changelog');
                    } else {
                        $icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                        $icon.attr('title', 'Activer le changelog');
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