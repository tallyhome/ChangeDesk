@extends('themes.editorial.layouts.app')
@section('title', 'Signaler un bug')
@section('content')
<p class="ed-kicker">Support</p>
<h1 class="ed-title">Signaler un bug</h1>
<p class="ed-lead">Décrivez le problème — on le suit jusqu’à résolution.</p>

<div class="ed-grid">
  <div class="ed-card">
    @if(session('success'))
      <div class="ed-alert">{{ session('success') }}</div>
    @endif
    <form class="ed-form" action="{{ route('bug-report.store') }}" method="POST">
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
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="field">
          <label for="email">Votre email</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="field">
        <label for="captcha">Pour vérifier que vous n'êtes pas un robot, combien font 2 + 3 ?</label>
        <input type="text" id="captcha" name="captcha" required style="max-width:8rem">
        @error('captcha')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="ed-btn">Soumettre le rapport</button>
    </form>
  </div>

  <aside class="ed-side">
    <div class="ed-side-head">Bugs récemment signalés</div>
    <ul class="ed-list" style="padding:0 1rem 1rem">
      @forelse($recentBugs as $bug)
        <li>
          <a href="{{ route('bug-report.show', $bug->id) }}">{{ $bug->title }}</a>
          <div class="ed-muted" style="font-size:.88rem;margin-top:.2rem">
            Signalé le {{ $bug->created_at->format('d/m/Y') }}
            · {{ $bug->status == 'open' ? 'Ouvert' : ($bug->status == 'in_progress' ? 'En cours' : 'Résolu') }}
          </div>
        </li>
      @empty
        <li class="ed-muted" style="border:0">Aucun bug signalé récemment.</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection
