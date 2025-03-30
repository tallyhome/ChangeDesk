@extends('layouts.admin')

@section('title', 'Gestion du Wiki')

@section('content')
<div class="container-fluid" style="margin-top: 80px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Gestion des articles Wiki</h1>
        <div>
            <a href="{{ route('admin.wiki.categories.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-folder me-1"></i> Gérer les catégories
            </a>
            <a href="{{ route('admin.wiki.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> Nouvel article
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Liste des articles</h5>
                </div>
                <div class="col-md-4">
                    <form action="{{ route('admin.wiki.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary ms-2">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Titre</th>
                            <th>Catégorie</th>
                            <th>Ordre</th>
                            <th>Statut</th>
                            <th>Dernière modification</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($articles as $article)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.wiki.edit', $article) }}" class="text-decoration-none fw-medium">
                                        {{ $article->title }}
                                    </a>
                                </td>
                                <td>
                                    @if($article->category)
                                        <span class="badge bg-light text-dark">
                                            {{ $article->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted">--</span>
                                    @endif
                                </td>
                                <td>{{ $article->order }}</td>
                                <td>
                                    @if($article->is_published)
                                        <span class="badge bg-success">Publié</span>
                                    @else
                                        <span class="badge bg-secondary">Brouillon</span>
                                    @endif
                                </td>
                                <td>{{ $article->updated_at->format('d/m/Y H:i') }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.wiki.show', $article) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.wiki.edit', $article) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.wiki.destroy', $article) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')" title="Supprimer">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Aucun article trouvé</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($articles->hasPages())
            <div class="card-footer bg-light">
                {{ $articles->links() }}
            </div>
        @endif