@extends('layouts.admin')

@section('title', 'Gestion du Wiki')

@push('styles')
<style>
    .btn-toggle-publication {
        cursor: pointer;
        transition: all 0.3s ease;
    }
    .btn-toggle-publication:hover {
        transform: scale(1.1);
    }
    .btn-toggle-publication.published {
        color: #198754;
    }
    .btn-toggle-publication.unpublished {
        color: #6c757d;
    }
    [data-bs-theme="dark"] .btn-toggle-publication.published {
        color: #25c274;
    }
    [data-bs-theme="dark"] .btn-toggle-publication.unpublished {
        color: #adb5bd;
    }
    .btn-toggle-wiki {
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.5rem;
    }
    .btn-toggle-wiki:hover {
        transform: scale(1.1);
    }
    .btn-toggle-wiki.enabled {
        color: #198754;
    }
    .btn-toggle-wiki.disabled {
        color: #dc3545;
    }
    [data-bs-theme="dark"] .btn-toggle-wiki.enabled {
        color: #25c274;
    }
    [data-bs-theme="dark"] .btn-toggle-wiki.disabled {
        color: #ff6b6b;
    }
</style>
@endpush

@section('content')
<div class="container-fluid" style="margin-top: 80px;">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">Gestion des articles Wiki</h1>
        <div>
            <i class="fas fa-power-off btn-toggle-wiki {{ $wikiEnabled ? 'enabled' : 'disabled' }}"
               data-bs-toggle="tooltip"
               title="{{ $wikiEnabled ? 'Désactiver le Wiki' : 'Activer le Wiki' }}"
               style="margin-right: 1rem;"></i>
            <a href="{{ route('admin.wiki.categories.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-folder me-1"></i> Gérer les catégories
            </a>
            <a href="{{ route('admin.wiki.settings') }}" class="btn btn-outline-secondary me-2">
                <i class="fas fa-cog me-2"></i>Paramètres
            </a>
            <a href="{{ route('admin.wiki.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouvel article
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
        <div class="card-header bg-primary bg-opacity-10">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Liste des articles</h5>
                </div>
                <div class="col-md-4">
                    <form action="{{ route('admin.wiki.index') }}" method="GET" class="d-flex">
                        <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-light ms-2">
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
                                    <form action="{{ route('admin.wiki.toggle-publication', $article) }}" method="POST" class="d-inline">
                                        @csrf
                                        <i class="fas fa-toggle-on fa-lg btn-toggle-publication {{ $article->is_published ? 'published' : 'unpublished' }}"
                                           data-article-id="{{ $article->id }}"
                                           data-bs-toggle="tooltip"
                                           title="{{ $article->is_published ? 'Dépublier' : 'Publier' }}"
                                           style="transform: rotate({{ $article->is_published ? '0' : '180' }}deg);">
                                        </i>
                                    </form>
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
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialiser les tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Gérer le basculement de la publication
    document.querySelectorAll('.btn-toggle-publication').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const form = this.closest('form');
            const articleId = this.dataset.articleId;
            const icon = this;
            
            console.log('Tentative de basculement pour l\'article:', articleId);
            
            // Désactiver temporairement le bouton
            icon.style.pointerEvents = 'none';
            
            // Soumettre le formulaire via AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                console.log('Réponse reçue:', response);
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data);
                if (data.success) {
                    // Mettre à jour l'apparence du bouton
                    icon.classList.toggle('published');
                    icon.classList.toggle('unpublished');
                    icon.style.transform = `rotate(${data.is_published ? '0' : '180'}deg)`;
                    
                    // Mettre à jour le tooltip
                    const tooltip = bootstrap.Tooltip.getInstance(icon);
                    tooltip.setContent({ '.tooltip-inner': data.is_published ? 'Dépublier' : 'Publier' });
                    
                    // Afficher un message de succès
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.card'));
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                // Afficher un message d'erreur
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    Une erreur est survenue lors de la modification du statut: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.card'));
            })
            .finally(() => {
                // Réactiver le bouton
                icon.style.pointerEvents = 'auto';
            });
        });
    });

    // Gérer le basculement de l'état du wiki
    const wikiToggle = document.querySelector('.btn-toggle-wiki');
    if (wikiToggle) {
        wikiToggle.addEventListener('click', function() {
            const icon = this;
            
            // Désactiver temporairement le bouton
            icon.style.pointerEvents = 'none';
            
            fetch('{{ route("admin.wiki.toggle-status") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Mettre à jour l'apparence du bouton
                    icon.classList.toggle('enabled');
                    icon.classList.toggle('disabled');
                    
                    // Mettre à jour le tooltip
                    const tooltip = bootstrap.Tooltip.getInstance(icon);
                    tooltip.setContent({ '.tooltip-inner': data.is_enabled ? 'Désactiver le Wiki' : 'Activer le Wiki' });
                    
                    // Afficher un message de succès
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success alert-dismissible fade show';
                    alert.innerHTML = `
                        ${data.message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    `;
                    document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.card'));
                } else {
                    throw new Error(data.message || 'Une erreur est survenue');
                }
            })
            .catch(error => {
                console.error('Erreur détaillée:', error);
                // Afficher un message d'erreur
                const alert = document.createElement('div');
                alert.className = 'alert alert-danger alert-dismissible fade show';
                alert.innerHTML = `
                    Une erreur est survenue lors de la modification du statut du wiki: ${error.message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
                document.querySelector('.container-fluid').insertBefore(alert, document.querySelector('.card'));
            })
            .finally(() => {
                // Réactiver le bouton
                icon.style.pointerEvents = 'auto';
            });
        });
    }
});
</script>
@endpush