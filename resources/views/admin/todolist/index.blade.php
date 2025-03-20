@extends('layouts.admin')

@section('title', 'Gestion des fonctionnalités à venir')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Gestion des fonctionnalités à venir</h1>
                <a href="{{ route('admin.todolist.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Ajouter une fonctionnalité
                </a>
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
                                        <th>Statut</th>
                                        <th>Date prévue</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($todoItems as $item)
                                        <tr>
                                            <td>{{ $item->title }}</td>
                                            <td>{!! Str::limit($item->description, 100) !!}</td>
                                            <td style="width: 200px;">
                                                <div class="progress">
                                                    <div class="progress-bar bg-{{ $item->color }}" role="progressbar" style="width: {{ $item->progress }}%" aria-valuenow="{{ $item->progress }}" aria-valuemin="0" aria-valuemax="100">{{ $item->progress }}%</div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($item->status == 'pending')
                                                    <span class="badge bg-warning">En attente</span>
                                                @elseif($item->status == 'in_progress')
                                                    <span class="badge bg-info">En cours</span>
                                                @elseif($item->status == 'completed')
                                                    <span class="badge bg-success">Terminé</span>
                                                @endif
                                            </td>
                                            <td>{{ $item->expected_date ? $item->expected_date->format('d/m/Y') : 'Non définie' }}</td>
                                            <td>
                                                <a href="{{ route('admin.todolist.edit', $item->id) }}" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('admin.todolist.destroy', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette fonctionnalité ?')">
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
                        <p class="text-center">Aucune fonctionnalité à venir n'a été ajoutée.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection