@extends('superadmin.layout')
@section('title', 'Mises à jour')
@section('content')
<div class="sa-top">
  <div>
    <h1>Mises à jour</h1>
    <div class="text-muted">Mises à jour depuis <code>{{ $repo }}</code> (auth embarquée, hors .env)</div>
  </div>
  <a href="{{ route('superadmin.updates.index', ['fresh' => 1]) }}" class="btn btn-outline-secondary">Rafraîchir</a>
</div>

@if($error)
  <div class="alert alert-warning">Impossible de contacter GitHub : {{ $error }}</div>
@endif

<div class="row g-4">
  <div class="col-lg-5">
    <div class="sa-card p-4">
      <div class="label text-muted fw-semibold">Version installée</div>
      <div class="display-6 fw-bold">v{{ $current }}</div>
    </div>
  </div>
  <div class="col-lg-7">
    <div class="sa-card p-4">
      @if($release)
        <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
          <div>
            <div class="label text-muted fw-semibold">Dernière release GitHub</div>
            <div class="h3 mb-1">v{{ $release['tag'] }} — {{ $release['name'] }}</div>
            @if($release['published_at'])
              <div class="small text-muted">Publiée le {{ \Illuminate\Support\Carbon::parse($release['published_at'])->timezone(config('app.timezone'))->format('d/m/Y H:i') }}</div>
            @endif
          </div>
          @if($available)
            <span class="badge text-bg-success">Mise à jour disponible</span>
          @else
            <span class="badge text-bg-secondary">À jour</span>
          @endif
        </div>

        @if(!empty($release['body']))
          <hr>
          <div class="small" style="white-space:pre-wrap;max-height:220px;overflow:auto">{{ $release['body'] }}</div>
        @endif

        @if($available)
          <hr>
          <form method="POST" action="{{ route('superadmin.updates.apply') }}" onsubmit="return confirm('Appliquer la mise à jour maintenant ? Un backup DB est recommandé.');">
            @csrf
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="confirm" value="1" id="confirm" required>
              <label class="form-check-label" for="confirm">
                J’ai sauvegardé la base et j’accepte d’écraser le code applicatif (hors <code>.env</code> / <code>storage</code> / <code>vendor</code>).
              </label>
            </div>
            <button class="btn btn-accent">Installer v{{ $release['tag'] }}</button>
            @if($release['url'])
              <a class="btn btn-outline-secondary" href="{{ $release['url'] }}" target="_blank" rel="noopener">Voir sur GitHub</a>
            @endif
          </form>
        @elseif($release['url'])
          <div class="mt-3">
            <a class="btn btn-outline-secondary" href="{{ $release['url'] }}" target="_blank" rel="noopener">Voir sur GitHub</a>
          </div>
        @endif
      @else
        <p class="mb-0 text-muted">Aucune release trouvée. Publiez une release GitHub sur <code>{{ $repo }}</code>. Si le dépôt est privé, renseignez le PAT dans <code>app/Support/GithubUpdateAuth.php</code>.</p>
      @endif
    </div>
  </div>
</div>

<div class="sa-card p-3 mt-4 small text-muted">
  <strong>Notes cPanel :</strong> l’extension PHP <code>zip</code> et <code>allow_url_fopen</code>/curl sont nécessaires.
  Après une MAJ majeures des dépendances, relancez <code>composer install --no-dev --optimize-autoloader</code> sur le serveur.
  Pas besoin de <code>npm run build</code> pour l’admin (Bootstrap CDN) ; uniquement si vous modifiez les assets Vite.
</div>
@endsection
