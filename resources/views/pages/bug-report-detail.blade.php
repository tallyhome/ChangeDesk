@extends('layouts.app')

@section('title', 'Détails du bug - ' . $bug->title)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1>{{ $bug->title }}</h1>
                <a href="{{ route('bug-report') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour à la liste
                </a>
            </div>
            
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Statut:</strong></p>
                            <span class="badge bg-{{ $bug->status == 'open' ? 'danger' : ($bug->status == 'in_progress' ? 'info' : 'success') }} p-2">
                                {{ $bug->status == 'open' ? 'Ouvert' : ($bug->status == 'in_progress' ? 'En cours' : 'Résolu') }}
                            </span>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-1"><strong>Signalé le:</strong></p>
                            <p>{{ $bug->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                    
                    @if($bug->progress > 0)
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1"><strong>Progression:</strong></p>
                            <div class="progress">
                                <div class="progress-bar bg-{{ $bug->color ?? 'primary' }}" role="progressbar" 
                                    style="width: {{ $bug->progress }}%" 
                                    aria-valuenow="{{ $bug->progress }}" 
                                    aria-valuemin="0" 
                                    aria-valuemax="100">{{ $bug->progress }}%</div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <p class="mb-1"><strong>Description:</strong></p>
                            <div class="p-3 bg-light rounded">
                                {!! $bug->description !!}
                            </div>
                        </div>
                    </div>
                    
                    @if($bug->expected_fix_date)
                    <div class="row">
                        <div class="col-md-12">
                            <p class="mb-1"><strong>Date prévue de correction:</strong></p>
                            <p>{{ $bug->expected_fix_date->format('d/m/Y') }}</p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection