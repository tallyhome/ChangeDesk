<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ChanLog — Changelog multi-tenant</title>
@include('partials.favicon')
@php $siteVersion = config('updates.number', config('version.number', '2.8.5')); @endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
<style>
:root{
  --bg:#05070f; --bg2:#0a0f1e; --panel:rgba(255,255,255,.045); --panel-2:rgba(255,255,255,.075);
  --ink:#eef2ff; --muted:#94a3c4; --line:rgba(148,163,196,.18);
  --c1:#22d3ee; --c2:#818cf8; --c3:#f472b6;
  --font-d:"Space Grotesk",system-ui,sans-serif;
  --font-b:"Inter",system-ui,sans-serif;
  --font-m:"JetBrains Mono",ui-monospace,monospace;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:var(--font-b);color:var(--ink);background:var(--bg);line-height:1.65;font-size:1.02rem;-webkit-font-smoothing:antialiased;overflow-x:hidden}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
h1,h2,h3{font-family:var(--font-d);letter-spacing:-.025em;margin:0}
p{margin:0}

/* Fond animé */
.aura{position:fixed;inset:0;z-index:0;pointer-events:none;overflow:hidden}
.aura::before,.aura::after{content:"";position:absolute;width:60vw;height:60vw;border-radius:50%;filter:blur(90px);opacity:.5}
.aura::before{top:-22vw;left:-12vw;background:radial-gradient(circle,rgba(34,211,238,.42),transparent 65%);animation:float1 20s ease-in-out infinite}
.aura::after{top:8vw;right:-18vw;background:radial-gradient(circle,rgba(129,140,248,.4),transparent 65%);animation:float2 26s ease-in-out infinite}
.grid-bg{position:fixed;inset:0;z-index:0;pointer-events:none;
  background-image:linear-gradient(rgba(148,163,196,.07) 1px,transparent 1px),linear-gradient(90deg,rgba(148,163,196,.07) 1px,transparent 1px);
  background-size:64px 64px;
  mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,#000 25%,transparent 78%);
  -webkit-mask-image:radial-gradient(ellipse 80% 60% at 50% 0%,#000 25%,transparent 78%)}
@keyframes float1{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(6vw,4vw,0)}}
@keyframes float2{0%,100%{transform:translate3d(0,0,0)}50%{transform:translate3d(-5vw,6vw,0)}}
.wrap{position:relative;z-index:1}

/* Nav */
.nav{position:sticky;top:0;z-index:30;display:flex;justify-content:space-between;align-items:center;gap:1rem;
  padding:1rem clamp(1.1rem,5vw,4rem);backdrop-filter:blur(14px);background:rgba(5,7,15,.6);
  border-bottom:1px solid transparent;transition:border-color .3s,background .3s}
.nav.scrolled{border-color:var(--line);background:rgba(5,7,15,.86)}
.brand{display:flex;align-items:center;gap:.6rem;font-family:var(--font-d);font-weight:700;font-size:1.15rem}
.brand img{width:32px;height:32px}
.nav-links{display:flex;align-items:center;gap:1.6rem;font-size:.92rem;color:var(--muted)}
.nav-links a:hover{color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.72rem 1.35rem;border-radius:999px;
  font-weight:600;font-size:.94rem;border:1px solid transparent;cursor:pointer;transition:transform .18s,box-shadow .25s,background .25s,color .25s;white-space:nowrap}
.btn-primary{background:linear-gradient(120deg,var(--c1),var(--c2));color:#04121a;box-shadow:0 12px 34px rgba(34,211,238,.28)}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 18px 44px rgba(34,211,238,.4)}
.btn-line{border-color:var(--line);color:var(--ink);background:var(--panel)}
.btn-line:hover{border-color:rgba(34,211,238,.55);background:var(--panel-2)}
.nav-burger{display:none;background:var(--panel);border:1px solid var(--line);color:var(--ink);border-radius:12px;padding:.5rem .7rem;font-size:1rem}

/* Hero */
.hero{padding:clamp(3.4rem,9vw,7rem) clamp(1.1rem,5vw,4rem) clamp(2.5rem,6vw,4.5rem);display:grid;gap:clamp(2rem,5vw,3.5rem);grid-template-columns:1.02fr .98fr;align-items:center;max-width:1320px;margin:0 auto}
.pill{display:inline-flex;align-items:center;gap:.5rem;font-family:var(--font-m);font-size:.76rem;letter-spacing:.12em;text-transform:uppercase;
  padding:.4rem .85rem;border-radius:999px;border:1px solid var(--line);background:var(--panel);color:var(--c1)}
.pill i{width:7px;height:7px;border-radius:50%;background:var(--c1);box-shadow:0 0 0 4px rgba(34,211,238,.18);display:inline-block;animation:pulse 2.4s ease-in-out infinite}
@keyframes pulse{50%{opacity:.35}}
.hero h1{font-size:clamp(2.5rem,5.6vw,4.3rem);line-height:1.03;margin:1.2rem 0 1rem}
.hero h1 span{background:linear-gradient(100deg,var(--c1),var(--c2) 55%,var(--c3));-webkit-background-clip:text;background-clip:text;color:transparent}
.lead{color:var(--muted);font-size:1.08rem;max-width:36rem}
.lead strong{color:var(--ink);font-family:var(--font-m);font-size:.95rem;background:var(--panel-2);padding:.1rem .4rem;border-radius:6px}
.hero-cta{display:flex;flex-wrap:wrap;gap:.8rem;margin-top:1.8rem}
.hero-note{display:flex;flex-wrap:wrap;gap:1.4rem;margin-top:1.9rem;font-size:.86rem;color:var(--muted)}
.hero-note span{display:flex;align-items:center;gap:.45rem}
.hero-note b{color:var(--c1)}

/* Fenêtre produit */
.window{border-radius:20px;border:1px solid var(--line);background:linear-gradient(180deg,rgba(255,255,255,.07),rgba(255,255,255,.02));
  box-shadow:0 40px 90px rgba(0,0,0,.55);overflow:hidden;backdrop-filter:blur(10px)}
.window-bar{display:flex;align-items:center;gap:.45rem;padding:.7rem .95rem;border-bottom:1px solid var(--line);background:rgba(255,255,255,.04)}
.dot{width:10px;height:10px;border-radius:50%;background:#ef4444}.dot.y{background:#f59e0b}.dot.g{background:#10b981}
.window-url{margin-left:.6rem;font-family:var(--font-m);font-size:.76rem;color:var(--muted);overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.slides{position:relative;aspect-ratio:16/10;background:#070b16}
.slides img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;object-position:top center;opacity:0;transition:opacity .8s ease}
.slides img.on{opacity:1}
.slide-tabs{display:flex;gap:.4rem;flex-wrap:wrap;padding:.75rem .95rem;border-top:1px solid var(--line);background:rgba(255,255,255,.03)}
.slide-tabs button{font-family:var(--font-m);font-size:.74rem;letter-spacing:.06em;text-transform:uppercase;padding:.35rem .7rem;border-radius:8px;
  border:1px solid transparent;background:transparent;color:var(--muted);cursor:pointer;transition:.2s}
.slide-tabs button.on{color:#04121a;background:linear-gradient(120deg,var(--c1),var(--c2))}

/* Bandeau logos / ticker */
.ticker{border-top:1px solid var(--line);border-bottom:1px solid var(--line);background:rgba(255,255,255,.02);overflow:hidden;padding:.9rem 0}
.ticker-in{display:flex;gap:3rem;width:max-content;animation:slide 28s linear infinite;font-family:var(--font-m);font-size:.8rem;letter-spacing:.14em;text-transform:uppercase;color:var(--muted)}
.ticker-in span{display:flex;align-items:center;gap:3rem}
.ticker-in span::after{content:"◆";color:var(--c2);font-size:.6rem}
@keyframes slide{to{transform:translateX(-50%)}}

/* Sections */
.section{padding:clamp(3.2rem,7vw,5.6rem) clamp(1.1rem,5vw,4rem);max-width:1320px;margin:0 auto}
.section-head{max-width:44rem;margin-bottom:2.6rem}
.section-head .tag{font-family:var(--font-m);font-size:.76rem;letter-spacing:.16em;text-transform:uppercase;color:var(--c2)}
.section-head h2{font-size:clamp(1.8rem,3.5vw,2.7rem);margin:.7rem 0 .7rem}
.section-head p{color:var(--muted)}

.cards{display:grid;gap:1.1rem;grid-template-columns:repeat(auto-fit,minmax(260px,1fr))}
.card{position:relative;padding:1.6rem;border-radius:18px;border:1px solid var(--line);background:var(--panel);
  backdrop-filter:blur(8px);transition:transform .25s,border-color .25s,background .25s}
.card:hover{transform:translateY(-4px);border-color:rgba(34,211,238,.4);background:var(--panel-2)}
.card .ico{width:42px;height:42px;border-radius:12px;display:grid;place-items:center;font-family:var(--font-m);font-weight:600;
  background:linear-gradient(140deg,rgba(34,211,238,.22),rgba(129,140,248,.22));border:1px solid var(--line);color:var(--c1);margin-bottom:1rem}
.card h3{font-size:1.12rem;margin-bottom:.45rem}
.card p{color:var(--muted);font-size:.95rem}

.stats{display:grid;gap:1px;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));border:1px solid var(--line);border-radius:18px;overflow:hidden;background:var(--line)}
.stat{padding:1.5rem;background:#080d1a;text-align:center}
.stat b{display:block;font-family:var(--font-d);font-size:2rem;background:linear-gradient(100deg,var(--c1),var(--c2));-webkit-background-clip:text;background-clip:text;color:transparent}
.stat span{color:var(--muted);font-size:.85rem}

.shots{display:grid;gap:1.1rem;grid-template-columns:repeat(2,1fr)}
.shot{border-radius:16px;border:1px solid var(--line);background:#070b16;overflow:hidden;box-shadow:0 24px 60px rgba(0,0,0,.45)}
.shot.full{grid-column:1/-1}
.shot img{width:100%;height:100%;object-fit:contain;background:#070b16}
.shot figcaption{padding:.75rem 1rem;border-top:1px solid var(--line);font-size:.85rem;color:var(--muted);display:flex;justify-content:space-between;gap:1rem}
.shot figcaption b{color:var(--ink);font-family:var(--font-d)}

/* Plans */
.plans{display:grid;gap:1.2rem;grid-template-columns:repeat(auto-fit,minmax(265px,1fr));align-items:start}
.plan{padding:1.8rem;border-radius:20px;border:1px solid var(--line);background:var(--panel);display:flex;flex-direction:column;gap:1rem;transition:transform .25s,border-color .25s}
.plan:hover{transform:translateY(-4px)}
.plan.featured{border-color:rgba(34,211,238,.5);background:linear-gradient(180deg,rgba(34,211,238,.1),rgba(129,140,248,.06));box-shadow:0 26px 70px rgba(34,211,238,.16)}
.plan .badge{align-self:flex-start;font-family:var(--font-m);font-size:.7rem;letter-spacing:.12em;text-transform:uppercase;padding:.28rem .65rem;border-radius:999px;background:linear-gradient(120deg,var(--c1),var(--c2));color:#04121a}
.plan h3{font-size:1.2rem}
.plan .price{font-family:var(--font-d);font-size:1.9rem}
.plan ul{list-style:none;margin:0;padding:0;display:grid;gap:.5rem;color:var(--muted);font-size:.93rem;flex:1}
.plan li{display:flex;gap:.55rem;align-items:flex-start}
.plan li::before{content:"✓";color:var(--c1);font-weight:700}

/* Thèmes */
.themes{display:grid;gap:1.2rem;grid-template-columns:repeat(auto-fit,minmax(300px,1fr))}
.theme-frame{border-radius:18px;border:1px solid var(--line);background:var(--panel);overflow:hidden}
.theme-label{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:.85rem 1.1rem;border-bottom:1px solid var(--line)}
.theme-label strong{font-family:var(--font-d)}
.theme-label span{color:var(--muted);font-size:.82rem}
.theme-stage{position:relative;height:290px;background:#070b16}
.theme-stage iframe{position:absolute;top:0;left:0;width:1280px;height:960px;border:0;transform:scale(.36);transform-origin:top left;pointer-events:none}

/* CTA + footer */
.cta{margin:0 clamp(1.1rem,5vw,4rem) clamp(3rem,6vw,4.5rem);padding:clamp(2.4rem,5vw,3.6rem);border-radius:24px;text-align:center;
  border:1px solid var(--line);background:linear-gradient(140deg,rgba(34,211,238,.14),rgba(129,140,248,.12),rgba(244,114,182,.1));max-width:1320px;margin-inline:auto}
.cta h2{font-size:clamp(1.7rem,3.4vw,2.5rem);margin-bottom:.8rem}
.cta p{color:var(--muted);margin-bottom:1.6rem}
footer{border-top:1px solid var(--line);padding:1.6rem clamp(1.1rem,5vw,4rem);display:flex;flex-wrap:wrap;gap:1rem;justify-content:space-between;color:var(--muted);font-size:.85rem}
footer b{color:var(--ink)}

.reveal{opacity:0;transform:translateY(22px);transition:opacity .7s ease,transform .7s ease}
.reveal.in{opacity:1;transform:none}
.d1{transition-delay:.1s}.d2{transition-delay:.2s}.d3{transition-delay:.3s}

@media (max-width:960px){
  .hero{grid-template-columns:1fr;padding-top:2.6rem}
  .nav-links{position:absolute;top:100%;right:clamp(1.1rem,5vw,4rem);flex-direction:column;align-items:stretch;gap:.6rem;padding:1rem;
    border:1px solid var(--line);border-radius:16px;background:rgba(8,13,26,.97);backdrop-filter:blur(14px);display:none;min-width:220px}
  .nav-links.open{display:flex}
  .nav-burger{display:block}
  .shots{grid-template-columns:1fr}
}
@media (prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important}.reveal{opacity:1;transform:none}}
</style>
</head>
<body>
<div class="aura"></div>
<div class="grid-bg"></div>

<div class="wrap">
<header class="nav" id="topNav">
  <a class="brand" href="/">
    <img src="{{ asset('Promo/05-Branding/chanlog-mark.svg') }}" alt="">
    ChanLog
  </a>
  <button class="nav-burger" id="burger" aria-label="Menu">☰</button>
  <nav class="nav-links" id="navLinks" aria-label="Navigation">
    <a href="#modules">Modules</a>
    <a href="#screens">Produit</a>
    <a href="#plans">Tarifs</a>
    <a href="#themes">Thèmes</a>
    <a href="{{ route('login') }}">Connexion</a>
    <a class="btn btn-primary" href="{{ route('register') }}">Créer mon projet</a>
  </nav>
</header>

<section class="hero">
  <div>
    <span class="pill"><i></i> Changelog SaaS multi-tenant</span>
    <h1>Publiez vos releases<br><span>comme une équipe produit.</span></h1>
    <p class="lead">Changelog, roadmap, bugs et wiki dans un espace public à votre marque, sur <strong>slug.{{ config('tenancy.central_domain') }}</strong> ou votre propre domaine.</p>
    <div class="hero-cta">
      <a class="btn btn-primary" href="{{ route('register') }}">Essayer gratuitement</a>
      <a class="btn btn-line" href="#screens">Voir l’application</a>
    </div>
    <div class="hero-note">
      <span><b>✓</b> Installation guidée</span>
      <span><b>✓</b> Stripe &amp; PayPal</span>
      <span><b>✓</b> Domaine personnalisé</span>
    </div>
  </div>
  <div class="window reveal in">
    <div class="window-bar">
      <span class="dot"></span><span class="dot y"></span><span class="dot g"></span>
      <span class="window-url" id="winUrl">https://acme.{{ config('tenancy.central_domain') }}/changelog</span>
    </div>
    <div class="slides" id="slides">
      <img class="on" src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-01-cover.png') }}" alt="Dashboard ChanLog" data-url="/admin" loading="eager">
      <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="Roadmap publique" data-url="/todolist" loading="lazy">
      <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-03-bugs.png') }}" alt="Suivi des bugs" data-url="/bug-report" loading="lazy">
      <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-04-wiki.png') }}" alt="Wiki" data-url="/wiki" loading="lazy">
    </div>
    <div class="slide-tabs" id="slideTabs">
      <button class="on" type="button">Dashboard</button>
      <button type="button">Roadmap</button>
      <button type="button">Bugs</button>
      <button type="button">Wiki</button>
    </div>
  </div>
</section>

<div class="ticker" aria-hidden="true">
  <div class="ticker-in">
    <span>Changelog</span><span>Roadmap</span><span>Bug tracking</span><span>Wiki</span><span>Multi-tenant</span><span>Domaine custom</span>
    <span>Changelog</span><span>Roadmap</span><span>Bug tracking</span><span>Wiki</span><span>Multi-tenant</span><span>Domaine custom</span>
  </div>
</div>

<section class="section" id="modules">
  <div class="section-head reveal">
    <span class="tag">Modules</span>
    <h2>Tout ce que vos utilisateurs doivent voir</h2>
    <p>Un espace public soigné par projet, sans multiplier les installations ni les outils.</p>
  </div>
  <div class="cards">
    <article class="card reveal"><div class="ico">01</div><h3>Changelog</h3><p>Versions datées, contenu riche, images de release et flux public prêt à partager.</p></article>
    <article class="card reveal d1"><div class="ico">02</div><h3>Roadmap</h3><p>Fonctionnalités à venir, statuts et progression — la transparence produit sans effort.</p></article>
    <article class="card reveal d2"><div class="ico">03</div><h3>Bugs</h3><p>Formulaire de signalement public, sévérité et suivi visible côté admin.</p></article>
    <article class="card reveal d3"><div class="ico">04</div><h3>Wiki &amp; domaine</h3><p>Documentation par tenant, sous-domaine automatique ou domaine personnalisé vérifié.</p></article>
  </div>
</section>

<section class="section" style="padding-top:0">
  <div class="stats reveal">
    <div class="stat"><b>4</b><span>modules publics</span></div>
    <div class="stat"><b>4</b><span>thèmes visiteurs</span></div>
    <div class="stat"><b>∞</b><span>projets multi-tenant</span></div>
    <div class="stat"><b>2</b><span>passerelles de paiement</span></div>
  </div>
</section>

<section class="section" id="screens" style="padding-top:0">
  <div class="section-head reveal">
    <span class="tag">Produit</span>
    <h2>Voyez le produit en situation</h2>
    <p>Changelog, roadmap et bugs — l’interface telle que vos utilisateurs la verront.</p>
  </div>
  <div class="shots">
    <figure class="shot full reveal" style="margin:0">
      <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-01-cover.png') }}" alt="Dashboard ChanLog" loading="lazy">
      <figcaption><b>Dashboard</b><span>Pilotage des versions</span></figcaption>
    </figure>
    <figure class="shot reveal d1" style="margin:0">
      <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="Roadmap publique" loading="lazy">
      <figcaption><b>Roadmap</b><span>Fonctionnalités à venir</span></figcaption>
    </figure>
    <figure class="shot reveal d2" style="margin:0">
      <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-03-bugs.png') }}" alt="Suivi des bugs" loading="lazy">
      <figcaption><b>Bugs</b><span>Signalements publics</span></figcaption>
    </figure>
  </div>
</section>

<section class="section" id="plans">
  <div class="section-head reveal">
    <span class="tag">Tarifs</span>
    <h2>Des plans simples</h2>
    <p>Stripe ou PayPal. Changement de plan à tout moment depuis l’espace client.</p>
  </div>
  <div class="plans">
    @forelse($plans as $i => $plan)
      <article class="plan reveal d{{ min($i,3) }} {{ $plan->slug === 'pro' ? 'featured' : '' }}">
        @if($plan->slug === 'pro')<span class="badge">Populaire</span>@endif
        <h3>{{ $plan->name }}</h3>
        <div class="price">{{ $plan->formattedPrice() }}</div>
        <ul>
          @if($plan->features['changelog'] ?? false)<li>Changelog</li>@endif
          @if($plan->features['todolist'] ?? false)<li>Roadmap / Todo</li>@endif
          @if($plan->features['bugs'] ?? false)<li>Bugs</li>@endif
          @if($plan->features['wiki'] ?? false)<li>Wiki</li>@endif
          @if($plan->features['stats'] ?? false)<li>Statistiques</li>@endif
          @if($plan->features['custom_domain'] ?? false)<li>Domaine personnalisé</li>@endif
          @if(!empty($plan->features['themes']))<li>Thèmes : {{ implode(', ', $plan->features['themes']) }}</li>@endif
        </ul>
        <a class="btn {{ $plan->slug === 'pro' ? 'btn-primary' : 'btn-line' }}" href="{{ route('register') }}">Choisir {{ $plan->name }}</a>
      </article>
    @empty
      <p style="color:var(--muted)">Les plans seront affichés après installation.</p>
    @endforelse
  </div>
</section>

<section class="section" id="themes">
  <div class="section-head reveal">
    <span class="tag">Thèmes</span>
    <h2>4 thèmes publics réels</h2>
    <p>Aperçus live — Accueil, Changelog, Roadmap, Bugs et Wiki.</p>
  </div>
  <div class="themes">
    @foreach([
      ['classic','Classic','Bootstrap clair'],
      ['midnight','Midnight','Sombre teal'],
      ['editorial','Editorial','Chronologique'],
      ['aurora','Aurora','Glass / gradient'],
    ] as $i => [$slug,$label,$desc])
    <article class="theme-frame reveal d{{ min($i,3) }}">
      <div class="theme-label"><strong>{{ $label }}</strong><span>{{ $desc }}</span></div>
      <div class="theme-stage">
        <iframe title="Aperçu {{ $label }}" src="{{ route('theme.preview', $slug) }}" loading="lazy" tabindex="-1"></iframe>
      </div>
    </article>
    @endforeach
  </div>
</section>

<section class="cta reveal">
  <h2>Prêt à publier votre changelog ?</h2>
  <p>Créez votre projet en quelques minutes, choisissez un thème et publiez votre première version.</p>
  <a class="btn btn-primary" href="{{ route('register') }}">Créer mon projet</a>
</section>

<footer>
  <span><b>ChanLog</b> v{{ $siteVersion }}</span>
  <span>Multi-tenant · Stripe &amp; PayPal</span>
</footer>
</div>

<script>
(() => {
  const nav = document.getElementById('topNav');
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 12);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  document.getElementById('burger').addEventListener('click', () => {
    document.getElementById('navLinks').classList.toggle('open');
  });

  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
  }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
  document.querySelectorAll('.reveal').forEach((el) => io.observe(el));

  const shots = [...document.querySelectorAll('#slides img')];
  const tabs = [...document.querySelectorAll('#slideTabs button')];
  const url = document.getElementById('winUrl');
  const base = 'https://acme.{{ config('tenancy.central_domain') }}';
  let idx = 0, timer;

  const show = (i) => {
    idx = i;
    shots.forEach((s, n) => s.classList.toggle('on', n === i));
    tabs.forEach((t, n) => t.classList.toggle('on', n === i));
    url.textContent = base + (shots[i].dataset.url || '/');
  };
  const start = () => { timer = setInterval(() => show((idx + 1) % shots.length), 4600); };
  const reset = () => { clearInterval(timer); start(); };

  tabs.forEach((t, i) => t.addEventListener('click', () => { show(i); reset(); }));
  show(0);
  start();
})();
</script>
</body>
</html>
