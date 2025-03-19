@extends('layouts.app')

@section('title', 'Accueil')

@include('partials.lightbox')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ $page->title }}</h1>
            <div class="content-area">
                {!! $page->content !!}
            </div>
        </div>
    </div>
</div>
@endsection
