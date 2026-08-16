@extends('superadmin.layout')
@section('title', 'Site vitrine')
@section('content')
<div class="sa-top">
  <div>
    <h1>Thème du site vitrine</h1>
    <div class="text-muted">Choisissez l’apparence de la page d’accueil publique (domaine central).</div>
  </div>
  <a href="{{ url('/') }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm"><i class="fas fa-arrow-up-right-from-square me-1"></i> Voir le site</a>
</div>

<div class="row g-3">
  @foreach($themes as $slug => $theme)
    @php $isActive = $slug === $current; @endphp
    <div class="col-12 col-xl-4">
      <div class="sa-card h-100 d-flex flex-column p-0 overflow-hidden" style="{{ $isActive ? 'outline:2px solid '.$theme['accent'].';outline-offset:-2px' : '' }}">
        <div class="d-flex justify-content-between align-items-center gap-2 p-3 border-bottom border-secondary-subtle">
          <div>
            <div class="fw-semibold d-flex align-items-center gap-2">
              <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:{{ $theme['accent'] }}"></span>
              {{ $theme['label'] }}
            </div>
            <code class="small text-muted">{{ $slug }}</code>
          </div>
          @if($isActive)
            <span class="badge bg-success">Actif</span>
          @endif
        </div>

        <div style="position:relative;height:230px;overflow:hidden;background:#0b1220">
          <iframe
            src="{{ route('superadmin.landing.preview', $slug) }}"
            title="Aperçu {{ $theme['label'] }}"
            loading="lazy" tabindex="-1"
            style="position:absolute;top:0;left:0;width:1440px;height:1150px;border:0;transform:scale(.32);transform-origin:top left;pointer-events:none"></iframe>
        </div>

        <div class="p-3 d-flex flex-column gap-3 flex-grow-1">
          <p class="small text-muted mb-0">{{ $theme['description'] }}</p>
          <div class="mt-auto d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('superadmin.landing.preview', $slug) }}" target="_blank" rel="noopener">Aperçu plein écran</a>
            @if($isActive)
              <button class="btn btn-sm btn-success" disabled>Thème en place</button>
            @else
              <form method="POST" action="{{ route('superadmin.landing.update') }}" data-confirm="Activer le thème « {{ $theme['label'] }} » sur le site vitrine ?">
                @csrf
                <input type="hidden" name="theme" value="{{ $slug }}">
                <button class="btn btn-sm btn-accent">Activer</button>
              </form>
            @endif
          </div>
        </div>
      </div>
    </div>
  @endforeach
</div>

<div class="sa-card mt-3 p-3">
  <h2 class="h6">Bon à savoir</h2>
  <ul class="small text-muted mb-0">
    <li>Le choix s’applique uniquement à la page d’accueil du domaine central — les thèmes visiteurs des tenants restent gérés par chaque client.</li>
    <li>Valeur de secours si la base est indisponible : <code>LANDING_THEME</code> dans le <code>.env</code>.</li>
    <li>Après un changement, pensez à vider le cache de vue si vous utilisez <code>view:cache</code>.</li>
  </ul>
</div>
@endsection
