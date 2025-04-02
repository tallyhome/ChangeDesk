@extends('layouts.admin')

@section('title', 'Gestion des sauvegardes de base de données')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2>Gestion des sauvegardes de base de données</h2>
                    <div class="d-flex">
                        <form action="{{ route('admin.backups.create') }}" method="POST" class="me-2">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-database me-2"></i>Sauvegarde complète
                            </button>
                        </form>
                        <form action="{{ route('admin.backups.create') }}" method="POST">
                            @csrf
                            <input type="hidden" name="user_data_only" value="1">
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-users me-2"></i>Sauvegarde données utilisateur
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(count($backups) > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Nom du fichier</th>
                                        <th>Taille</th>
                                        <th>Date de création</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($backups as $backup)
                                        <tr>
                                            <td>{{ $backup['filename'] }}</td>
                                            <td>{{ $backup['size'] }}</td>
                                            <td>{{ $backup['date'] }}</td>
                                            <td class="d-flex">
                                                <a href="{{ route('admin.backups.download', $backup['filename']) }}" class="btn btn-sm btn-info me-2">
                                                    <i class="fas fa-download"></i> Télécharger
                                                </a>
                                                
                                                <form action="{{ route('admin.backups.restore', $backup['filename']) }}" method="POST" class="me-2">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-warning" onclick="return confirm('Êtes-vous sûr de vouloir restaurer cette sauvegarde ? Toutes les données actuelles seront remplacées.')">
                                                        <i class="fas fa-undo"></i> Restaurer
                                                    </button>
                                                </form>
                                                
                                                <form action="{{ route('admin.backups.destroy', $backup['filename']) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette sauvegarde ?')">
                                                        <i class="fas fa-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="alert alert-info">
                            Aucune sauvegarde disponible. Utilisez le bouton "Créer une sauvegarde" pour effectuer votre première sauvegarde.
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection