@extends('layouts.admin')

@section('title', 'Gestion des rapports de bugs')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Gestion des rapports de bugs</h1>
                <a href="{{ route('admin.bug_reports.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter un rapport de bug
                </a>
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
    </div>
</div>
@endsection