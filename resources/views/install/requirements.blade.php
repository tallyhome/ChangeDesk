@extends('install.layout')
@section('step2', 'on')
@section('content')
<h1 class="h3 mb-3">Prérequis serveur</h1>
<ul class="list-group mb-3">
@foreach($checks as $label => $pass)
  <li class="list-group-item d-flex justify-content-between">
    <span>{{ $label }}</span>
    @if($pass)<span class="text-success fw-bold">OK</span>@else<span class="text-danger fw-bold">KO</span>@endif
  </li>
@endforeach
</ul>
@if($ok)
  <a href="{{ route('install.database') }}" class="btn btn-success">Continuer</a>
@else
  <div class="alert alert-warning">Corrigez les points en KO puis rechargez cette page.</div>
  <a href="{{ route('install.requirements') }}" class="btn btn-outline-secondary">Recharger</a>
@endif
@endsection
