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
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,500;0,9..144,700;1,9..144,500&family=Inter:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500&display=swap" rel="stylesheet">
<style>
:root{
  --paper:#f5f3ee; --paper-2:#fffefb; --ink:#14161a; --ink-soft:#4b5158; --line:#14161a;
  --line-soft:rgba(20,22,26,.14); --accent:#1d4ed8; --accent-soft:#e6ecff;
  --font-d:"Fraunces",Georgia,serif;
  --font-b:"Inter",system-ui,sans-serif;
  --font-m:"IBM Plex Mono",ui-monospace,monospace;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;background:var(--paper);color:var(--ink);font-family:var(--font-b);font-size:1.02rem;line-height:1.62;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}
h1,h2,h3{font-family:var(--font-d);font-weight:700;letter-spacing:-.02em;margin:0;line-height:1.08}
p{margin:0}
.mono{font-family:var(--font-m);font-size:.76rem;letter-spacing:.14em;text-transform:uppercase}

.shell{max-width:1240px;margin:0 auto;padding:0 clamp(1.1rem,4vw,2.6rem)}

/* Nav */
.nav{position:sticky;top:0;z-index:30;background:rgba(245,243,238,.92);backdrop-filter:blur(10px);border-bottom:1px solid var(--line)}
.nav-in{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.9rem 0}
.brand{display:flex;align-items:center;gap:.6rem;font-family:var(--font-d);font-weight:700;font-size:1.2rem}
.brand img{width:30px;height:30px}
.nav-links{display:flex;align-items:center;gap:1.5rem;font-size:.9rem}
.nav-links a:not(.btn){padding-bottom:2px;border-bottom:1px solid transparent}
.nav-links a:not(.btn):hover{border-color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.5rem;padding:.66rem 1.25rem;font-weight:600;font-size:.92rem;
  border:1px solid var(--line);background:transparent;color:var(--ink);cursor:pointer;transition:.2s;white-space:nowrap}
.btn:hover{background:var(--ink);color:var(--paper-2)}
.btn-solid{background:var(--ink);color:var(--paper-2)}
.btn-solid:hover{background:var(--accent);border-color:var(--accent)}
.btn-lg{padding:.85rem 1.6rem;font-size:1rem}
.burger{display:none;border:1px solid var(--line);background:transparent;padding:.45rem .7rem;font-size:1rem;cursor:pointer}

/* Hero */
.hero{border-bottom:1px solid var(--line);padding:clamp(2.6rem,6vw,4.6rem) 0 0}
.hero-top{display:grid;grid-template-columns:1.15fr .85fr;gap:clamp(1.6rem,4vw,3.4rem);align-items:end;padding-bottom:2.4rem}
.hero h1{font-size:clamp(2.7rem,7vw,5.4rem)}
.hero h1 em{font-style:italic;color:var(--accent)}
.hero-meta{display:flex;flex-direction:column;gap:1.1rem;border-left:1px solid var(--line-soft);padding-left:1.5rem}
.hero-meta p{color:var(--ink-soft)}
.hero-meta code{font-family:var(--font-m);font-size:.85rem;background:var(--accent-soft);padding:.1rem .4rem;border:1px solid var(--line-soft)}
.hero-cta{display:flex;flex-wrap:wrap;gap:.7rem}
.hero-rule{display:grid;grid-template-columns:repeat(4,1fr);border-top:1px solid var(--line)}
.hero-rule div{padding:1rem .2rem;border-right:1px solid var(--line-soft);text-align:left}
.hero-rule div:last-child{border-right:0}
.hero-rule b{display:block;font-family:var(--font-d);font-size:1.5rem}
.hero-rule span{color:var(--ink-soft);font-size:.82rem}

/* Frame produit */
.frame{border:1px solid var(--line);background:var(--paper-2);margin:0 0 -1px}
.frame-bar{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:.6rem 1rem;border-bottom:1px solid var(--line);color:var(--ink-soft)}
.frame-bar nav{display:flex;gap:.3rem;flex-wrap:wrap}
.frame-bar button{font-family:var(--font-m);font-size:.72rem;letter-spacing:.1em;text-transform:uppercase;padding:.3rem .6rem;
  border:1px solid transparent;background:transparent;color:var(--ink-soft);cursor:pointer}
