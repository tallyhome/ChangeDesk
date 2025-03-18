@extends('layouts.app')

@section('title', 'Politique de confidentialité')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Politique de confidentialité</div>
                <div class="card-body">
                    @if(isset($page) && $page)
                        {!! $page->content !!}
                    @else
                        <p>Politique de confidentialité du site.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection