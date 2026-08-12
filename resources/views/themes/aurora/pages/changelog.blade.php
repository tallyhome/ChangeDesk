@extends('themes.aurora.layouts.app')
@section('title', 'Versions')
@section('content')
<p class="au-hero-line">Changelog Multi-Tenant</p>
<p class="au-lead" style="margin-bottom:1.25rem">Affichez et parcourez vos versions publiées — clairement.</p>

<div class="au-grid">
  <div class="au-panel">
    <div class="au-panel-pad">
      <div class="au-panel-head">
        <div>
          <h1>Versions</h1>
          <p>Historique des releases publiées pour vos utilisateurs.</p>
        </div>
        <span class="au-chip">{{ $versions->count() }} publiée{{ $versions->count() > 1 ? 's' : '' }}</span>
      </div>

      <div class="table-responsive">
        <table class="au-table">
          <thead>
            <tr>
              <th>Version</th>
              <th>Date</th>
              <th>Statut</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($versions as $version)
              <tr class="au-clickable" data-toggle-detail="detail-{{ $version->id }}" id="version-{{ $version->id }}">
                <td>
                  <span class="au-ver">
                    v{{ $version->version_number }}
                    <small>{{ $version->description ?: 'Release' }}</small>
                  </span>
                </td>
                <td>{{ $version->release_date?->format('d/m/Y') }}</td>
                <td><span class="au-status done"><i class="fas fa-check-circle"></i> Publié</span></td>
                <td class="au-muted"><i class="fas fa-globe"></i></td>
              </tr>
              <tr>
                <td colspan="4" style="padding:0">
                  <div class="au-detail" id="detail-{{ $version->id }}">
                    <div class="au-prose">{!! $version->content !!}</div>
                  </div>
                </td>
              </tr>
            @empty
              <tr><td colspan="4" class="au-muted">Aucune version publiée.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <aside class="au-side" aria-label="Versions disponibles">
    <div class="au-side-head">Versions disponibles</div>
    <ul class="au-side-list">
      @forelse($versions as $version)
        <li>
          <a href="#version-{{ $version->id }}">
            <div>
              <strong>v{{ $version->version_number }}</strong>
              <small>{{ $version->release_date?->format('d/m/Y') }}</small>
            </div>
            <span class="chev" aria-hidden="true">›</span>
          </a>
        </li>
      @empty
        <li style="padding:1rem" class="au-muted">Aucune version</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection

@push('scripts')
<script>
(() => {
  const openDetail = (id) => {
    document.querySelectorAll('.au-detail.open').forEach(d => d.classList.remove('open'));
    const el = document.getElementById(id);
    if (el) el.classList.add('open');
  };
  document.querySelectorAll('[data-toggle-detail]').forEach(row => {
    row.addEventListener('click', () => {
      const id = row.dataset.toggleDetail;
      const el = document.getElementById(id);
      if (!el) return;
      if (el.classList.contains('open')) el.classList.remove('open');
      else openDetail(id);
    });
  });
  document.querySelectorAll('.au-side-list a[href^="#version-"]').forEach(a => {
    a.addEventListener('click', () => {
      const vid = a.getAttribute('href').replace('#', '');
      const row = document.getElementById(vid);
      if (row?.dataset?.toggleDetail) openDetail(row.dataset.toggleDetail);
    });
  });
  if (location.hash.startsWith('#version-')) {
    const row = document.querySelector(location.hash);
    if (row?.dataset?.toggleDetail) openDetail(row.dataset.toggleDetail);
  }
})();
</script>
@endpush
