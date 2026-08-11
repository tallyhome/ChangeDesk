@extends('layouts.admin')
@section('title', 'Apparence')
@section('content')
<div class="container" style="max-width:720px">
  <h1 class="mb-3">Apparence publique</h1>
  <p class="text-muted">Choisissez le rendu de votre changelog public. Les thèmes disponibles dépendent de votre plan.</p>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  <form method="POST" action="{{ route('admin.appearance.update') }}" class="card card-body">
    @csrf @method('PUT')
    @foreach($themes as $theme)
      <div class="form-check mb-3">
        <input class="form-check-input" type="radio" name="visual_theme" id="theme_{{ $theme }}" value="{{ $theme }}" @checked($tenant->visual_theme === $theme)>
        <label class="form-check-label" for="theme_{{ $theme }}">
          <strong>{{ ucfirst($theme) }}</strong>
          @if($theme === 'classic') — design actuel Bootstrap @endif
          @if($theme === 'midnight') — sombre teal (type marketplace) @endif
          @if($theme === 'editorial') — chronologique indigo @endif
          @if($theme === 'aurora') — glass / gradient @endif
        </label>
      </div>
    @endforeach
    <button class="btn btn-primary">Enregistrer</button>
  </form>
</div>
@endsection
