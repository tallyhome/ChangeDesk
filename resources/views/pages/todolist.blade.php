@extends(\App\Support\ThemeView::layout())

@section('title', __('app.public.todolist_title'))

@section('content')
@php use App\Support\ThemeUi; @endphp
<div class="container py-5">
    <h1 class="mb-4">{{ __('app.public.todolist_title') }}</h1>
    
    <div class="row">
        @foreach($todoItems as $item)
            @php
                $progress = $item->progress ?? 0;
                $barColor = ThemeUi::progressColor($item->color ?? 'primary');
            @endphp
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->title }}</h5>
                        <div class="card-text">{!! $item->description !!}</div>
                        
                        <div class="progress mb-3">
                            <div class="progress-bar bg-{{ $item->color ?? 'primary' }}" role="progressbar" 
                                style="width: {{ $progress }}%;background-color:{{ $barColor }}!important" 
                                aria-valuenow="{{ $progress }}" 
                                aria-valuemin="0" 
                                aria-valuemax="100">{{ $progress }}%</div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <div>
                                <i class="fas fa-calendar-alt"></i> {{ __('app.common.estimated_date') }} :
                                @if(is_string($item->expected_date))
                                    {{ $item->expected_date }}
                                @elseif($item->expected_date)
                                    {{ \App\Support\Locale::formatDate($item->expected_date) }}
                                @else
                                    {{ __('app.common.undefined') }}
                                @endif
                            </div>
                            <div>
                                <span class="badge bg-{{ $item->status == 'completed' ? 'success' : ($item->status == 'in_progress' ? 'info' : 'secondary') }}">
                                    {{ ThemeUi::statusLabel($item->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection