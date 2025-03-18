@extends('layouts.app')

@section('title', 'Éditer la page')

@section('head')
<!-- Utiliser CKEditor à la place de TinyMCE -->
<script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
<style>
    .ck-editor__editable_inline {
        min-height: 300px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Éditer {{ $page->title }}</h2>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre</label>
                            <input type="text" class="form-control" id="title" name="title" value="{{ $page->title }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="content" class="form-label">Contenu</label>
                            <textarea class="form-control" id="content" name="content" rows="10">{{ $page->content }}</textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Annuler</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
@endsection