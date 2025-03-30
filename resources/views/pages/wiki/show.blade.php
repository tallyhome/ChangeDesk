@extends('layouts.app')

@section('title', $article->title)

@section('content')
<div class="container-fluid py-5">
    <div class="row">
        <!-- Menu de navigation à gauche -->
        <div class="col-md-3">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Catégories</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <ul class="nav flex-column wiki-nav">
                        <li class="nav-item">
                            <a href="{{ route('wiki') }}" class="nav-link {{ request()->routeIs('wiki') && !request()->route('slug') ? 'active' : '' }}">
                                <i class="fas fa-home me-2"></i> Accueil du Wiki
                            </a>
                        </li>
                        @foreach($categories as $category)
                            <li class="nav-item">
                                <a href="{{ route('wiki.category', $category->slug) }}" class="nav-link {{ $article->category && $article->category->id == $category->id ? 'active' : '' }}">
                                    <i class="fas fa-folder me-2"></i> {{ $category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Contenu principal au centre -->
        <div class="col-md-6">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('wiki') }}">Wiki</a></li>
                    @if($article->category)
                        <li class="breadcrumb-item"><a href="{{ route('wiki.category', $article->category->slug) }}">{{ $article->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ $article->title }}</li>
                </ol>
            </nav>
            
            <div class="card">
                <div class="card-header bg-light">
                    <h1 class="h2 mb-0">{{ $article->title }}</h1>
                </div>
                <div class="card-body">
                    <div class="wiki-content">
                        {!! $article->content !!}
                    </div>
                </div>
                <div class="card-footer bg-light text-muted">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <i class="fas fa-calendar-alt me-1"></i> Mis à jour le {{ $article->updated_at->format('d/m/Y') }}
                        </div>
                        @if($article->category)
                            <div>
                                <i class="fas fa-folder me-1"></i> {{ $article->category->name }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Barre latérale droite pour la classification -->
        <div class="col-md-3">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Articles similaires</h5>
                </div>
                <div class="card-body">
                    @if($relatedArticles->count() > 0)
                        <ul class="list-group list-group-flush">
                            @foreach($relatedArticles as $relatedArticle)
                                <li class="list-group-item px-0">
                                    <a href="{{ route('wiki.show', $relatedArticle->slug) }}" class="text-decoration-none">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span>{{ $relatedArticle->title }}</span>
                                            <i class="fas fa-chevron-right"></i>
                                        </div>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">Aucun article similaire trouvé.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .wiki-nav .nav-link {
        color: var(--text-color);
        border-left: 3px solid transparent;
        padding: 0.5rem 1rem;
        transition: all 0.3s ease;
    }
    
    .wiki-nav .nav-link:hover {
        background-color: rgba(13, 110, 253, 0.1);
        border-left: 3px solid rgba(13, 110, 253, 0.5);
    }
    
    .wiki-nav .nav-link.active {
        background-color: rgba(13, 110, 253, 0.1);
        border-left: 3px solid #0d6efd;
        font-weight: bold;
    }
    
    .wiki-content img {
        max-width: 100%;
        height: auto;
    }
    
    .wiki-content h2 {
        margin-top: 1.5rem;
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.1);
    }
    
    .wiki-content h3 {
        margin-top: 1.2rem;
        margin-bottom: 0.8rem;
    }
    
    .wiki-content pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 0.25rem;
        overflow-x: auto;
    }
    
    [data-theme="dark"] .wiki-content pre {
        background-color: #2d2d2d;
        color: #f8f9fa;
    }

    [data-theme="dark"] .wiki-content h2 {
        border-bottom-color: rgba(255,255,255,0.1);
    }

    [data-theme="dark"] .card {
        background-color: #2d2d2d;
        border-color: rgba(255,255,255,0.1);
    }

    [data-theme="dark"] .card-header {
        background-color: #222;
        border-bottom-color: rgba(255,255,255,0.1);
    }

    [data-theme="dark"] .card-footer {
        background-color: #222;
        border-top-color: rgba(255,255,255,0.1);
    }

    [data-theme="dark"] .list-group-item {
        background-color: #2d2d2d;
        border-color: rgba(255,255,255,0.1);
    }

    [data-theme="dark"] .list-group-item a {
        color: #66b0ff;
    }

    [data-theme="dark"] .breadcrumb-item a {
        color: #66b0ff;
    }

    [data-theme="dark"] .breadcrumb-item.active {
        color: #adb5bd;
    }

    [data-theme="dark"] .wiki-nav .nav-link:hover {
        background-color: rgba(102, 176, 255, 0.1);
        border-left-color: rgba(102, 176, 255, 0.5);
    }

    [data-theme="dark"] .wiki-nav .nav-link.active {
        background-color: rgba(102, 176, 255, 0.1);
        border-left-color: #66b0ff;
    }
</style>
@endpush