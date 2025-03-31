@extends('layouts.admin')

@section('title', 'Gestion des rapports de bugs')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des rapports de bugs</h1>
        <div class="d-flex align-items-center gap-3">
            @php
                $bugReportEnabled = \App\Models\Setting::getValue('bug_report_enabled', '1');
            @endphp
            <i class="fas fa-toggle-{{ $bugReportEnabled == '1' ? 'on text-success' : 'off text-danger' }} toggle-icon"
               style="font-size: 1.5rem; cursor: pointer;"
               data-key="bug_report_enabled"
               title="{{ $bugReportEnabled == '1' ? 'Désactiver les rapports de bugs' : 'Activer les rapports de bugs' }}"></i>
            <a href="{{ route('admin.bug_reports.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un rapport de bug
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            @if($bugReports->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Progression</th>
                                <th>Sévérité</th>
                                <th>Statut</th>
                                <th>Date prévue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($bugReports as $bug)
                                <tr>
                                    <td>{{ $bug->title }}</td>
                                    <td>{!! Str::limit($bug->description, 100) !!}</td>
                                    <td style="width: 200px;">
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $bug->color }}" role="progressbar" style="width: {{ $bug->progress }}%" aria-valuenow="{{ $bug->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $bug->progress }}%</div>
                                        </div>
                                    </td>
                                    <td>
                                        @if($bug->severity == 'low')
                                            <span class="badge bg-success">Faible</span>
                                        @elseif($bug->severity == 'medium')
                                            <span class="badge bg-warning">Moyenne</span>
                                        @elseif($bug->severity == 'high')
                                            <span class="badge bg-danger">Élevée</span>
                                        @elseif($bug->severity == 'critical')
                                            <span class="badge bg-dark">Critique</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bug->status == 'open')
                                            <span class="badge bg-danger">Ouvert</span>
                                        @elseif($bug->status == 'in_progress')
                                            <span class="badge bg-info">En cours</span>
                                        @elseif($bug->status == 'resolved')
                                            <span class="badge bg-success">Résolu</span>
                                        @elseif($bug->status == 'closed')
                                            <span class="badge bg-secondary">Fermé</span>
                                        @endif
                                    </td>
                                    <td>{{ $bug->expected_fix_date ? $bug->expected_fix_date->format('d/m/Y') : 'Non définie' }}</td>
                                    <td>
                                        <a href="{{ route('admin.bug_reports.edit', $bug->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.bug_reports.destroy', $bug->id) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rapport de bug ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Aucun rapport de bug n'a été ajouté.</p>
            @endif
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
    
    // Gérer le toggle du système de rapports de bugs
    $('.toggle-icon').on('click', function() {
        const $icon = $(this);
        const key = $icon.data('key');
        
        // Afficher l'indicateur de chargement
        $icon.removeClass('fa-toggle-on fa-toggle-off').addClass('fa-spinner fa-spin');
        
        // Envoyer la requête AJAX
        $.ajax({
            url: "{{ route('admin.bug_reports.toggle-status') }}",
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'icône
                    $icon.removeClass('fa-spinner fa-spin');
                    if (response.value === '1') {
                        $icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                        $icon.attr('title', 'Désactiver les rapports de bugs');
                    } else {
                        $icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                        $icon.attr('title', 'Activer les rapports de bugs');
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