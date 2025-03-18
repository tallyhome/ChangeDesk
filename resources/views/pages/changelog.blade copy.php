@extends('layouts.app')

@section('title', 'Changelog')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Changelog</div>
                <div class="card-body">
                    @if(isset($page) && $page->content)
                        {!! $page->content !!}
                    @else
                        <p>Aucun contenu disponible pour le moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection