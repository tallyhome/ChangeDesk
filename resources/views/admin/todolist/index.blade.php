@extends('layouts.admin')

@section('title', 'Gestion des fonctionnalités à venir')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Gestion des fonctionnalités à venir</h1>
        <div class="d-flex align-items-center gap-3">
            @php
                $todoEnabled = \App\Models\Setting::getValue('todo_enabled', '1');
            @endphp
            <i class="fas fa-toggle-{{ $todoEnabled == '1' ? 'on text-success' : 'off text-danger' }} toggle-icon"
               style="font-size: 1.5rem; cursor: pointer;"
               data-key="todo_enabled"
               title="{{ $todoEnabled == '1' ? 'Désactiver les fonctionnalités à venir' : 'Activer les fonctionnalités à venir' }}"></i>
            <a href="{{ route('admin.todolist.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter une fonctionnalité
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
            @if($todoItems->count() > 0)
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Titre</th>
                                <th>Description</th>
                                <th>Progression</th>
                                <th>Priorité</th>
                                <th>Statut</th>
                                <th>Date prévue</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($todoItems as $item)
                                <tr>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ Str::limit($item->description, 50) }}</td>
                                    <td>
                                        <div class="progress">
                                            <div class="progress-bar bg-{{ $item->color }}" 
                                                 role="progressbar" 
                                                 style="width: {{ $item->progress }}%" 
                                                 aria-valuenow="{{ $item->progress }}" 
                                                 aria-valuemin="0" 
                                                 aria-valuemax="100">{{ $item->progress }}%</div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->priority === 'high' ? 'danger' : ($item->priority === 'medium' ? 'warning' : 'success') }}">
                                            {{ ucfirst($item->priority) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge bg-{{ $item->status === 'pending' ? 'warning' : ($item->status === 'completed' ? 'success' : 'info') }}">
                                            {{ $item->status === 'pending' ? 'En attente' : ($item->status === 'completed' ? 'Terminé' : 'En cours') }}
                                        </span>
                                    </td>
                                    <td>{{ $item->expected_completion_date ? date('d/m/Y', strtotime($item->expected_completion_date)) : 'Non définie' }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.todolist.edit', $item) }}" class="btn btn-sm btn-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.todolist.destroy', $item) }}" method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette fonctionnalité ?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center">Aucune fonctionnalité à venir n'a été ajoutée.</p>
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
    
    // Gérer le toggle du système de fonctionnalités à venir
    $('.toggle-icon').on('click', function() {
        const $icon = $(this);
        const key = $icon.data('key');
        
        // Afficher l'indicateur de chargement
        $icon.removeClass('fa-toggle-on fa-toggle-off').addClass('fa-spinner fa-spin');
        
        // Envoyer la requête AJAX
        $.ajax({
            url: "{{ route('admin.todolist.toggle-status') }}",
            method: 'POST',
            success: function(response) {
                if (response.success) {
                    // Mettre à jour l'icône
                    $icon.removeClass('fa-spinner fa-spin');
                    if (response.value === '1') {
                        $icon.removeClass('fa-toggle-off text-danger').addClass('fa-toggle-on text-success');
                        $icon.attr('title', 'Désactiver les fonctionnalités à venir');
                    } else {
                        $icon.removeClass('fa-toggle-on text-success').addClass('fa-toggle-off text-danger');
                        $icon.attr('title', 'Activer les fonctionnalités à venir');
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