@extends('layouts.admin')

@section('title', 'Modifier une catégorie Wiki')

@section('content')
<div class="container-fluid py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.wiki.index') }}">Wiki</a></li>
            <li class="breadcrumb-item"><a href="{{ route('admin.wiki.categories') }}">Catégories</a></li>
            <li class="breadcrumb-item active" aria-current="page">Modifier une catégorie</li>
        </ol>
    </nav>

    <div class="card">
        <div class="card-header bg-light">
            <h1 class="h2 mb-0">Modifier la catégorie Wiki</h1>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.wiki.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="name" class="form-label">Nom</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb