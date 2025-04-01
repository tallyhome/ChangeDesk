@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h3 class="mb-0">Page expirée</h3>
                </div>
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-exclamation-circle text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-center mb-4">Votre session a expiré</h4>
                    <p class="mb-3">Pour des raisons de sécurité, votre session a expiré. Cela peut se produire pour les raisons suivantes :</p>
                    <ul class="mb-4">
                        <li>Vous êtes resté inactif trop longtemps</li>
                        <li>Vous avez ouvert la page dans un autre onglet</li>
                        <li>Vous avez utilisé le bouton retour du navigateur</li>
                    </ul>
                    <p class="mb-4">Veuillez réessayer en suivant ces étapes :</p>
                    <ol class="mb-4">
                        <li>Rafraîchissez la page</li>
                        <li>Reconnectez-vous si nécessaire</li>
                        <li>Réessayez l'action précédente</li>
                    </ol>
                    <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary me-md-2">
                            <i class="fas fa-arrow-left me-2"></i>Retour
                        </a>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-home me-2"></i>Tableau de bord
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection