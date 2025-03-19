@extends('layouts.app')

@section('title', 'Changelog')

@include('partials.lightbox')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Changelog</h1>
            
            @foreach($versions as $version)
            <div class="card mb-4">
                <div class="card-header">
                    <h2>Version {{ $version->version_number }}</h2>
                    <small>{{ $version->release_date->format('d/m/Y') }}</small>
                </div>
                <div class="card-body">
                    <div class="content-area">
                        {!! $version->content !!}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection