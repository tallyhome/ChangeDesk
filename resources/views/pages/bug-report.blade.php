@extends('layouts.app')

@section('title', 'Signaler un bug')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4">Signaler un bug</h1>
            
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif
            
            <div class="card mb-4">
                <div class="card-body">
                    <form action="{{ route('bug-report.store') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre du bug</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="description" class="form-label">Description détaillée</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reporter_name" class="form-label">Votre nom</label>
                                    <input type="text" class="form-control @error('reporter_name') is-invalid @enderror" id="reporter_name" name="reporter_name" value="{{ old('reporter_name') }}" required>
                                    @error('reporter_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="reporter_email" class="form-label">Votre email</label>
                                    <input type="email" class="form-control @error('reporter_email') is-invalid @enderror" id="reporter_email" name="reporter_email" value="{{ old('reporter_email') }}" required>
                                    @error('reporter_email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        
                        <!-- Champ honeypot pour détecter les robots -->
                        <div class="mb-3" style="display: none;">
                            <label for="website">Website (ne pas remplir)</label>
                            <input type="text" name="website" id="website">
                        </div>
                        
                        <!-- Question simple anti-robot -->
                        <div class="mb-3">
                            <label for="human_check" class="form-label">Pour vérifier que vous n'êtes pas un robot, combien font 2 + 3 ?</label>
                            <input type="text" class="form-control @error('human_check') is-invalid @enderror" id="human_check" name="human_check" required>
                            @error('human_check')
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
                    <h5>Bugs récemment signalés</h5>
                </div>
                <div class="card-body" style="max-height: 600px; overflow-y: auto;">
                    <ul class="list-group">
                        @foreach($bugReports->where('status', 'resolved')->take(5) as $report)
                            <li class="list-group-item">
                                <div>
                                    <h6>{{ $report->title }}</h6>
                                    <p class="text-muted mb-0">
                                        <small>Signalé le {{ $report->created_at->format('d/m/Y') }}</small>
                                        <span class="badge bg-success">Résolu</span>
                                    </p>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection