@extends('layouts.app')

@section('title', 'Recherche: ' . $query)

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
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('wiki') }}">Wiki</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Recherche: {{ $query }}</li>
                </ol>
            </nav>
            
            <h1 class="mb-4">Résultats de recherche pour "{{ $query }}"</h1>
            
            @if($articles->count() > 0)
                <div class="list-group mb-4">
                    @foreach($articles as $article)
                        <a href="{{ route('wiki.show', $article->slug) }}" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h5 class="mb-1">{{ $article->title }}</h5>
                                @if($article->category)
                                    <small class="text-muted">{{ $article->category->name }}</small>
                                @endif
                            </div>
                            <p class="mb-1">{{ Str::limit(strip_tags($article->content), 150) }}</p>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> Aucun résultat trouvé pour "{{ $query }}".
                </div>
            @endif
            
            <div class="mt-4">
                <a href="{{ route('wiki') }}" class="btn btn-outline-primary">
                    <i class="fas fa-arrow-left me-2"></i> Retour à l'accueil du Wiki
                </a>
            </div>
        </div>
        
        <!-- Sidebar à droite -->
        <div class="col-md-3">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-light">
                    <h5 class="mb-0">Recherche</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('wiki.search') }}" method="GET" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Rechercher..." value="{{ $query }}" required>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                    
                    <div class="mt-3">
                        <h6>Affiner par catégorie:</h6>
                        <div class="list-group list-group-flush">
                            @foreach($categories as $category)
                                <a href="{{ route('wiki.category', $category->slug) }}" class="list-group-item list-group-item-action py-2 px-3">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection