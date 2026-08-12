@extends('superadmin.layout')
@section('title', 'Mises à jour')
@section('content')
<div class="sa-top">
  <div>
    <h1>Mises à jour</h1>
    <div class="text-muted">Mises à jour depuis <code>{{ $repo }}</code> — migrations &amp; caches automatiques</div>
  </div>
  <a href="{{ route('superadmin.updates.index', ['fresh' => 1]) }}" class="btn btn-outline-secondary">Rafraîchir</a>
</div>

@if($error)
  <div class="alert alert-warning">Impossible de contacter GitHub : {{ $error }}</div>
@endif
<div id="updateAlert" class="alert d-none"></div>
<div id="updateSuccessBanner" class="alert alert-success border-0 shadow-sm d-none align-items-center gap-3 flex-wrap mb-4" style="background:linear-gradient(135deg,#ecfdf5,#d1fae5);border-left:5px solid #10b981!important">
  <div class="display-6 mb-0">✓</div>
  <div class="flex-grow-1">
    <div class="fw-bold fs-5 mb-0" id="updateSuccessTitle">Mise à jour réussie</div>
    <div class="small mb-0" id="updateSuccessText">Tout est en place. Aucune action SSH nécessaire.</div>
  </div>
  <span class="badge text-bg-success fs-6 px-3 py-2">OK</span>
  <button type="button" class="btn-close" id="updateSuccessClose" aria-label="Fermer"></button>
</div>

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
            <span class="badge text-bg-success">À jour ✓</span>
          @endif
        </div>

        @if(!empty($release['body']))
          <hr>
          <div class="small" style="white-space:pre-wrap;max-height:160px;overflow:auto">{{ $release['body'] }}</div>
        @endif

        @if($available)
          <hr>
          <form id="updateForm">
            @csrf
            <div class="form-check mb-3">
              <input class="form-check-input" type="checkbox" name="confirm" value="1" id="confirm" required>
              <label class="form-check-label" for="confirm">
                J’ai sauvegardé la base et j’accepte d’écraser le code (hors <code>.env</code> / <code>storage</code> / <code>vendor</code>).
              </label>
            </div>
            <button type="submit" class="btn btn-accent" id="updateBtn">Installer v{{ $release['tag'] }}</button>
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
        <p class="mb-0 text-muted">Aucune release trouvée sur <code>{{ $repo }}</code>.</p>
      @endif
    </div>
  </div>
</div>

<div class="sa-card p-4 mt-4" id="progressCard">
  <div class="d-flex justify-content-between align-items-center mb-2">
    <strong>Progression</strong>
    <span id="progressPct" class="fw-bold">{{ (int)($progress['percent'] ?? 0) }}%</span>
  </div>
  <div class="progress mb-2" style="height:22px">
    <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-success" role="progressbar"
         style="width: {{ (int)($progress['percent'] ?? 0) }}%">{{ (int)($progress['percent'] ?? 0) }}%</div>
  </div>
  <div id="progressStep" class="fw-semibold">{{ $progress['step'] ?? 'En attente' }}</div>
  <div id="progressDetail" class="small text-muted mb-3">{{ $progress['detail'] ?? 'Lancez une mise à jour pour suivre l’avancement en direct.' }}</div>
  <div class="small text-muted mb-1">Journal</div>
  <pre id="progressLog" class="small bg-dark text-light p-3 rounded mb-0" style="max-height:220px;overflow:auto;white-space:pre-wrap">@foreach(($progress['logs'] ?? []) as $log)[{{ $log['t'] ?? '' }}] {{ $log['m'] ?? '' }}
@endforeach</pre>
</div>

<div class="sa-card p-3 mt-4 small text-muted">
  Après chaque MAJ, ChanLog exécute automatiquement :
  <code>migrate --force</code>, <code>optimize:clear</code>, <code>storage:link</code>,
  <code>config:cache</code>, <code>route:cache</code>, <code>view:cache</code>.
  Aucune commande SSH n’est nécessaire (sauf <code>composer install</code> si les dépendances PHP changent).
</div>

