@extends('layouts.admin')

@section('title', $article->title)

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2">{{ $article->title }}</h1>
            @if($article->category)
                <span class="badge bg-light text-dark">{{ $article->category->name }}</span>
            @endif
        </div>
        <div>
            <a href="{{ route('admin.wiki.edit', $article) }}" class="btn btn-primary">
                <i class="fas fa-edit me-1"></i> Modifier
            </a>
            <a href="{{ route('admin.wiki.index') }}" class="btn btn-outline-secondary ms-2">
                <i class="fas fa-arrow-left me-1"></i> Retour à la liste
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="wiki-content">
                {!! $article->content !!}
            </div>
        </div>
    </div>

    <div class="mt-4 text-muted">
        <small>Dernière modification : {{ $article->updated_at->format('d/m/Y H:i') }}</small>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .wiki-content {
        font-size: 1.1em;
        line-height: 1.6;
    }
    .wiki-content img {
        max-width: 100%;
        height: auto;
    }
    .wiki-content table {
        width: 100%;
        margin-bottom: 1rem;
        border-collapse: collapse;
    }
    .wiki-content table th,
    .wiki-content table td {
        padding: 0.75rem;
        border: 1px solid var(--tab-border);
    }
    .wiki-content table th {
        background-color: var(--tab-bg);
    }
</style>
@endsection