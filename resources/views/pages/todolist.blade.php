@extends('layouts.app')

@section('title', 'Prochaines fonctionnalités')

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">Prochaines fonctionnalités</h1>
            
            <div class="row">
                @foreach($todoItems as $item)
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $item->title }}</h5>
                                <span class="badge {{ $item->completion_percentage >= 75 ? 'bg-success' : ($item->completion_percentage >= 25 ? 'bg-warning' : 'bg-danger') }}">
                                    {{ $item->completion_percentage }}%
                                </span>
                            </div>
                            <div class="card-body">
                                <div class="progress mb-3">
                                    <div class="progress-bar {{ $item->completion_percentage >= 75 ? 'bg-success' : ($item->completion_percentage >= 25 ? 'bg-warning' : 'bg-danger') }}" 
                                         role="progressbar" 
                                         style="width: {{ $item->completion_percentage }}%" 
                                         aria-valuenow="{{ $item->completion_percentage }}" 
                                         aria-valuemin="0" 
                                         aria-valuemax="100">
                                        {{ $item->completion_percentage }}%
                                    </div>
                                </div>
                                <p>{{ $item->description }}</p>
                                @if($item->estimated_completion_date)
                                    <p class="text-muted mt-3">
                                        <i class="fas fa-calendar-alt"></i> Date estimée : {{ $item->estimated_completion_date->format('d/m/Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection