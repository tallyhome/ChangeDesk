@extends('layouts.app')

@section('title', 'Admin - Changelog')

@section('head')
<script src="https://cdn.ckeditor.com/ckeditor5/35.1.0/classic/ckeditor.js"></script>
@endsection

@section('scripts')
<script>
    ClassicEditor
        .create(document.querySelector('#content'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ isset($version) ? 'Éditer la version '.$version->version_number : 'Nouvelle version' }}</span>
                    <a href="{{ route('admin.changelog.create') }}" class="btn btn-primary btn-sm">Nouvelle version</a>
                </div>
                <div class="card-body">
                    <form action="{{ isset($version) ? route('admin.changelog.update', $version->id) : route('admin.changelog.store') }}" method="POST">
                        @csrf
                        @if(isset($version))
                            @method('PUT')
                        @endif
                        
                        <div class="mb-3">
                            <label for="version_number" class="form-label">Numéro de version</label>
                            <input type="text" class="form-control" id="version_number" name="version_number" value="{{ $version->version_number ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="release_date" class="form-label">Date de sortie</label>
                            <input type="date" class="form-control" id="release_date" name="release_date" value="{{ $version->release_date ?? '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Contenu</label>
                            <textarea class="form-control" id="content" name="content" rows="15">{{ $version->content ?? '' }}</textarea>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                            <a href="{{ route('admin.changelog') }}" class="btn btn-secondary">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Versions</div>
                <div class="card-body">
                    <div class="nav flex-column">
                        @foreach($versions as $v)
                            <a href="{{ route('admin.changelog.edit', $v->id) }}" class="nav-link">
                                {{ $v->version_number }} ({{ $v->release_date->format('d/m/Y') }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection