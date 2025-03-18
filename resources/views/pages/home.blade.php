@extends('layouts.app')

@section('title', 'Accueil')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Accueil</div>
                <div class="card-body">
                    @if(isset($page) && $page)
                        {!! $page->content !!}
                    @else
                        <p>Bienvenue sur notre site.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection