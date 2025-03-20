@extends('layouts.app')

@section('title', 'Fonctionnalités à venir')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Fonctionnalités à venir</h1>
    
    <div class="row">
        @foreach($todoItems as $item)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <div class="card-text">{!! $item->description !!}</div>
                        
                        <!-- Barre de progression -->
                        <div class="progress mb-3">
                            <div class="progress-bar bg-{{ $item->color ?? 'primary' }}" role="progressbar" 
                                style="width: {{ $item->progress ?? 0 }}%" 
                                aria-valuenow="{{ $item->progress ?? 0 }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">{{ $item->progress ?? 0 }}%</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="fas fa-calendar-alt"></i> Date estimée : 
                                @if(is_string($item->expected_date))
                                    {{ $item->expected_date }}
                                @elseif($item->expected_date)
                                    {{ $item->expected_date->format('d/m/Y') }}
                                @else
                                    Non définie
                                @endif
                            </div>
                            <div>
                                @if($item->status == 'pending')
                                    <span class="badge bg-warning">En attente</span>
                                @elseif($item->status == 'in_progress')
                                    <span class="badge bg-info">En cours</span>
                                @elseif($item->status == 'completed')
                                    <span class="badge bg-success">Terminé</span>
                                @else
                                    <span class="badge bg-secondary">{{ $item->status }}</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection