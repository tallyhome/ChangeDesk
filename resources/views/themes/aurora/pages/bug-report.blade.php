@extends('themes.aurora.layouts.app')
@section('title', 'Rapport de bugs')
@section('content')
@php use App\Support\ThemeUi; @endphp
<p class="au-hero-line">Bugs signalés. Suivis. <span style="color:var(--au-accent)">Résolus.</span></p>
<p class="au-lead" style="margin-bottom:1.25rem">Du signalement à la résolution — un suivi clair pour vos utilisateurs.</p>

<div class="au-panel">
  <div class="au-panel-pad">
    <div class="au-panel-head">
      <div>
        <h1>Rapporter un bug</h1>
        <p>Décrivez le problème — on le suit jusqu’à résolution.</p>
      </div>
    </div>

    @if(session('success'))
      <div class="au-alert">{{ session('success') }}</div>
    @endif

    <form class="au-form" action="{{ route('bug-report.store') }}" method="POST">
      @csrf
      <div class="au-bug-layout">
        <div>
          <div class="field">
            <label for="title">Résumé</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="Ex. Bouton Enregistrer inactif" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="field">
            <label for="description">Description / étapes</label>
            <textarea id="description" name="description" rows="6" placeholder="Contexte, étapes pour reproduire, résultat attendu…" required>{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div>
          <div class="field">
            <label for="name">Votre nom</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
          </div>
          <div class="field">
            <label for="email">Votre email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
          </div>
          <div class="field">
            <label for="captcha">Captcha — combien font 2 + 3 ?</label>
            <input type="text" id="captcha" name="captcha" required>
            @error('captcha')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
      <div class="au-form-actions">
        <a href="{{ url('/') }}" class="au-btn-ghost">Annuler</a>
        <button type="submit" class="au-btn">Soumettre le bug</button>
      </div>
    </form>
  </div>
</div>

<div class="au-panel">
  <div class="au-panel-pad">
    <div class="au-panel-head">
      <div>
        <h2>Bugs récemment signalés</h2>
        <p>Suivi public des derniers tickets.</p>
      </div>
    </div>

    <table class="au-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Résumé</th>
          <th>Priorité</th>
          <th>Statut</th>
          <th>Mis à jour</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentBugs as $bug)
          @php
            $prio = strtolower((string) ($bug->priority ?? 'medium'));
            $sevClass = match ($prio) {
              'high', 'critical', 'élevée', 'elevee' => 'high',
              'low', 'faible' => 'low',
              default => 'medium',
            };
            $sevLabel = match ($sevClass) {
              'high' => 'Élevée',
              'low' => 'Faible',
              default => 'Moyenne',
            };
            $st = strtolower((string) $bug->status);
            $stClass = match (true) {
              in_array($st, ['resolved', 'closed', 'completed'], true) => 'done',
              $st === 'in_progress' => 'progress',
              default => 'open',
            };
          @endphp
          <tr class="au-clickable" onclick="location.href='{{ route('bug-report.show', $bug->id) }}'">
            <td class="au-muted">#{{ $bug->id }}</td>
            <td><strong style="color:#fff">{{ $bug->title }}</strong></td>
            <td><span class="au-sev {{ $sevClass }}"><span class="dot"></span>{{ $sevLabel }}</span></td>
            <td><span class="au-status {{ $stClass }}"><span class="dot"></span>{{ ThemeUi::statusLabel($bug->status) }}</span></td>
            <td class="au-muted">{{ $bug->updated_at?->format('d/m/Y') }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="au-muted">Aucun bug signalé récemment.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
