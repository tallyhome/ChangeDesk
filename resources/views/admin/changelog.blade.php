@extends('layouts.app')

@section('title', __('app.admin.changelog'))

@include('partials.tinymce')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Éditeur de version -->
        <div class="col-md-8 editor-container">
            <div class="card fixed-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span>{{ isset($version) ? __('app.common.edit').' '.$version->version_number : __('app.admin.new_version') }}</span>
                    <a href="{{ route('admin.changelog.create') }}" class="btn btn-primary btn-sm">{{ __('app.admin.new_version') }}</a>
                </div>
                <div class="card-body scrollable">
                    <form action="{{ isset($version) ? route('admin.changelog.update', $version->id) : route('admin.changelog.store') }}" method="POST" enctype="multipart/form-data">
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
                            <input type="date" class="form-control" id="release_date" name="release_date" 
                                   value="{{ isset($version->release_date) ? $version->release_date->format('Y-m-d') : '' }}" required>
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Contenu</label>
                            <textarea class="form-control summernote" id="content" name="content" rows="15">{{ $version->content ?? '' }}</textarea>
                        </div>

                        <div class="mb-3 d-flex justify-content-between">
                            <div>
                                <button type="submit" class="btn btn-primary">{{ __('app.common.save') }}</button>
                                <a href="{{ route('admin.changelog') }}" class="btn btn-secondary">{{ __('app.common.back') }}</a>
                            </div>
                            @if(isset($version))
                            <form action="{{ route('admin.changelog.destroy', $version->id) }}" method="POST" class="d-inline" data-confirm="{{ __('app.admin.confirm_delete') }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">{{ __('app.common.delete') }}</button>
                            </form>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Liste des versions -->
        <div class="col-md-4 versions-container">
            <div class="card fixed-card">
                <div class="card-header">Versions</div>
                <div class="card-body scrollable">
                    <div class="nav flex-column">
                        @foreach($versions as $v)
                            <div class="d-flex justify-content-between align-items-center">
                                <a href="{{ route('admin.changelog.edit', $v->id) }}" class="nav-link">
                                    {{ $v->version_number }} ({{ $v->release_date->format('d/m/Y') }})
                                </a>
                                <form action="{{ route('admin.changelog.destroy', $v->id) }}" method="POST" class="d-inline" data-confirm="{{ __('app.admin.confirm_delete') }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection