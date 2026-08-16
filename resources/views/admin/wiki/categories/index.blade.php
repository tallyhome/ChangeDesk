@extends('layouts.admin')

@section('title', __('app.common.categories'))

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2">{{ __('app.common.categories') }}</h1>
        <div>
            <a href="{{ route('admin.wiki.categories.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i> {{ __('app.admin.new_category') }}
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card">
        <div class="card-header bg-light">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0">Liste des catégories</h5>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>{{ __('app.common.name') }}</th>
                            <th>Slug</th>
                            <th>{{ __('app.common.description') }}</th>
                            <th>{{ __('app.common.articles') }}</th>
                            <th>Ordre</th>
                            <th class="text-end">{{ __('app.common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                <td><code>{{ $category->slug }}</code></td>
                                <td>{{ Str::limit($category->description, 50) }}</td>
                                <td>{{ $category->articles_count }}</td>
                                <td>{{ $category->order }}</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.wiki.categories.edit', $category) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.wiki.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" data-confirm="{{ __('app.admin.confirm_delete') }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">Aucune catégorie trouvée</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection