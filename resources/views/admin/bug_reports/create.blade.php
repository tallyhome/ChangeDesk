@extends('layouts.admin')

@section('title', 'Ajouter un rapport de bug')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Ajouter un rapport de bug</h1>
                <a href="{{ route('admin.bug_reports') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.bug_reports.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="severity" class="form-label">Sévérité</label>
                            <select class="form-select @error('severity') is-invalid @enderror" id="severity" name="severity" required>
                                <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Faible</option>
                                <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>Élevée</option>
                                <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critique</option>
                            </select>
                            @error('severity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Ouvert</option>
                                <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="resolved" {{ old('status') == 'resolved' ? 'selected' : '' }}>Résolu</option>
                                <option value="closed" {{ old('status') == 'closed' ? 'selected' : '' }}>Fermé</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="progress" class="form-label">Progression (%): <span id="progressValue">{{ old('progress', 0) }}</span>%</label>
                            <input type="range" class="form-range @error('progress') is-invalid @enderror" id="progress" name="progress" min="0" max="100" step="5" value="{{ old('progress', 0) }}">
                            <div class="progress mt-2">
                                <div id="progressBar" class="progress-bar bg-{{ old('color', 'danger') }}" role="progressbar" style="width: {{ old('progress', 0) }}%" aria-valuenow="{{ old('progress', 0) }}" aria-valuemin="0" aria-valuemax="100"></div>
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
                                <option value="danger" {{ old('color', 'danger') == 'danger' ? 'selected' : '' }}>Rouge (Danger)</option>
                            </select>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="expected_fix_date" class="form-label">Date prévue de correction (optionnelle)</label>
                            <input type="date" class="form-control @error('expected_fix_date') is-invalid @enderror" id="expected_fix_date" name="expected_fix_date" value="{{ old('expected_fix_date') }}">
                            @error('expected_fix_date')
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
<script>
    $(document).ready(function() {
        // Initialiser l'éditeur de texte pour la description
        $('#description').summernote({
            placeholder: 'Décrivez le bug en détail...',
            height: 200,
            toolbar: [
                ['style', ['style']],
                ['font', ['bold', 'underline', 'clear']],
                ['color', ['color']],
                ['para', ['ul', 'ol', 'paragraph']],
                ['table', ['table']],
                ['insert', ['link']],
                ['view', ['fullscreen', 'codeview', 'help']]
            ]
        });
        
        // Mise à jour dynamique de la barre de progression
        $('#progress').on('input', function() {
            var value = $(this).val();
            $('#progressValue').text(value);
            $('#progressBar').css('width', value + '%').attr('aria-valuenow', value);
        });
        
        // Mise à jour de la couleur de la barre de progression
        $('#color').on('change', function() {
            var color = $(this).val();
            $('#progressBar').removeClass('bg-primary bg-success bg-info bg-warning bg-danger').addClass('bg-' + color);
        });
    });
</script>
@endsection