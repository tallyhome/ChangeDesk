@extends('layouts.app')

@section('title', __('app.nav.home'))

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ $page->title }}</div>
                <div class="card-body">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection