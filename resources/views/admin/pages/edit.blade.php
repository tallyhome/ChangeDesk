@extends('layouts.app')

@section('title', 'Admin - Éditer ' . $page->title)

@include('partials.summernote')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Éditer {{ $page->title }}</div>
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
                            <textarea class="form-control summernote" id="content" name="content" rows="15">{{ $page->content }}</textarea>
                        </div>

                        <div class="mb-3">
                            <button type="submit" class="btn btn-primary">Sauvegarder</button>
                            <a href="{{ route('admin.pages.index') }}" class="btn btn-secondary">Retour</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection