<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('title', 'Superadmin') — Evolora</title>
@include('partials.favicon')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
<style>
:root{
  --sa-bg:#f4f7f6; --sa-side:#0f1f1c; --sa-side-2:#16302b; --sa-accent:#12a38c;
  --sa-text:#e8f6f2; --sa-muted:#8fb3ab; --sa-card:#fff; --sa-ink:#132421;
}
*{box-sizing:border-box}
body{margin:0;font-family:Manrope,system-ui,sans-serif;background:var(--sa-bg);color:var(--sa-ink)}
.sa-shell{display:grid;grid-template-columns:260px 1fr;min-height:100vh}
.sa-side{background:linear-gradient(180deg,var(--sa-side),var(--sa-side-2));color:var(--sa-text);padding:1.25rem 1rem;position:sticky;top:0;height:100vh;display:flex;flex-direction:column;overflow:visible}
.sa-brand{font-weight:800;font-size:1.25rem;padding:.5rem .75rem 1.25rem;letter-spacing:-.02em}
.sa-brand span{color:var(--sa-accent)}
.sa-nav a{display:flex;align-items:center;gap:.75rem;color:var(--sa-muted);text-decoration:none;padding:.7rem .85rem;border-radius:12px;margin-bottom:.2rem;font-weight:600;font-size:.95rem}
.sa-nav a:hover,.sa-nav a.active{background:rgba(18,163,140,.12);color:#fff}
.sa-nav a i{width:1.1rem;text-align:center}
.sa-nav .section-label{font-size:.72rem;text-transform:uppercase;letter-spacing:.08em;color:rgba(143,179,171,.7);padding:.9rem .85rem .35rem}
.sa-side-foot{margin-top:auto;padding:.75rem;position:relative;z-index:90;overflow:visible}
.sa-main{padding:1.5rem 1.75rem 2.5rem}
.sa-top{display:flex;justify-content:space-between;align-items:center;gap:1rem;margin-bottom:1.25rem}
.sa-top h1{margin:0;font-size:1.55rem;font-weight:800;letter-spacing:-.02em}
.sa-card{background:var(--sa-card);border:1px solid rgba(19,36,33,.06);border-radius:16px;box-shadow:0 10px 30px rgba(19,36,33,.04)}
.sa-stat{padding:1.1rem 1.2rem}
.sa-stat .label{color:#6b857f;font-size:.85rem;font-weight:600}
.sa-stat .value{font-size:1.7rem;font-weight:800;margin-top:.2rem}
.btn-accent{background:var(--sa-accent);border-color:var(--sa-accent);color:#fff;font-weight:700}
.btn-accent:hover{background:#0e8a76;border-color:#0e8a76;color:#fff}
.table>:not(caption)>*>*{vertical-align:middle}
.sa-menu-btn{display:none;border:0;background:rgba(255,255,255,.08);color:#fff;border-radius:10px;padding:.45rem .7rem}
@media(max-width:900px){
  .sa-shell{grid-template-columns:1fr}
  .sa-side{position:fixed;inset:0 auto 0 0;width:min(280px,86vw);z-index:40;transform:translateX(-105%);transition:transform .25s ease}
  .sa-side.open{transform:none}
  .sa-menu-btn{display:inline-flex}
  .sa-backdrop{display:none;position:fixed;inset:0;background:rgba(0,0,0,.35);z-index:30}
  .sa-backdrop.show{display:block}
}
</style>
</head>
<body>
<div class="sa-backdrop" id="saBackdrop"></div>
<div class="sa-shell">
  <aside class="sa-side" id="saSide">
    <div class="d-flex justify-content-between align-items-center">
      <div class="sa-brand">Evo<span>lora</span> Admin</div>
      <button type="button" class="sa-menu-btn d-md-none" id="saClose" aria-label="{{ __('app.nav.close') }}">✕</button>
    </div>
    <nav class="sa-nav">
      <div class="section-label">{{ __('app.superadmin.overview') }}</div>
      <a class="{{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}" href="{{ route('superadmin.dashboard') }}"><i class="fas fa-gauge-high"></i> {{ __('app.superadmin.dashboard') }}</a>
      <div class="section-label">{{ __('app.superadmin.platform') }}</div>
      <a class="{{ request()->routeIs('superadmin.tenants.*') ? 'active' : '' }}" href="{{ route('superadmin.tenants.index') }}"><i class="fas fa-building"></i> {{ __('app.superadmin.tenants') }}</a>
      <a class="{{ request()->routeIs('superadmin.users.*') ? 'active' : '' }}" href="{{ route('superadmin.users.index') }}"><i class="fas fa-users"></i> {{ __('app.superadmin.users') }}</a>
      <a class="{{ request()->routeIs('superadmin.plans.*') ? 'active' : '' }}" href="{{ route('superadmin.plans.index') }}"><i class="fas fa-tags"></i> {{ __('app.superadmin.plans') }}</a>
      <a class="{{ request()->routeIs('superadmin.billing.*') ? 'active' : '' }}" href="{{ route('superadmin.billing.index') }}"><i class="fas fa-credit-card"></i> {{ __('app.superadmin.billing') }}</a>
      <a class="{{ request()->routeIs('superadmin.landing.*') ? 'active' : '' }}" href="{{ route('superadmin.landing.index') }}"><i class="fas fa-palette"></i> {{ __('app.superadmin.landing') }}</a>
      <div class="section-label">{{ __('app.superadmin.system') }}</div>
      <a class="{{ request()->routeIs('superadmin.audit.*') ? 'active' : '' }}" href="{{ route('superadmin.audit.index') }}"><i class="fas fa-clipboard-list"></i> {{ __('app.superadmin.audit') }}</a>
      <a class="{{ request()->routeIs('superadmin.updates.*') ? 'active' : '' }}" href="{{ route('superadmin.updates.index') }}"><i class="fas fa-cloud-arrow-down"></i> {{ __('app.superadmin.updates') }}</a>
      <a class="{{ request()->routeIs('superadmin.backups.*') ? 'active' : '' }}" href="{{ route('superadmin.backups.index') }}"><i class="fas fa-database"></i> {{ __('app.superadmin.backups') }}</a>
    </nav>
    <div class="sa-side-foot">
      @include('partials.lang-switcher', ['variant' => 'on-dark', 'placement' => 'up-start'])
      <form method="POST" action="{{ route('logout') }}" class="mt-2">@csrf
        <button class="btn btn-outline-light btn-sm w-100">{{ __('app.superadmin.logout') }}</button>
      </form>
    </div>
  </aside>
  <main class="sa-main">
    <div class="mb-3 d-lg-none">
      <button type="button" class="btn btn-dark btn-sm" id="saOpen"><i class="fas fa-bars me-1"></i> {{ __('app.superadmin.menu') }}</button>
    </div>
    @yield('content')
  </main>
</div>
@include('partials.sweetalert')
<script>
(() => {
  const side = document.getElementById('saSide');
  const backdrop = document.getElementById('saBackdrop');
  const open = () => { side.classList.add('open'); backdrop.classList.add('show'); };
  const close = () => { side.classList.remove('open'); backdrop.classList.remove('show'); };
  document.getElementById('saOpen')?.addEventListener('click', open);
  document.getElementById('saClose')?.addEventListener('click', close);
  backdrop?.addEventListener('click', close);
})();
</script>
</body>
</html>
