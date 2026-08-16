@extends('themes.aurora.layouts.app')
@section('title', __('app.public.changelog_title'))
@section('content')
<h1 class="au-title">{{ __('app.public.changelog_title') }}</h1>

<div class="au-grid">
  <div class="au-panel">
    <div class="au-panel-pad">
      <div class="au-panel-head">
        <div>
          <h2 style="margin:0;font-size:1.15rem;font-weight:800;color:#fff">{{ __('app.common.versions') }}</h2>
        </div>
        <span class="au-chip">{{ trans_choice('app.public.published_count', $versions->count(), ['count' => $versions->count()]) }}</span>
      </div>

      <div class="table-responsive">
        <table class="au-table">
          <thead>
            <tr>
              <th>{{ __('app.common.version') }}</th>
              <th>{{ __('app.common.date') }}</th>
              <th>{{ __('app.common.status') }}</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @forelse($versions as $version)
              <tr class="au-clickable" data-toggle-detail="detail-{{ $version->id }}" id="version-{{ $version->id }}">
                <td>
                  <span class="au-ver">
                    v{{ $version->version_number }}
                    <small>{{ $version->description ?: __('app.common.release') }}</small>
                  </span>
                </td>
                <td>{{ \App\Support\Locale::formatDate($version->release_date) }}</td>
                <td><span class="au-status done"><i class="fas fa-check-circle"></i> {{ __('app.common.published') }}</span></td>
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
              <tr><td colspan="4" class="au-muted">{{ __('app.common.empty') }}</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <aside class="au-side" aria-label="{{ __('app.common.available_versions') }}">
    <div class="au-side-head">{{ __('app.common.available_versions') }}</div>
    <ul class="au-side-list">
      @forelse($versions as $version)
        <li>
          <a href="#version-{{ $version->id }}">
            <div>
              <strong>v{{ $version->version_number }}</strong>
              <small>{{ \App\Support\Locale::formatDate($version->release_date) }}</small>
            </div>
            <span class="chev" aria-hidden="true">›</span>
          </a>
        </li>
      @empty
        <li style="padding:1rem" class="au-muted">{{ __('app.common.empty') }}</li>
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
