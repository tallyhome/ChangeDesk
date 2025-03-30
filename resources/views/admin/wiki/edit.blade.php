@extends('layouts.admin')

@section('title', 'Modifier un article Wiki')

@push('styles')
<style>
    .tox-tinymce {
        border: 1px solid #ced4da;
        border-radius: 0.25rem;
    }
    [data-bs-theme="dark"] .tox-tinymce {
        border-color: #404040;
    }
    [data-bs-theme="dark"] .tox {
        background-color: #2d2d2d !important;
    }
    [data-bs-theme="dark"] .tox .tox-toolbar,
    [data-bs-theme="dark"] .tox .tox-toolbar__primary,
    [data-bs-theme="dark"] .tox .tox-toolbar__overflow,
    [data-bs-theme="dark"] .tox .tox-edit-area__iframe,
    [data-bs-theme="dark"] .tox .tox-statusbar {
        background-color: #2d2d2d !important;
        border-color: #404040 !important;
    }
    [data-bs-theme="dark"] .tox .tox-mbtn,
    [data-bs-theme="dark"] .tox .tox-tbtn {
        color: #ffffff !important;
    }
    [data-bs-theme="dark"] .tox .tox-tbtn svg {
        fill: #ffffff !important;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.wiki.index') }}">Wiki</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifier un article</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header bg-light">
            <h1 class="h2 mb-0">Modifier l'article Wiki</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wiki.update', $article) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="title" class="form-label">Titre</label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $article->title) }}" required>
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="wiki_category_id" class="form-label">Catégorie</label>
                    <select class="form-select @error('wiki_category_id') is-invalid @enderror" id="wiki_category_id" name="wiki_category_id">
                        <option value="">-- Aucune catégorie --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('wiki_category_id', $article->wiki_category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                    @error('wiki_category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="content" class="form-label">Contenu</label>
                    <textarea class="form-control tinymce @error('content') is-invalid @enderror" id="content" name="content" rows="15">{{ old('content', $article->content) }}</textarea>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label for="order" class="form-label">Ordre d'affichage</label>
                        <input type="number" class="form-control @error('order') is-invalid @enderror" id="order" name="order" value="{{ old('order', $article->order) }}">
                        @error('order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <div class="form-check mt-4">
                            <input type="hidden" name="is_published" value="0">
                            <input type="checkbox" class="form-check-input" id="is_published" name="is_published" value="1" {{ old('is_published', $article->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                Publier l'article
                            </label>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/tinymce/6.8.3/tinymce.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        tinymce.init({
            selector: '.tinymce',
            height: 500,
            skin: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'oxide-dark' : 'oxide',
            content_css: window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'default',
            menubar: 'file edit view insert format tools table help',
            plugins: [
                'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor',
                'searchreplace', 'visualblocks', 'code', 'fullscreen',
                'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
            ],
            toolbar: 'undo redo | styles | bold italic | alignleft aligncenter alignright alignjustify | ' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons | help',
            menu: {
                file: { title: 'File', items: 'newdocument restoredraft | preview | print' },
                edit: { title: 'Edit', items: 'undo redo | cut copy paste pastetext | selectall | searchreplace' },
                view: { title: 'View', items: 'code | visualaid visualchars visualblocks | spellchecker | preview fullscreen' },
                insert: { title: 'Insert', items: 'image link media template codesample inserttable | charmap emoticons hr | pagebreak nonbreaking anchor toc | insertdatetime' },
                format: { title: 'Format', items: 'bold italic underline strikethrough superscript subscript codeformat | formats blockformats fontformats fontsizes align lineheight | forecolor backcolor | removeformat' },
                tools: { title: 'Tools', items: 'spellchecker spellcheckerlanguage | code wordcount' },
                table: { title: 'Table', items: 'inserttable | cell row column | advtablesort | tableprops deletetable' }
            },
            content_style: 'body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; font-size: 16px; }',
            setup: function(editor) {
                editor.on('init', function() {
                    this.getContainer().style.visibility = 'visible';
                });
            },
            init_instance_callback: function(editor) {
                editor.on('Change', function(e) {
                    editor.save();
                });
            }
        });

        // Mise à jour du thème lors du changement de mode sombre/clair
        const darkModeObserver = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === 'data-bs-theme') {
                    const isDarkMode = document.documentElement.getAttribute('data-bs-theme') === 'dark';
                    tinymce.activeEditor.setOption('skin', isDarkMode ? 'oxide-dark' : 'oxide');
                    tinymce.activeEditor.setOption('content_css', isDarkMode ? 'dark' : 'default');
                }
            });
        });

        darkModeObserver.observe(document.documentElement, {
            attributes: true,
            attributeFilter: ['data-bs-theme']
        });
    });
</script>
@endpush