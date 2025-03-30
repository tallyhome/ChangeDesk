@extends('layouts.app')

@section('title', 'Wiki')

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
                                <a href="{{ route('wiki.category', $category->slug) }}" class="nav-link">
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
            <h1 class="mb-4">Wiki</h1>
            
            <div class="card mb-4">
                <div class="card-header bg-light">
                    <h2 class="h4 mb-0">Bienvenue dans le Wiki</h2>
                </div>
                <div class="card-body">
                    <p>Bienvenue dans notre base de connaissances. Vous trouverez ici toutes les informations nécessaires organisées par catégories.</p>
                    <p>Utilisez le menu de navigation à gauche pour parcourir les différentes catégories ou utilisez la barre de recherche pour trouver rapidement ce que vous cherchez.</p>
                </div>
            </div>
            
            <h2 class="h3 mb-3">Articles récents</h2>
            
            @if($recentArticles->count() > 0)
                @foreach($recentArticles as $article)
                    <div class="card mb-3">
                        <div class="card-body">
                            <h3 class="h5 mb-2">
                                <a href="{{ route('wiki.show', $article->slug) }}" class="text-decoration-none">{{ $article->title }}</a>
                            </h3>
                            <p class="text-muted small mb-2">
                                @if($article->category)
                                    <span class="badge bg-secondary">{{ $article->category->name }}</span>
                                @endif
                                <span><i class="fas fa-calendar-alt ms-2 me-1"></i> {{ $article->updated_at->format('d/m/Y') }}</span>
                            </p>
                            <p class="mb-0">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="alert alert-info">
                    Aucun article n'a encore été publié.
                </div>
            @endif
        </div>
        
        <!-- Barre latérale droite pour la classification -->
        <div class="col-md-3">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Recherche</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wiki.search') }}" method="GET">
                        <div class="input-group">
                            <input type="text" class="form-control" name="q" placeholder="Rechercher..." required>
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">Toutes les catégories</h5>
                </div>
                <div class="card-body">
                    <div class="list-group list-group-flush">
                        @foreach($categories as $category)
                            <a href="{{ route('wiki.category', $category->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                {{ $category->name }}
                                <span class="badge bg-primary rounded-pill">{{ $category->publishedArticles->count() }}</span>
                            </a>
                        @endforeach
                    </div>
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
</style>
@endpush