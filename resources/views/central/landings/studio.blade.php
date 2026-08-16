<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ChanLog — Changelog multi-tenant</title>
@include('partials.favicon')
@php $siteVersion = config('updates.number', config('version.number', '2.8.7')); @endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;500;600;700;800&family=Source+Serif+4:ital,opsz,wght@0,8..60,500;1,8..60,500&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#fff6ef; --bg2:#ffe8d6; --ink:#2b1d18; --muted:#7a5c52;
  --coral:#e85d4c; --coral-2:#ff8a6a; --sage:#2f6f5e; --sage-2:#7dcea0;
  --card:#fffdf9; --line:rgba(43,29,24,.1);
  --font:"Sora",system-ui,sans-serif; --serif:"Source Serif 4",Georgia,serif;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:var(--font);color:var(--ink);background:
  radial-gradient(900px 500px at 8% -10%,#ffd7c2 0%,transparent 55%),
  radial-gradient(700px 420px at 100% 8%,#cfe8d8 0%,transparent 50%),
  var(--bg);line-height:1.65;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
h1,h2,h3{margin:0;letter-spacing:-.03em;line-height:1.08}
p{margin:0}

.shell{width:min(1180px,calc(100% - 2rem));margin:0 auto}

.nav{position:sticky;top:0;z-index:30;display:flex;justify-content:space-between;align-items:center;gap:1rem;
  padding:.85rem 0;backdrop-filter:blur(14px);background:rgba(255,246,239,.78);border-bottom:1px solid transparent}
.nav.scrolled{border-color:var(--line)}
.brand{display:flex;align-items:center;gap:.55rem;font-weight:800;font-size:1.15rem}
.brand img{width:32px;height:32px}
.nav-links{display:flex;align-items:center;gap:1.15rem;font-size:.92rem;color:var(--muted)}
.nav-links a:hover{color:var(--ink)}
.burger{display:none;border:1px solid var(--line);background:var(--card);border-radius:12px;padding:.45rem .7rem;cursor:pointer}

.btn{display:inline-flex;align-items:center;justify-content:center;gap:.4rem;padding:.72rem 1.25rem;border-radius:999px;
  font-weight:700;font-size:.94rem;border:0;cursor:pointer;transition:transform .18s,box-shadow .2s,background .2s}
.btn-coral{background:var(--coral);color:#fff;box-shadow:0 10px 24px rgba(232,93,76,.28)}
.btn-coral:hover{transform:translateY(-2px);box-shadow:0 16px 32px rgba(232,93,76,.36)}
.btn-sage{background:var(--sage);color:#fff}
.btn-ghost{background:transparent;color:var(--ink);border:1px solid var(--line)}
.btn-ghost:hover{background:#fff}

.hero{padding:clamp(2.6rem,7vw,5.2rem) 0 2rem;display:grid;grid-template-columns:1.05fr .95fr;gap:clamp(1.6rem,4vw,3.2rem);align-items:center}
.chip{display:inline-flex;align-items:center;gap:.45rem;padding:.35rem .8rem;border-radius:999px;background:#fff;border:1px solid var(--line);
  color:var(--sage);font-size:.78rem;font-weight:700;letter-spacing:.04em;text-transform:uppercase}
.chip i{width:8px;height:8px;border-radius:50%;background:var(--coral);display:inline-block}
.hero h1{font-size:clamp(2.4rem,5.4vw,4.1rem);margin:1rem 0 .9rem}
.hero h1 em{font-family:var(--serif);font-style:italic;color:var(--coral);font-weight:500}
.lead{color:var(--muted);font-size:1.06rem;max-width:34rem}
.lead strong{color:var(--ink);background:#ffe1d4;padding:.05rem .35rem;border-radius:6px;font-weight:600}
.hero-cta{display:flex;flex-wrap:wrap;gap:.7rem;margin-top:1.6rem}
.pills{display:flex;flex-wrap:wrap;gap:.5rem;margin-top:1.4rem}
.pills span{padding:.35rem .7rem;border-radius:999px;background:rgba(47,111,94,.08);color:var(--sage);font-size:.8rem;font-weight:600}

.preview{position:relative}
.preview-card{border-radius:28px;background:var(--card);border:1px solid var(--line);box-shadow:0 30px 70px rgba(43,29,24,.12);overflow:hidden}
.preview-bar{display:flex;gap:.4rem;padding:.75rem 1rem;background:linear-gradient(90deg,#ffe8d6,#e8f5ee)}
.preview-bar b{width:10px;height:10px;border-radius:50%;background:#e85d4c}.preview-bar b:nth-child(2){background:#f4b942}.preview-bar b:nth-child(3){background:#2f6f5e}
.stage{position:relative;aspect-ratio:16/10;background:#2b1d18}
.stage img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:top;opacity:0;transition:opacity .6s}
.stage img.on{opacity:1}
.tabs{display:flex;gap:.4rem;flex-wrap:wrap;padding:.75rem}
.tabs button{border:0;background:#f6eee7;color:var(--muted);border-radius:999px;padding:.4rem .8rem;font-weight:700;font-size:.78rem;cursor:pointer}
.tabs button.on{background:var(--ink);color:#fff}
.float-a,.float-b{position:absolute;border-radius:18px;background:#fff;border:1px solid var(--line);box-shadow:0 16px 40px rgba(43,29,24,.12);padding:.75rem 1rem;font-size:.82rem;font-weight:700}
.float-a{left:-18px;bottom:18%;color:var(--sage)}
.float-b{right:-12px;top:14%;color:var(--coral)}

.section{padding:clamp(2.8rem,6vw,4.6rem) 0}
.k{font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:var(--coral);font-weight:700}
.section h2{font-size:clamp(1.7rem,3.4vw,2.5rem);margin:.45rem 0 .5rem}
.section .sub{color:var(--muted);max-width:40rem}

.bento{display:grid;grid-template-columns:1.2fr 1fr 1fr;grid-template-rows:auto auto;gap:1rem;margin-top:2rem}
.tile{background:var(--card);border:1px solid var(--line);border-radius:22px;padding:1.35rem;min-height:150px}
.tile.wide{grid-column:1;grid-row:1/3;background:linear-gradient(160deg,#fff 0%,#ffe8d6 100%)}
.tile .n{width:36px;height:36px;border-radius:12px;display:grid;place-items:center;background:var(--coral);color:#fff;font-weight:800;margin-bottom:.9rem}
.tile.sage .n{background:var(--sage)}
.tile h3{font-size:1.15rem;margin-bottom:.4rem}
.tile p{color:var(--muted);font-size:.95rem}

.stats{display:grid;grid-template-columns:repeat(4,1fr);gap:1rem}
.stat{background:var(--card);border-radius:20px;padding:1.2rem;border:1px solid var(--line);text-align:center}
.stat b{display:block;font-size:1.8rem;color:var(--coral)}
.stat span{color:var(--muted);font-size:.82rem}

.shots{display:grid;grid-template-columns:1.4fr 1fr;gap:1rem}
.shot{margin:0;border-radius:22px;overflow:hidden;background:#2b1d18;border:1px solid var(--line)}
.shot.stack{display:grid;gap:1rem}
.shot img{width:100%;height:100%;object-fit:contain;background:#2b1d18}
.shot figcaption{display:flex;justify-content:space-between;padding:.7rem 1rem;background:var(--card);font-size:.85rem;color:var(--muted)}
.shot figcaption b{color:var(--ink)}

.plans{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1rem;align-items:stretch}
.plan{background:var(--card);border:1px solid var(--line);border-radius:22px;padding:1.5rem;display:flex;flex-direction:column;gap:.85rem}
.plan.featured{background:var(--ink);color:#fff;border-color:var(--ink)}
.plan.featured p,.plan.featured li{color:#f3d6cf}
.plan .price{font-size:1.7rem;font-weight:800}
.plan ul{list-style:none;margin:0;padding:0;display:grid;gap:.4rem;flex:1;color:var(--muted);font-size:.93rem}
.plan li::before{content:"• ";color:var(--coral)}

.themes{display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:1rem}
.theme{background:var(--card);border:1px solid var(--line);border-radius:22px;overflow:hidden}
.theme header{display:flex;justify-content:space-between;padding:.8rem 1rem;border-bottom:1px solid var(--line)}
.theme header span{color:var(--muted);font-size:.8rem}
.theme-stage{position:relative;height:240px;background:#2b1d18}
.theme-stage iframe{position:absolute;top:0;left:0;width:1280px;height:900px;border:0;transform:scale(.32);transform-origin:top left;pointer-events:none}

.cta{text-align:center;padding:clamp(2.4rem,5vw,3.6rem);border-radius:28px;background:linear-gradient(135deg,var(--coral),#ff8a6a 55%,#f4b942);
  color:#fff;box-shadow:0 24px 50px rgba(232,93,76,.28)}
.cta h2{font-size:clamp(1.7rem,3.4vw,2.4rem);margin-bottom:.6rem}
.cta p{opacity:.92;margin-bottom:1.3rem}
.cta .btn{background:#fff;color:var(--coral)}

footer{display:flex;justify-content:space-between;flex-wrap:wrap;gap:1rem;padding:1.6rem 0;color:var(--muted);font-size:.85rem}
footer b{color:var(--ink)}

.reveal{opacity:0;transform:translateY(16px);transition:.7s ease}
.reveal.in{opacity:1;transform:none}

@media (max-width:900px){
  .hero,.bento,.shots,.stats{grid-template-columns:1fr}
  .tile.wide{grid-column:auto;grid-row:auto}
  .float-a,.float-b{display:none}
  .nav-links{display:none;position:absolute;top:100%;right:1rem;flex-direction:column;align-items:stretch;min-width:220px;
    background:#fff;border:1px solid var(--line);border-radius:16px;padding:1rem;box-shadow:0 16px 40px rgba(43,29,24,.12)}
  .nav-links.open{display:flex}
  .burger{display:block}
}
@media (prefers-reduced-motion:reduce){*{transition:none!important}.reveal{opacity:1;transform:none}}
</style>
</head>
<body>
<div class="shell">
<header class="nav" id="topNav">
  <a class="brand" href="/"><img src="{{ asset('Promo/05-Branding/chanlog-mark.svg') }}" alt=""> ChanLog</a>
  <button class="burger" id="burger" aria-label="{{ __('app.nav.menu') }}">☰</button>
  <nav class="nav-links" id="navLinks">
    <a href="#modules">{{ __('app.nav.modules') }}</a>
    <a href="#produit">{{ __('app.nav.product') }}</a>
    <a href="#plans">{{ __('app.nav.pricing') }}</a>
    <a href="#themes">{{ __('app.nav.themes') }}</a>
    <a href="{{ route('login') }}">{{ __('app.nav.login') }}</a>
    <a class="btn btn-coral" href="{{ route('register') }}">{{ __('app.nav.register') }}</a>
    @include('partials.lang-switcher', ['variant' => 'light'])
  </nav>
</header>

<section class="hero">
  <div>
    <span class="chip"><i></i> {{ __('app.landing.studio_kicker') }}</span>
    <h1>{{ __('app.landing.studio_title_1') }}<br><em>{{ __('app.landing.studio_title_2') }}</em></h1>
    <p class="lead">{!! __('app.landing.studio_lead', ['slug' => '<strong>slug.'.config('tenancy.central_domain').'</strong>']) !!}</p>
    <div class="hero-cta">
      <a class="btn btn-coral" href="{{ route('register') }}">{{ __('app.landing.try_free') }}</a>
      <a class="btn btn-ghost" href="#produit">{{ __('app.landing.see_app') }}</a>
    </div>
    <div class="pills">
      <span>{{ __('app.landing.install_guided') }}</span>
      <span>{{ __('app.landing.stripe_paypal') }}</span>
      <span>{{ __('app.landing.custom_domain') }}</span>
    </div>
  </div>
  <div class="preview">
    <div class="float-a">✓ {{ __('app.landing.mod_roadmap_only') }}</div>
    <div class="float-b">{{ __('app.nav.changelog') }} v2.8</div>
    <div class="preview-card">
      <div class="preview-bar"><b></b><b></b><b></b></div>
      <div class="stage" id="stage">
        <img class="on" src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-01-cover.png') }}" alt="{{ __('app.landing.dashboard') }}" loading="eager">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="{{ __('app.nav.features') }}" loading="lazy">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-03-bugs.png') }}" alt="{{ __('app.nav.bugs') }}" loading="lazy">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-04-wiki.png') }}" alt="{{ __('app.nav.wiki') }}" loading="lazy">
      </div>
      <div class="tabs" id="tabs">
        <button class="on" type="button">{{ __('app.landing.dashboard') }}</button>
        <button type="button">{{ __('app.landing.mod_roadmap_only') }}</button>
        <button type="button">{{ __('app.nav.bugs') }}</button>
        <button type="button">{{ __('app.nav.wiki') }}</button>
      </div>
    </div>
  </div>
</section>

<section class="section" id="modules">
  <span class="k">{{ __('app.nav.modules') }}</span>
  <h2>{{ __('app.landing.modules_title') }}</h2>
  <p class="sub">{{ __('app.landing.modules_lead') }}</p>
  <div class="bento">
    <article class="tile wide reveal">
      <div class="n">01</div>
      <h3>{{ __('app.landing.mod_changelog') }}</h3>
      <p>{{ __('app.landing.mod_changelog_text') }}</p>
    </article>
    <article class="tile sage reveal">
      <div class="n">02</div>
      <h3>{{ __('app.landing.mod_roadmap_only') }}</h3>
      <p>{{ __('app.landing.mod_roadmap_only_text') }}</p>
    </article>
    <article class="tile reveal">
      <div class="n">03</div>
      <h3>{{ __('app.landing.mod_bugs') }}</h3>
      <p>{{ __('app.landing.mod_bugs_text') }}</p>
    </article>
    <article class="tile sage reveal" style="grid-column:2/-1">
      <div class="n">04</div>
      <h3>{{ __('app.landing.mod_wiki_domain') }}</h3>
      <p>{{ __('app.landing.mod_wiki_domain_text') }}</p>
    </article>
  </div>
</section>

<section class="section" style="padding-top:0">
  <div class="stats">
    <div class="stat reveal"><b>4</b><span>{{ __('app.landing.stat_modules') }}</span></div>
    <div class="stat reveal"><b>4</b><span>{{ __('app.landing.stat_themes') }}</span></div>
    <div class="stat reveal"><b>∞</b><span>{{ __('app.landing.stat_isolated') }}</span></div>
    <div class="stat reveal"><b>2</b><span>{{ __('app.landing.stat_payments') }}</span></div>
  </div>
</section>

<section class="section" id="produit" style="padding-top:0">
  <span class="k">{{ __('app.nav.product') }}</span>
  <h2>{{ __('app.landing.shots_title') }}</h2>
  <p class="sub">{{ __('app.landing.shots_lead') }}</p>
  <div class="shots" style="margin-top:2rem">
    <figure class="shot reveal">
      <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-01-cover.png') }}" alt="{{ __('app.landing.dashboard') }}" loading="lazy">
      <figcaption><b>{{ __('app.landing.dashboard') }}</b><span>{{ __('app.landing.dashboard_cap') }}</span></figcaption>
    </figure>
    <div class="shot stack">
      <figure class="shot reveal" style="margin:0">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="{{ __('app.landing.mod_roadmap_only') }}" loading="lazy">
        <figcaption><b>{{ __('app.landing.mod_roadmap_only') }}</b><span>{{ __('app.landing.roadmap_cap') }}</span></figcaption>
      </figure>
      <figure class="shot reveal" style="margin:0">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-03-bugs.png') }}" alt="{{ __('app.nav.bugs') }}" loading="lazy">
        <figcaption><b>{{ __('app.nav.bugs') }}</b><span>{{ __('app.landing.bugs_cap') }}</span></figcaption>
      </figure>
    </div>
  </div>
</section>

<section class="section" id="plans">
  <span class="k">{{ __('app.nav.pricing') }}</span>
  <h2>{{ __('app.landing.plans_title') }}</h2>
  <p class="sub">{{ __('app.landing.plans_lead_long') }}</p>
  <div class="plans" style="margin-top:2rem">
    @forelse($plans as $plan)
      <article class="plan reveal {{ $plan->slug === 'pro' ? 'featured' : '' }}">
        <h3>{{ $plan->name }}</h3>
        <div class="price">{{ $plan->formattedPrice() }}</div>
        <ul>
          @if($plan->features['changelog'] ?? false)<li>{{ __('app.landing.feat_changelog') }}</li>@endif
          @if($plan->features['todolist'] ?? false)<li>{{ __('app.landing.feat_todo') }}</li>@endif
          @if($plan->features['bugs'] ?? false)<li>{{ __('app.landing.feat_bugs') }}</li>@endif
          @if($plan->features['wiki'] ?? false)<li>{{ __('app.landing.feat_wiki') }}</li>@endif
          @if($plan->features['stats'] ?? false)<li>{{ __('app.landing.feat_stats') }}</li>@endif
          @if($plan->features['custom_domain'] ?? false)<li>{{ __('app.landing.feat_domain') }}</li>@endif
          @if(!empty($plan->features['themes']))<li>{{ __('app.landing.feat_themes', ['list' => implode(', ', $plan->features['themes'])]) }}</li>@endif
        </ul>
        <a class="btn {{ $plan->slug === 'pro' ? 'btn-coral' : 'btn-ghost' }}" href="{{ route('register') }}">{{ __('app.landing.choose_plan', ['name' => $plan->name]) }}</a>
      </article>
    @empty
      <p>{{ __('app.landing.plans_empty') }}</p>
    @endforelse
  </div>
</section>

<section class="section" id="themes">
  <span class="k">{{ __('app.nav.themes') }}</span>
  <h2>{{ __('app.landing.themes_title') }}</h2>
  <p class="sub">{{ __('app.landing.themes_lead') }}</p>
  <div class="themes" style="margin-top:2rem">
    @foreach([
      ['classic', __('app.landing.classic'), __('app.landing.classic_desc')],
      ['midnight', __('app.landing.midnight'), __('app.landing.midnight_desc')],
      ['editorial', __('app.landing.editorial'), __('app.landing.editorial_desc')],
      ['aurora', __('app.landing.aurora'), __('app.landing.aurora_desc')],
    ] as [$slug,$label,$desc])
    <article class="theme reveal">
      <header><strong>{{ $label }}</strong><span>{{ $desc }}</span></header>
      <div class="theme-stage">
        <iframe title="{{ $label }}" src="{{ route('theme.preview', $slug) }}" loading="lazy" tabindex="-1"></iframe>
      </div>
    </article>
    @endforeach
  </div>
</section>

<section class="cta reveal">
  <h2>{{ __('app.landing.cta_title') }}</h2>
  <p>{{ __('app.landing.cta_lead') }}</p>
  <a class="btn" href="{{ route('register') }}">{{ __('app.landing.create_project') }}</a>
</section>

<footer>
  <span><b>ChanLog</b> v{{ $siteVersion }}</span>
  <span>{{ __('app.footer.multi_tenant') }}</span>
</footer>
</div>

<script>
(() => {
  const nav = document.getElementById('topNav');
  window.addEventListener('scroll', () => nav.classList.toggle('scrolled', window.scrollY > 10), { passive: true });
  document.getElementById('burger').addEventListener('click', () => document.getElementById('navLinks').classList.toggle('open'));
  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
  }, { threshold: 0.12 });
  document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
  const shots = [...document.querySelectorAll('#stage img')];
  const tabs = [...document.querySelectorAll('#tabs button')];
  let i = 0, t;
  const show = (n) => { i = n; shots.forEach((s, k) => s.classList.toggle('on', k === n)); tabs.forEach((b, k) => b.classList.toggle('on', k === n)); };
  tabs.forEach((b, n) => b.addEventListener('click', () => { show(n); clearInterval(t); t = setInterval(() => show((i + 1) % shots.length), 4600); }));
  show(0);
  t = setInterval(() => show((i + 1) % shots.length), 4600);
})();
</script>
</body>
</html>