.frame-bar button.on{border-color:var(--line);background:var(--ink);color:var(--paper-2)}
.stage{position:relative;aspect-ratio:16/9;background:#eceae4}
.stage img{position:absolute;inset:0;width:100%;height:100%;object-fit:contain;opacity:0;transition:opacity .6s ease}
.stage img.on{opacity:1}

/* Sections */
.section{padding:clamp(3rem,6vw,5rem) 0;border-bottom:1px solid var(--line)}
.head{display:grid;grid-template-columns:auto 1fr;gap:1.4rem;align-items:baseline;margin-bottom:2.4rem}
.head .num{font-family:var(--font-m);font-size:.8rem;color:var(--accent)}
.head h2{font-size:clamp(1.7rem,3.6vw,2.6rem);margin-bottom:.5rem}
.head p{color:var(--ink-soft);max-width:42rem}

.rows{border-top:1px solid var(--line-soft)}
.row-item{display:grid;grid-template-columns:5rem 1fr 1.15fr;gap:1.5rem;padding:1.5rem 0;border-bottom:1px solid var(--line-soft);align-items:baseline;transition:padding .25s}
.row-item:hover{padding-left:.6rem}
.row-item .n{font-family:var(--font-m);font-size:.8rem;color:var(--accent)}
.row-item h3{font-size:1.3rem}
.row-item p{color:var(--ink-soft)}

.gallery{display:grid;gap:1.2rem;grid-template-columns:repeat(2,1fr)}
.gallery figure{margin:0;border:1px solid var(--line);background:var(--paper-2)}
.gallery figure.full{grid-column:1/-1}
.gallery img{width:100%;object-fit:contain;background:#eceae4}
.gallery figcaption{display:flex;justify-content:space-between;gap:1rem;padding:.7rem 1rem;border-top:1px solid var(--line-soft);font-size:.85rem;color:var(--ink-soft)}
.gallery figcaption b{font-family:var(--font-d);color:var(--ink)}

/* Plans */
.plans{display:grid;gap:0;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));border:1px solid var(--line)}
.plan{padding:1.8rem;border-right:1px solid var(--line-soft);display:flex;flex-direction:column;gap:1rem;background:var(--paper-2)}
.plan:last-child{border-right:0}
.plan.featured{background:var(--accent-soft)}
.plan .tagline{font-family:var(--font-m);font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--accent)}
.plan h3{font-size:1.35rem}
.plan .price{font-family:var(--font-d);font-size:2rem}
.plan ul{list-style:none;margin:0;padding:0;display:grid;gap:.45rem;font-size:.93rem;color:var(--ink-soft);flex:1}
.plan li{display:flex;gap:.5rem}
.plan li::before{content:"—";color:var(--accent)}

