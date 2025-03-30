@extends('layouts.app')

@section('title', $category->name)

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
                        @foreach($categories as $cat)
                            <li class="nav-item">
                                <a href="{{ route('wiki.category', $cat->slug) }}" class="nav-link {{ $category->id == $cat->id ? 'active' : '' }}">
                                    <i class="fas fa-folder me-2"></i> {{ $cat->name }}
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
                    <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                </ol>
            </nav>
            
            <h1 class="mb-4">{{ $category->name }}</h1>
            
            @if($category->description)
                <div class="card mb-4">
                    <div class="card-body">
                        {{ $category->description }}
                    </div>
                </div>
            @endif
            
            @if($articles->count() > 0)
                <div class="list-group mb-4">
                    @foreach($articles as $article)
                        <a href="{{ route('wiki.show', $article->slug) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $article->title }}</h5>
                                <small>{{ $article->updated_at->format('d/m/Y') }}</small>
                            </div>
                            <p class="mb-1">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    Aucun article n'a encore été publié dans cette catégorie.
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
                    <h5 class="mb-0">Articles de cette catégorie</h5>
                </div>
                <div class="card-body">
                    @if($articles->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($articles as $article)
                                <a href="{{ route('wiki.show', $article->slug) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                                    {{ $article->title }}
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted">Aucun article dans cette catégorie.</p>
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
</style>
@endpush