<script>
(() => {
  const form = document.getElementById('updateForm');
  const bar = document.getElementById('progressBar');
  const pctEl = document.getElementById('progressPct');
  const stepEl = document.getElementById('progressStep');
  const detailEl = document.getElementById('progressDetail');
  const logEl = document.getElementById('progressLog');
  const btn = document.getElementById('updateBtn');
  const alertBox = document.getElementById('updateAlert');
  const banner = document.getElementById('updateSuccessBanner');
  const bannerTitle = document.getElementById('updateSuccessTitle');
  const bannerText = document.getElementById('updateSuccessText');
  const progressUrl = @json(route('superadmin.updates.progress'));
  const applyUrl = @json(route('superadmin.updates.apply'));
  const currentVersion = @json($current);
  let timer = null;
  let successShown = false;

  function hideSuccessBanner() {
    banner.classList.add('d-none');
    banner.classList.remove('d-flex');
  }

  function showSuccess(p, { celebrate = false, reload = false, autoHideMs = 8000 } = {}) {
    const to = (p.result && p.result.to) || p.to || currentVersion;
    const title = 'Mise à jour réussie';
    const text = to
      ? `La version v${to} est installée. Migrations et caches ont été appliqués.`
      : (p.detail || 'Mise à jour appliquée avec succès.');
    banner.classList.remove('d-none');
    banner.classList.add('d-flex');
    bannerTitle.textContent = title;
    bannerText.textContent = text;
    progressCardSuccess();
    if (autoHideMs > 0) {
      setTimeout(hideSuccessBanner, autoHideMs);
    }
    const key = 'chanlog_update_ok_' + (p.updated_at || to || 'done');
    if (celebrate && !successShown && !sessionStorage.getItem(key)) {
      successShown = true;
      sessionStorage.setItem(key, '1');
      const after = () => { if (reload) location.reload(); };
      if (window.ChanSwal) {
        window.ChanSwal.success(title, text).then(after);
      } else {
        setTimeout(after, 1200);
      }
    }
  }

  document.getElementById('updateSuccessClose')?.addEventListener('click', hideSuccessBanner);

  function progressCardSuccess() {
    stepEl.classList.add('text-success');
    detailEl.classList.remove('text-muted');
    detailEl.classList.add('text-success', 'fw-semibold');
    bar.classList.remove('progress-bar-animated');
    bar.classList.add('bg-success');
  }

  function paint(p) {
    const pct = Math.max(0, Math.min(100, parseInt(p.percent || 0, 10)));
    bar.style.width = pct + '%';
    bar.textContent = pct + '%';
    pctEl.textContent = pct + '%';
    stepEl.textContent = p.step || '…';
    detailEl.textContent = p.detail || '';
    if (Array.isArray(p.logs)) {
      logEl.textContent = p.logs.map(l => `[${l.t || ''}] ${l.m || ''}`).join('\n');
      logEl.scrollTop = logEl.scrollHeight;
    }
    if (p.status === 'failed') {
      bar.classList.remove('bg-success');
      bar.classList.add('bg-danger');
      bar.classList.remove('progress-bar-animated');
    }
    if (p.status === 'done') {
      progressCardSuccess();
    }
  }

  async function poll() {
    try {
      const r = await fetch(progressUrl, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin' });
      const p = await r.json();
      paint(p);
      if (p.status === 'done' || p.status === 'failed') {
        clearInterval(timer);
        timer = null;
        if (btn) btn.disabled = false;
        if (p.status === 'done') {
          showSuccess(p, { celebrate: true, reload: true });
        } else if (window.ChanSwal) {
          window.ChanSwal.error('Échec de la mise à jour', p.error || 'Une erreur est survenue.');
        } else {
          alertBox.classList.remove('d-none', 'alert-success');
          alertBox.classList.add('alert-danger');
          alertBox.textContent = p.error || 'Échec de la mise à jour';
        }
      }
    } catch (e) {}
  }

  @if(!empty($showSuccessOnce) && ($progress['status'] ?? '') === 'done')
    paint(@json($progress));
    showSuccess(@json($progress), { celebrate: false, reload: false, autoHideMs: 8000 });
    // Après affichage ponctuel : carte progression en état neutre (fichier déjà acknowledge côté serveur)
    setTimeout(() => {
      paint({
        percent: 0,
        step: 'En attente',
        detail: 'Lancez une mise à jour pour suivre l’avancement en direct.',
        logs: @json(array_slice($progress['logs'] ?? [], -20)),
        status: 'idle'
      });
      stepEl.classList.remove('text-success');
      detailEl.classList.add('text-muted');
      detailEl.classList.remove('text-success', 'fw-semibold');
    }, 8000);
  @elseif(($progress['status'] ?? '') === 'running')
    timer = setInterval(poll, 600);
    poll();
  @endif

  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    if (!document.getElementById('confirm').checked) return;
    const ok = window.ChanSwal
      ? await window.ChanSwal.confirm('Lancer la mise à jour ?', 'Le code sera remplacé par la dernière release GitHub.', 'Installer')
      : confirm('Lancer la mise à jour maintenant ?');
    if (!ok) return;

    successShown = false;
    banner.classList.add('d-none');
    banner.classList.remove('d-flex');
    btn.disabled = true;
    bar.classList.add('progress-bar-animated', 'bg-success');
    bar.classList.remove('bg-danger');
    alertBox.classList.add('d-none');
    stepEl.classList.remove('text-success');
    detailEl.classList.add('text-muted');
    detailEl.classList.remove('text-success', 'fw-semibold');
    paint({ percent: 2, step: 'Démarrage', detail: 'Connexion au serveur…', logs: [], status: 'running' });

    timer = setInterval(poll, 600);
    poll();

    const fd = new FormData(form);
    try {
      const res = await fetch(applyUrl, {
        method: 'POST',
        body: fd,
        credentials: 'same-origin',
        headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
      });
      const data = await res.json();
      await poll();
      if (!data.ok) {
        if (window.ChanSwal) {
          window.ChanSwal.error('Échec', data.error || 'Échec de la mise à jour');
        }
        btn.disabled = false;
        clearInterval(timer);
      }
    } catch (err) {
      await poll();
      btn.disabled = false;
    }
  });
})();
</script>
@endsection