/* Thèmes */
.themes{display:grid;gap:1.2rem;grid-template-columns:repeat(auto-fit,minmax(290px,1fr))}
.theme-card{border:1px solid var(--line);background:var(--paper-2)}
.theme-card header{display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:.8rem 1rem;border-bottom:1px solid var(--line-soft)}
.theme-card header strong{font-family:var(--font-d);font-size:1.02rem}
.theme-card header span{font-size:.8rem;color:var(--ink-soft)}
.theme-stage{position:relative;height:280px;overflow:hidden;background:#eceae4}
.theme-stage iframe{position:absolute;top:0;left:0;width:1280px;height:960px;border:0;transform:scale(.35);transform-origin:top left;pointer-events:none}

/* Citation */
.quote{padding:clamp(2.6rem,5vw,4rem) 0;border-bottom:1px solid var(--line);text-align:center}
.quote blockquote{margin:0 auto;max-width:44rem;font-family:var(--font-d);font-style:italic;font-size:clamp(1.3rem,2.8vw,2rem);line-height:1.3}
.quote cite{display:block;margin-top:1.2rem;font-family:var(--font-m);font-size:.76rem;letter-spacing:.14em;text-transform:uppercase;color:var(--ink-soft);font-style:normal}

/* CTA + footer */
.cta{padding:clamp(3rem,6vw,5rem) 0;text-align:center;border-bottom:1px solid var(--line)}
.cta h2{font-size:clamp(1.9rem,4vw,3rem);margin-bottom:.9rem}
.cta p{color:var(--ink-soft);margin-bottom:1.7rem}
footer{padding:1.5rem 0;display:flex;flex-wrap:wrap;gap:1rem;justify-content:space-between;font-size:.85rem;color:var(--ink-soft)}
footer b{font-family:var(--font-d);color:var(--ink)}

.reveal{opacity:0;transform:translateY(18px);transition:opacity .7s ease,transform .7s ease}
.reveal.in{opacity:1;transform:none}
.d1{transition-delay:.09s}.d2{transition-delay:.18s}.d3{transition-delay:.27s}

@media (max-width:900px){
  .hero-top{grid-template-columns:1fr;align-items:start}
  .hero-meta{border-left:0;padding-left:0;border-top:1px solid var(--line-soft);padding-top:1.2rem}
  .hero-rule{grid-template-columns:repeat(2,1fr)}
  .hero-rule div{border-bottom:1px solid var(--line-soft)}
  .row-item{grid-template-columns:3rem 1fr}
  .row-item p{grid-column:2}
  .gallery{grid-template-columns:1fr}
  .head{grid-template-columns:1fr;gap:.5rem}
  .plan{border-right:0;border-bottom:1px solid var(--line-soft)}
  .nav-links{display:none;position:absolute;top:100%;left:0;right:0;flex-direction:column;align-items:stretch;gap:.8rem;
    padding:1.2rem clamp(1.1rem,4vw,2.6rem);background:var(--paper-2);border-bottom:1px solid var(--line)}
  .nav-links.open{display:flex}
  .burger{display:block}
}
@media (prefers-reduced-motion:reduce){*{transition:none!important}.reveal{opacity:1;transform:none}}
</style>
</head>
<body>

<header class="nav">
  <div class="shell nav-in">
    <a class="brand" href="/"><img src="{{ asset('Promo/05-Branding/chanlog-mark.svg') }}" alt=""> ChanLog</a>
    <button class="burger" id="burger" aria-label="Menu">☰</button>
    <nav class="nav-links" id="navLinks" aria-label="Navigation">
      <a href="#modules">Modules</a>
      <a href="#produit">Produit</a>
      <a href="#plans">Tarifs</a>
      <a href="#themes">Thèmes</a>
      <a href="{{ route('login') }}">Connexion</a>
      <a class="btn btn-solid" href="{{ route('register') }}">Créer mon projet</a>
    </nav>
  </div>
</header>

<section class="hero">
  <div class="shell">
    <div class="hero-top">
      <div>
        <span class="mono" style="color:var(--accent)">Changelog SaaS · Multi-tenant</span>
        <h1 style="margin-top:1rem">Vos releases,<br><em>racontées</em> proprement.</h1>
      </div>
      <div class="hero-meta">
        <p>Changelog, roadmap, bugs et wiki pour chaque projet — publiés sur <code>slug.{{ config('tenancy.central_domain') }}</code> ou sur votre domaine.</p>
        <div class="hero-cta">
          <a class="btn btn-solid btn-lg" href="{{ route('register') }}">Essayer gratuitement</a>
          <a class="btn btn-lg" href="#produit">Voir l’app</a>
        </div>
      </div>
    </div>
    <div class="hero-rule">
      <div><b>4</b><span>modules publics</span></div>
      <div><b>4</b><span>thèmes visiteurs</span></div>
      <div><b>∞</b><span>projets isolés</span></div>
      <div><b>2</b><span>moyens de paiement</span></div>
    </div>
  </div>
</section>

<section class="section" id="produit" style="padding-top:0;padding-bottom:0">
  <div class="shell" style="padding-left:0;padding-right:0">
    <div class="frame">
      <div class="frame-bar">
        <span class="mono" id="frameUrl">acme.{{ config('tenancy.central_domain') }}/changelog</span>
        <nav id="frameTabs">
          <button class="on" type="button">Dashboard</button>
          <button type="button">Roadmap</button>
          <button type="button">Bugs</button>
          <button type="button">Wiki</button>
        </nav>
      </div>
      <div class="stage" id="stage">
        <img class="on" src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-01-cover.png') }}" alt="Dashboard ChanLog" data-url="/admin" loading="eager">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="Roadmap publique" data-url="/todolist" loading="lazy">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-03-bugs.png') }}" alt="Suivi des bugs" data-url="/bug-report" loading="lazy">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-04-wiki.png') }}" alt="Wiki" data-url="/wiki" loading="lazy">
      </div>
    </div>
  </div>
</section>

<section class="section" id="modules">
  <div class="shell">
    <div class="head reveal">
      <span class="num">01 / Modules</span>
      <div>
        <h2>Tout ce que vos utilisateurs doivent voir</h2>
        <p>Un espace public soigné par projet — sans multiplier les installations.</p>
      </div>
    </div>
    <div class="rows">
      <div class="row-item reveal"><span class="n">01</span><h3>Changelog</h3><p>Versions datées, contenu riche, images de release et navigation par version.</p></div>
      <div class="row-item reveal d1"><span class="n">02</span><h3>Roadmap</h3><p>Fonctionnalités à venir avec statut, progression et couleur personnalisée.</p></div>
      <div class="row-item reveal d2"><span class="n">03</span><h3>Bugs</h3><p>Signalement public, sévérité, suivi et réponses côté administration.</p></div>
      <div class="row-item reveal d3"><span class="n">04</span><h3>Wiki &amp; domaine</h3><p>Documentation par tenant, sous-domaine automatique ou domaine personnalisé.</p></div>
    </div>
  </div>
</section>

