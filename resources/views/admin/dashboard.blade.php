@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <h2>Bienvenue dans l'administration</h2>
                    <p>Utilisez le menu de navigation pour gérer votre site.</p>
                    
                    <!-- Suppression de la référence à $pages qui cause l'erreur -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('title', 'Administration')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header">
                    <h2>Dashboard</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Dernière modification</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pages as $page)
                                <tr>
                                    <td>{{ $page->title }}</td>
                                    <td>{{ $page->updated_at ? $page->updated_at->format('d/m/Y H:i') : 'Non modifié' }}</td>
                                    <td>
                                        <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-primary btn-sm">
                                            Éditer
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('head')
<!-- Font Awesome -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script>
document.getElementById('searchInput').addEventListener('keyup', function() {
    let searchText = this.value.toLowerCase();
    let tableRows = document.querySelectorAll('tbody tr');
    
    tableRows.forEach(row => {
        let text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchText) ? '' : 'none';
    });
});
</script>
@endsection