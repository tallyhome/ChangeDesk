@extends('layouts.admin')

@section('title', 'Modifier une fonctionnalité')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>Modifier une fonctionnalité</h1>
                <a href="{{ route('admin.todolist') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('admin.todolist.update', $todoItem->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $todoItem->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description', $todoItem->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="status" class="form-label">Statut</label>
                            <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                                <option value="pending" {{ old('status', $todoItem->status) == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="in_progress" {{ old('status', $todoItem->status) == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ old('status', $todoItem->status) == 'completed' ? 'selected' : '' }}>Terminé</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="progress" class="form-label">Progression (%): <span id="progressValue">{{ old('progress', $todoItem->progress ?? 0) }}</span>%</label>
                            <input type="range" class="form-range @error('progress') is-invalid @enderror" id="progress" name="progress" min="0" max="100" step="5" value="{{ old('progress', $todoItem->progress ?? 0) }}">
                            <div class="progress mt-2">
                                <div id="progressBar" class="progress-bar bg-{{ old('color', $todoItem->color ?? 'primary') }}" role="progressbar" style="width: {{ old('progress', $todoItem->progress ?? 0) }}%" aria-valuenow="{{ old('progress', $todoItem->progress ?? 0) }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            @error('progress')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="color" class="form-label">Couleur de la barre de progression</label>
                            <select class="form-select @error('color') is-invalid @enderror" id="color" name="color" required>
                                <option value="primary" {{ old('color', $todoItem->color) == 'primary' ? 'selected' : '' }}>Bleu (Primary)</option>
                                <option value="success" {{ old('color', $todoItem->color) == 'success' ? 'selected' : '' }}>Vert (Success)</option>
                                <option value="info" {{ old('color', $todoItem->color) == 'info' ? 'selected' : '' }}>Bleu clair (Info)</option>
                                <option value="warning" {{ old('color', $todoItem->color) == 'warning' ? 'selected' : '' }}>Orange (Warning)</option>
                                <option value="danger" {{ old('color', $todoItem->color) == 'danger' ? 'selected' : '' }}>Rouge (Danger)</option>
                            </select>
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="expected_date" class="form-label">Date prévue (optionnelle)</label>
                            <input type="date" class="form-control @error('expected_date') is-invalid @enderror" id="expected_date" name="expected_date" value="{{ old('expected_date', $todoItem->expected_date ? $todoItem->expected_date->format('Y-m-d') : '') }}">
                            @error('expected_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Enregistrer les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@include('partials.tinymce')

@push('scripts')
<script>
    $(document).ready(function() {
        tinymce.init({
            selector: '#description',
            placeholder: 'Décrivez la fonctionnalité en détail...',
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
    });
</script>
@endpush