<section class="section">
  <div class="shell">
    <div class="head reveal">
      <span class="num">02 / Aperçus</span>
      <div>
        <h2>Voyez le produit en situation</h2>
        <p>Changelog, roadmap et bugs — l’interface telle que vos utilisateurs la verront.</p>
      </div>
    </div>
    <div class="gallery">
      <figure class="full reveal">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-01-cover.png') }}" alt="Dashboard ChanLog" loading="lazy">
        <figcaption><b>Dashboard</b><span>Pilotage des versions</span></figcaption>
      </figure>
      <figure class="reveal d1">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="Roadmap publique" loading="lazy">
        <figcaption><b>Roadmap</b><span>Fonctionnalités à venir</span></figcaption>
      </figure>
      <figure class="reveal d2">
        <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-03-bugs.png') }}" alt="Suivi des bugs" loading="lazy">
        <figcaption><b>Bugs</b><span>Signalements publics</span></figcaption>
      </figure>
    </div>
  </div>
</section>

<section class="quote">
  <div class="shell">
    <blockquote class="reveal">« Un changelog lisible vaut mieux qu’une newsletter oubliée : vos utilisateurs voient ce qui avance, en direct. »</blockquote>
    <cite class="reveal d1">Philosophie ChanLog</cite>
  </div>
</section>

<section class="section" id="plans">
  <div class="shell">
    <div class="head reveal">
      <span class="num">03 / Tarifs</span>
      <div>
        <h2>Des plans simples</h2>
        <p>Stripe ou PayPal. Upgrade depuis l’espace client, sans migration.</p>
      </div>
    </div>
    <div class="plans reveal">
      @forelse($plans as $plan)
        <article class="plan {{ $plan->slug === 'pro' ? 'featured' : '' }}">
          <span class="tagline">{{ $plan->slug === 'pro' ? 'Recommandé' : $plan->slug }}</span>
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
          <a class="btn {{ $plan->slug === 'pro' ? 'btn-solid' : '' }}" href="{{ route('register') }}">Choisir {{ $plan->name }}</a>
        </article>
      @empty
        <p style="padding:1.8rem;color:var(--ink-soft)">Les plans seront affichés après installation.</p>
      @endforelse
    </div>
  </div>
</section>

<section class="section" id="themes">
  <div class="shell">
    <div class="head reveal">
      <span class="num">04 / Thèmes</span>
      <div>
        <h2>4 thèmes publics réels</h2>
        <p>Aperçus live — Accueil, Changelog, Roadmap, Bugs et Wiki.</p>
      </div>
    </div>
    <div class="themes">
      @foreach([
        ['classic','Classic','Bootstrap clair'],
        ['midnight','Midnight','Sombre teal'],
        ['editorial','Editorial','Chronologique'],
        ['aurora','Aurora','Glass / gradient'],
      ] as $i => [$slug,$label,$desc])
      <article class="theme-card reveal d{{ min($i,3) }}">
        <header><strong>{{ $label }}</strong><span>{{ $desc }}</span></header>
        <div class="theme-stage">
          <iframe title="Aperçu {{ $label }}" src="{{ route('theme.preview', $slug) }}" loading="lazy" tabindex="-1"></iframe>
        </div>
      </article>
      @endforeach
    </div>
  </div>
</section>

<section class="cta">
  <div class="shell">
    <h2 class="reveal">Prêt à publier votre changelog ?</h2>
    <p class="reveal d1">Créez votre projet, choisissez un thème, publiez votre première version.</p>
    <a class="btn btn-solid btn-lg reveal d2" href="{{ route('register') }}">Créer mon projet</a>
  </div>
</section>

<div class="shell">
  <footer>
    <span><b>ChanLog</b> v{{ $siteVersion }}</span>
    <span>Multi-tenant · Stripe &amp; PayPal</span>
  </footer>
</div>

<script>
(() => {
  document.getElementById('burger').addEventListener('click', () => {
    document.getElementById('navLinks').classList.toggle('open');
  });

  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); } });
  }, { threshold: 0.12, rootMargin: '0px 0px -6% 0px' });
  document.querySelectorAll('.reveal').forEach((el) => io.observe(el));

  const shots = [...document.querySelectorAll('#stage img')];
  const tabs = [...document.querySelectorAll('#frameTabs button')];
  const url = document.getElementById('frameUrl');
  const host = 'acme.{{ config('tenancy.central_domain') }}';
  let idx = 0, timer;

  const show = (i) => {
    idx = i;
    shots.forEach((s, n) => s.classList.toggle('on', n === i));
    tabs.forEach((t, n) => t.classList.toggle('on', n === i));
    url.textContent = host + (shots[i].dataset.url || '/');
  };
  const start = () => { timer = setInterval(() => show((idx + 1) % shots.length), 4800); };

  tabs.forEach((t, i) => t.addEventListener('click', () => { show(i); clearInterval(timer); start(); }));
  show(0);
  start();
})();
</script>
</body>
</html>
