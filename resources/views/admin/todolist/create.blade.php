@extends('layouts.admin')

@section('title', 'Ajouter une fonctionnalité')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Ajouter une fonctionnalité</h1>
                <a href="{{ route('admin.todolist') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.todolist.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="progress" class="form-label">Progression (%): <span id="progressValue">{{ old('progress', 0) }}</span>%</label>
                            <input type="range" class="form-range @error('progress') is-invalid @enderror" id="progress" name="progress" min="0" max="100" step="5" value="{{ old('progress', 0) }}">
                            <div class="progress mt-2">
                                <div id="progressBar" class="progress-bar bg-{{ old('color', 'primary') }}" role="progressbar" style="width: {{ old('progress', 0) }}%" aria-valuenow="{{ old('progress', 0) }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @error('progress')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="color" class="form-label">Couleur de la barre de progression</label>
                            <select class="form-select @error('color') is-invalid @enderror" id="color" name="color" required>
                                <option value="primary" {{ old('color') == 'primary' ? 'selected' : '' }}>Bleu (Primary)</option>
                                <option value="success" {{ old('color') == 'success' ? 'selected' : '' }}>Vert (Success)</option>
                                <option value="info" {{ old('color') == 'info' ? 'selected' : '' }}>Bleu clair (Info)</option>
                                <option value="warning" {{ old('color') == 'warning' ? 'selected' : '' }}>Orange (Warning)</option>
                                <option value="danger" {{ old('color') == 'danger' ? 'selected' : '' }}>Rouge (Danger)</option>
                            </select>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="expected_date" class="form-label">Date prévue (optionnelle)</label>
                            <input type="date" class="form-control @error('expected_date') is-invalid @enderror" id="expected_date" name="expected_date" value="{{ old('expected_date') }}">
                            @error('expected_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
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
        // Configuration de TinyMCE
        tinymce.init({
            selector: '#description',
            height: 300,
            plugins: 'anchor autolink charmap codesample emoticons image link lists media searchreplace table visualblocks wordcount',
            toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table | align lineheight | numlist bullist indent outdent | emoticons charmap | removeformat',
            setup: function(editor) {
                editor.on('change', function() {
                    editor.save();
                });
            }
        });

        // Gestion de la barre de progression
        $('#progress').on('input change', function() {
            var value = $(this).val();
            $('#progressValue').text(value);
            $('#progressBar').css('width', value + '%').attr('aria-valuenow', value);
        });

        // Mise à jour de la couleur de la barre de progression
        $('#color').on('change', function() {
            var color = $(this).val();
            $('#progressBar').removeClass('bg-primary bg-success bg-info bg-warning bg-danger').addClass('bg-' + color);
        });

        // Validation du formulaire
        $('form').on('submit', function(e) {
            if (!$('#title').val() || !tinymce.get('description').getContent()) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs obligatoires');
            }
        });
    });
</script>
@endsection