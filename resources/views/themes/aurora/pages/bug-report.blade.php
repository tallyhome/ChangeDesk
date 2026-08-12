@extends('themes.aurora.layouts.app')
@section('title', 'Signaler un bug')
@section('content')
<span class="au-chip">Support</span>
<h1 class="au-title" style="margin-top:.75rem">Signaler un bug</h1>
<p class="au-lead">Un formulaire clair, des retours suivis — sans friction.</p>

<div class="au-grid">
  <div class="au-card">
    @if(session('success'))
      <div class="au-alert">{{ session('success') }}</div>
    @endif
    <form class="au-form" action="{{ route('bug-report.store') }}" method="POST">
      @csrf
      <div class="field">
        <label for="title">Titre du bug</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="field">
        <label for="description">Description détaillée</label>
        <textarea id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="row-2">
        <div class="field">
          <label for="name">Votre nom</label>
          <input type="text" id="name" name="name" value="{{ old('name') }}">
        </div>
        <div class="field">
          <label for="email">Votre email</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}">
        </div>
      </div>
      <div class="field">
        <label for="captcha">Pour vérifier que vous n'êtes pas un robot, combien font 2 + 3 ?</label>
        <input type="text" id="captcha" name="captcha" required style="max-width:8rem">
        @error('captcha')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="au-btn">Soumettre le rapport</button>
    </form>
  </div>

  <aside class="au-side">
    <div class="au-side-head">Bugs récemment signalés</div>
    <ul class="au-list" style="padding:0 1rem 1rem">
      @forelse($recentBugs as $bug)
        <li>
          <a href="{{ route('bug-report.show', $bug->id) }}">{{ $bug->title }}</a>
          <div class="au-muted" style="font-size:.88rem;margin-top:.2rem">
            Signalé le {{ $bug->created_at->format('d/m/Y') }}
            · {{ $bug->status == 'open' ? 'Ouvert' : ($bug->status == 'in_progress' ? 'En cours' : 'Résolu') }}
          </div>
        </li>
      @empty
        <li class="au-muted" style="border:0">Aucun bug signalé récemment.</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection
