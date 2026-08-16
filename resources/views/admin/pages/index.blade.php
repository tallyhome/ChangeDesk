@extends('layouts.admin')

@section('title', __('app.admin.pages'))

@section('content')
<div class="container" style="max-width:800px">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3 mb-0">{{ __('app.admin.pages') }}</h1>
    </div>
    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>{{ __('app.common.title') }}</th>
                        <th>Slug</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pages as $page)
                    <tr>
                        <td class="fw-semibold">{{ $page->title }}</td>
                        <td><code>{{ $page->slug }}</code></td>
                        <td class="text-end">
                            <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary btn-sm">{{ __('app.common.edit') }}</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
