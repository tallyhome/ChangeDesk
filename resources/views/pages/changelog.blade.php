@extends('layouts.app')

@section('title', 'Changelog')

@section('content')
<div class="container">
    <div class="row">
        <!-- Contenu principal -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Changelog</div>
                <div class="card-body">
                    @forelse($versions as $version)
                        <div id="v{{ str_replace('.', '-', $version->version_number) }}">
                            <h2>v{{ $version->version_number }} ({{ $version->release_date->format('d/m/Y') }})</h2>
                            {!! $version->content !!}
                        </div>
                        @if(!$loop->last)
                            <hr>
                        @endif
                    @empty
                        <p>Aucune version disponible pour le moment.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Barre latérale -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Sur cette page</div>
                <div class="card-body">
                    <div class="nav flex-column">
                        @foreach($versions as $version)
                            <a href="#v{{ str_replace('.', '-', $version->version_number) }}" class="nav-link">
                                v{{ $version->version_number }} ({{ $version->release_date->format('d/m/Y') }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.nav-link {
    padding: 0.25rem 1rem;
    color: #6c757d;
}
.nav-link:hover {
    color: #0d6efd;
    background-color: #f8f9fa;
}
</style>
@endsection