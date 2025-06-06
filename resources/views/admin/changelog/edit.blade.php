@extends('layouts.admin')

@section('title', 'Modifier la version')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>Modifier la version</h1>
                <form action="{{ route('admin.changelog.destroy', $version->id) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette version ?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
            
            <form action="{{ route('admin.changelog.update', $version->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="version_number" class="form-label">Numéro de version</label>
                    <input type="text" class="form-control" id="version_number" name="version_number" value="{{ $version->version_number }}" required>
                </div>
                
                <div class="mb-3">
                    <label for="release_date" class="form-label">Date de sortie</label>
                    <input type="date" class="form-control" id="release_date" name="release_date" value="{{ $version->release_date->format('Y-m-d') }}" required>
                </div>
                
                <!-- Le champ description a été supprimé -->
                
                <div class="mb-3">
                    <label for="content" class="form-label">Changements</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required>{{ $version->content }}</textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </form>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5>Versions existantes</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <ul class="list-group">
                        @foreach($versions as $existingVersion)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>v{{ $existingVersion->version_number }}</strong>
                                        <br>
                                        <small>{{ $existingVersion->release_date->format('d/m/Y') }}</small>
                                    </div>
                                    <div>
                                        <a href="{{ route('admin.changelog.edit', $existingVersion->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.tinymce')