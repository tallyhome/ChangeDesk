@extends('layouts.admin')

@section('title', 'Nouvelle Version')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <h1>Créer une Nouvelle Version</h1>
            
            <form action="{{ route('admin.changelog.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="version_number" class="form-label">Numéro de version</label>
                    <input type="text" class="form-control" id="version_number" name="version_number" required>
                </div>
                
                <div class="mb-3">
                    <label for="release_date" class="form-label">Date de sortie</label>
                    <input type="date" class="form-control" id="release_date" name="release_date" required>
                </div>
                
                <!-- Le champ description a été supprimé -->
                
                <div class="mb-3">
                    <label for="content" class="form-label">Changements</label>
                    <textarea class="form-control" id="content" name="content" rows="10" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Ajouter</button>
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

@section('scripts')
@include('partials.tinymce')
<script>
    $(document).ready(function() {
        // Configuration spécifique pour le formulaire de création
        tinymce.init({
            selector: '#content',
            height: 400,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save(); // Sauvegarde le contenu dans le textarea
                });
            }
        });

        // Validation du formulaire
        $('form').on('submit', function(e) {
            if (!$('#version_number').val() || !$('#release_date').val() || !tinymce.get('content').getContent()) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires');
            }
        });
    });
</script>
@endsection