@extends('layouts.app')

@section('title', 'Historique des versions')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">Historique des versions</h1>
            
            @foreach($versions as $version)
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h2 class="h4 mb-0">v{{ $version->version_number }} <small class="text-muted">({{ $version->release_date->format('d/m/Y') }})</small></h2>
                    </div>
                    <div class="card-body">
                        {!! $version->content !!}
                    </div>
                </div>
            @endforeach
        </div>
        
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header">
                    <h5>Versions disponibles</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <ul class="list-group">
                        @foreach($versions as $version)
                            <li class="list-group-item">
                                <a href="#version-{{ $version->id }}" class="d-flex justify-content-between align-items-center text-decoration-none">
                                    <div>
                                        <strong>v{{ $version->version_number }}</strong>
                                        <br>
                                        <small>{{ $version->release_date->format('d/m/Y') }}</small>
                                    </div>
                                    <i class="fas fa-chevron-right"></i>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Ajouter des IDs aux éléments de version pour le défilement
    document.addEventListener('DOMContentLoaded', function() {
        const versionCards = document.querySelectorAll('.card.mb-4');
        versionCards.forEach((card, index) => {
            const version = {{ $versions->pluck('id')->toJson() }}[index];
            card.id = 'version-' + version;
        });
        
        // Défilement fluide pour les liens d'ancrage
        document.querySelectorAll('a[href^="#version-"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                document.querySelector(this.getAttribute('href')).scrollIntoView({
                    behavior: 'smooth'
                });
            });
        });
    });
</script>
@endpush