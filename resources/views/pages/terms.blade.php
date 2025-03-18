@extends('layouts.app')

@section('title', 'Conditions d\'utilisation')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Conditions d'utilisation</div>
                <div class="card-body">
                    @if(isset($page) && $page)
                        {!! $page->content !!}
                    @else
                        <p>Conditions d'utilisation du site.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection