@extends('layouts.admin')

@section('title', 'Créer un article Wiki')

@section('content')
<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4" style="z-index: 900; position: relative;">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.wiki.index') }}">Wiki</a></li>
            <li class="breadcrumb-item active" aria-current="page">Créer un article</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header bg-light">
            <h1 class="h2 mb-0">Créer un nouvel article Wiki</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wiki.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="wiki_category_id" class="form-label">Catégorie</label>
                    <select class="form-select @error('wiki_category_id') is-invalid @enderror" id="wiki_category_id" name="wiki_category_id">
                        <option value="">-- Aucune catégorie --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('wiki_category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('wiki_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">Contenu</label>
                    <textarea class="form-control tinymce @error('content') is-invalid @enderror" id="content" name="content" rows="15">{{ old('content') }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="order" class="form-label">Ordre d'affichage</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', 0) }}">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input class="form-check-input" type="checkbox" id="is_published" name="is_published" {{ old('is_published') ? 'checked' : '' }}>
            <label class="form-check-label" for="is_published">Publier l'article</label>
        </div>
    </div>
</div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.wiki.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Retour
                    </a>
                    <div>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Enregistrer
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '.tinymce',
            height: 500,
            menubar: true,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | bold italic backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
            content_css: [
                '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
                '//www.tiny.cloud/css/codepen.min.css'
            ]
        });
    });
</script>
@endpush