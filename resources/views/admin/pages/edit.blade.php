@extends('layouts.admin')

@section('title', 'Éditer — '.$page->title)

@include('partials.tinymce')

@section('content')
<div class="container" style="max-width:960px">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">Éditer : {{ $page->title }}</h1>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('app.common.back') }}</a>
    </div>
    <div class="card card-body">
        <form action="{{ route('admin.pages.update', $page->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title" class="form-label fw-semibold">{{ __('app.common.title') }}</label>
                <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $page->title) }}" required>
            </div>

            <div class="mb-3">
                <label for="content" class="form-label fw-semibold">Contenu</label>
                <textarea class="form-control summernote" id="content" name="content" rows="15">{{ old('content', $page->content) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('app.common.save') }}</button>
        </form>
    </div>
</div>
@endsection
