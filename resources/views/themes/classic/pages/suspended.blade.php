@extends('layouts.app')
@section('content')
<div class="container py-5 text-center">
  <h1>Projet suspendu</h1>
  <p class="lead text-muted">{{ $tenant->suspension_reason ?: 'Ce changelog est temporairement indisponible.' }}</p>
</div>
@endsection
