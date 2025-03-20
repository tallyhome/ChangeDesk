@extends('layouts.app')

@section('title', 'Signaler un bug')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1>Signaler un bug</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('bug-report.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du bug</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description détaillée</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required></textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Votre nom</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Votre email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="captcha" class="form-label">Pour vérifier que vous n'êtes pas un robot, combien font 2 + 3 ?</label>
                            <input type="text" class="form-control @error('captcha') is-invalid @enderror" id="captcha" name="captcha" required>
                            @error('captcha')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary">Soumettre le rapport</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Bugs récemment signalés</h5>
                </div>
                <div class="card-body">
                    @if($recentBugs->count() > 0)
                        <ul class="list-group">
                            @foreach($recentBugs as $bug)
                                <li class="list-group-item">
                                    <a href="{{ route('bug-report.show', $bug->id) }}" class="text-decoration-none">
                                        <h6 class="mb-1">{{ $bug->title }}</h6>
                                        <small class="text-muted">Signalé le {{ $bug->created_at->format('d/m/Y') }}</small>
                                        <span class="badge bg-{{ $bug->status == 'open' ? 'danger' : ($bug->status == 'in_progress' ? 'info' : 'success') }} float-end">
                                            {{ $bug->status == 'open' ? 'Ouvert' : ($bug->status == 'in_progress' ? 'En cours' : 'Résolu') }}
                                        </span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p class="text-center mb-0">Aucun bug signalé récemment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection