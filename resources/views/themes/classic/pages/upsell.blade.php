@extends('layouts.app')
@section('content')
<div class="container py-5 text-center">
  <h1>Module non inclus</h1>
  <p class="lead">Le module <strong>{{ $module }}</strong> n'est pas disponible sur le plan actuel.</p>
  <p class="text-muted">Passez au plan Pro ou Business pour débloquer cette fonctionnalité.</p>
</div>
@endsection
