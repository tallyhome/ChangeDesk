@extends('layouts.admin')

@section('title', 'Gestion du Changelog')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>Gestion des Versions</h1>
            @if(Route::has('admin.changelog.create'))
                <a href="{{ route('admin.changelog.create') }}" class="btn btn-success mb-3">Nouvelle Version</a>
            @endif

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Version</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($versions as $version)
                        <tr>
                            <td>{{ $version->version_number }}</td>
                            <td>{{ $version->release_date->format('d/m/Y') }}</td>
                            <td>
                                <a href="{{ route('admin.changelog.edit', $version->id) }}" class="btn btn-sm btn-primary">Modifier</a>
                                <form action="{{ route('admin.changelog.destroy', $version->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Confirmer la suppression ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection