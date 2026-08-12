<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ChanLog — Changelog multi-tenant</title>
@include('partials.favicon')
@php $siteVersion = config('updates.number', config('version.number', '2.8.0')); @endphp
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&family=Source+Serif+4:opsz,wght@8..60,500;8..60,600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{
  --bg:#f7f4ef; --bg2:#fffdf9; --ink:#1a1f24; --muted:#5c6570;
  --accent:#c2410c; --accent-2:#9a3412; --line:rgba(26,31,36,.12); --card:#ffffff;
  --font-d:"Plus Jakarta Sans",system-ui,sans-serif;
  --font-b:"Plus Jakarta Sans",system-ui,sans-serif;
  --font-serif:"Source Serif 4",Georgia,serif;
}
*{box-sizing:border-box}
html{scroll-behavior:smooth}
body{margin:0;font-family:var(--font-b);color:var(--ink);background:linear-gradient(180deg,var(--bg2),var(--bg));line-height:1.65;font-size:1.02rem;-webkit-font-smoothing:antialiased}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}

.nav{position:sticky;top:0;z-index:20;display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1rem clamp(1.2rem,5vw,4rem);backdrop-filter:blur(12px);background:rgba(255,253,249,.88);border-bottom:1px solid transparent;transition:border-color .3s ease,box-shadow .3s ease}
.nav.scrolled{border-color:var(--line);box-shadow:0 8px 30px rgba(26,31,36,.06)}
.brand{display:flex;align-items:center;gap:.65rem;font-weight:800;font-size:1.2rem;letter-spacing:-.02em}
.brand img{width:34px;height:34px}
.nav-links{display:flex;align-items:center;gap:1.15rem;flex-wrap:wrap}
.nav-links a{font-weight:600;color:var(--muted);font-size:.95rem}
.nav-links a:hover{color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.4rem;border:0;border-radius:999px;padding:.85rem 1.35rem;font-weight:700;font-family:inherit;cursor:pointer;transition:transform .25s ease,background .2s ease,box-shadow .25s ease}
.btn:hover{transform:translateY(-2px);box-shadow:0 10px 24px rgba(194,65,12,.22)}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:var(--accent-2)}
.btn-line{background:#fff;border:1px solid var(--line);color:var(--ink);box-shadow:none}
.btn-line:hover{box-shadow:0 8px 20px rgba(26,31,36,.08)}

.hero{padding:2.5rem clamp(1.2rem,5vw,4rem) 4rem;position:relative;overflow:hidden}
.hero::before{content:"";position:absolute;inset:-20% -10% auto;height:70%;background:radial-gradient(ellipse at 70% 20%,rgba(194,65,12,.12),transparent 55%);pointer-events:none;animation:glowPulse 8s ease-in-out infinite}
.hero .row{position:relative;z-index:1;align-items:center}
.kicker{text-transform:uppercase;letter-spacing:.12em;font-size:.72rem;font-weight:700;color:var(--accent);margin:0 0 .9rem}
.hero h1{font-family:var(--font-d);font-weight:800;font-size:clamp(2.2rem,5.2vw,3.6rem);line-height:1.12;letter-spacing:-.035em;margin:0 0 1rem;max-width:14ch}
.hero h1 span{color:var(--accent)}
.hero .lead{color:var(--muted);font-size:1.08rem;max-width:32rem;margin:0 0 1.6rem;line-height:1.7}
.hero-cta{display:flex;flex-wrap:wrap;gap:.75rem}

.carousel-wrap{background:#1a1f24;border-radius:20px;overflow:hidden;box-shadow:0 28px 60px rgba(26,31,36,.16);aspect-ratio:16/10;position:relative;animation:floatY 7s ease-in-out infinite}
.carousel-wrap img{width:100%;height:100%;object-fit:contain;background:#111417}
.carousel-caption-bar{position:absolute;left:0;right:0;bottom:0;padding:.85rem 1.1rem;background:linear-gradient(transparent,rgba(0,0,0,.78));color:#f5f5f4;display:flex;justify-content:space-between;gap:1rem;font-size:.9rem}
.carousel-indicators [data-bs-target]{width:8px;height:8px;border-radius:50%;background:#a8a29e}
.carousel-indicators .active{background:#fb923c}

.section{padding:4rem clamp(1.2rem,5vw,4rem)}
.section-head{max-width:36rem;margin-bottom:1.75rem}
.section-head h2{font-family:var(--font-d);font-weight:800;font-size:clamp(1.55rem,2.8vw,2.15rem);letter-spacing:-.025em;margin:0 0 .55rem;line-height:1.2}
.section-head p{margin:0;color:var(--muted);font-size:1.02rem;line-height:1.65}

.values{background:#1a1f24;color:#f5f5f4;border-radius:18px;padding:1.6rem 1.4rem;display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.value{padding:.35rem .25rem}
.value .n{font-weight:800;color:#fb923c;margin-bottom:.3rem;font-size:.95rem}
.value h3{margin:0 0 .3rem;font-size:1.05rem;font-weight:700}
.value p{margin:0;color:#a8b0b8;font-size:.95rem;line-height:1.55}

.shots{display:grid;grid-template-columns:1.15fr 1fr;gap:.9rem}
.shot{border-radius:16px;overflow:hidden;background:#111417;min-height:210px;box-shadow:0 14px 36px rgba(26,31,36,.1);transition:transform .35s ease}
.shot:hover{transform:translateY(-4px) scale(1.01)}
.shot img{width:100%;height:100%;object-fit:cover;min-height:210px}
.shot.tall{grid-row:span 2;min-height:440px}
.shot.tall img{min-height:440px}

.plans{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.plan{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:1.45rem;display:flex;flex-direction:column;transition:transform .3s ease,box-shadow .3s ease}
.plan:hover{transform:translateY(-5px);box-shadow:0 18px 40px rgba(26,31,36,.08)}
.plan.featured{border-color:var(--accent);box-shadow:0 16px 40px rgba(194,65,12,.12)}
.plan h3{margin:0;font-size:1.15rem;font-weight:800}
.plan .price{font-size:1.85rem;font-weight:800;margin:.5rem 0 1rem;letter-spacing:-.03em}
.plan ul{margin:0 0 1.2rem;padding-left:1.1rem;color:var(--muted);flex:1;line-height:1.55}
.plan .btn{width:100%}

/* Thèmes : 4 colonnes, plus denses, aperçus plus grands */
.themes{display:grid;grid-template-columns:repeat(4,1fr);gap:.75rem}
.theme-frame{background:var(--card);border:1px solid var(--line);border-radius:14px;overflow:hidden;transition:transform .3s ease,box-shadow .3s ease}
.theme-frame:hover{transform:translateY(-4px);box-shadow:0 16px 36px rgba(26,31,36,.1)}
.theme-label{display:flex;flex-direction:column;gap:.15rem;padding:.7rem .85rem;border-bottom:1px solid var(--line);background:#faf8f4}
.theme-label strong{font-size:.98rem;font-weight:800}
.theme-label span{color:var(--muted);font-size:.78rem}
.theme-stage{height:280px;overflow:hidden;position:relative;background:#f1f3f5}
.theme-stage iframe{
  width:1100px;height:720px;border:0;pointer-events:none;
  transform:scale(.34);transform-origin:top left;
  position:absolute;top:0;left:0;
}

.cta{margin:0 clamp(1.2rem,5vw,4rem) 2.25rem;padding:2.3rem;border-radius:18px;background:linear-gradient(135deg,#c2410c,#9a3412);color:#fff;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap;position:relative;overflow:hidden}
.cta::after{content:"";position:absolute;inset:auto -20% -60% auto;width:280px;height:280px;background:radial-gradient(circle,rgba(255,255,255,.18),transparent 65%);animation:glowPulse 6s ease-in-out infinite}
.cta h2{position:relative;z-index:1;margin:0;font-size:clamp(1.35rem,2.6vw,1.8rem);font-weight:800;letter-spacing:-.025em;max-width:18ch;line-height:1.2}
.cta .btn{position:relative;z-index:1;background:#fff;color:#9a3412}

footer{padding:1.35rem clamp(1.2rem,5vw,4rem) 2.4rem;color:var(--muted);font-size:.9rem;display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap;border-top:1px solid var(--line)}
footer strong{color:var(--ink)}

/* Animations */
.reveal{opacity:0;transform:translateY(22px);transition:opacity .75s cubic-bezier(.22,1,.36,1),transform .75s cubic-bezier(.22,1,.36,1)}
.reveal.in{opacity:1;transform:none}
.reveal-delay-1{transition-delay:.08s}
.reveal-delay-2{transition-delay:.16s}
.reveal-delay-3{transition-delay:.24s}
.reveal-delay-4{transition-delay:.32s}
.hero-copy{animation:riseIn .85s cubic-bezier(.22,1,.36,1) both}
.hero-visual{animation:riseIn .95s .12s cubic-bezier(.22,1,.36,1) both}

@keyframes riseIn{from{opacity:0;transform:translateY(28px)}to{opacity:1;transform:none}}
@keyframes floatY{0%,100%{transform:translateY(0)}50%{transform:translateY(-8px)}}
@keyframes glowPulse{0%,100%{opacity:.7}50%{opacity:1}}

@media (prefers-reduced-motion:reduce){
  *,*::before,*::after{animation:none!important;transition:none!important}
  .reveal{opacity:1;transform:none}
}

@media(max-width:1100px){
  .themes{grid-template-columns:repeat(2,1fr);gap:.85rem}
  .theme-stage{height:260px}
  .theme-stage iframe{transform:scale(.36)}
}
@media(max-width:960px){
  .values,.plans,.shots{grid-template-columns:1fr}
  .shot.tall{grid-row:auto;min-height:240px}
  .shot.tall img{min-height:240px}
  .nav-links a:not(.btn){display:none}
  .carousel-wrap{animation:none}
}
@media(max-width:640px){
  .themes{grid-template-columns:1fr}
  .theme-stage{height:240px}
}
</style>
</head>
<body>
<header class="nav" id="topNav">
  <a class="brand" href="/">
    <img src="{{ asset('Promo/05-Branding/chanlog-mark.svg') }}" alt="">
    ChanLog
  </a>
  <nav class="nav-links" aria-label="Navigation">
    <a href="#modules">Modules</a>
    <a href="#plans">Tarifs</a>
    <a href="#themes">Thèmes</a>
    <a href="{{ route('login') }}">Connexion</a>
    <a class="btn btn-primary" href="{{ route('register') }}">Créer mon projet</a>
  </nav>
</header>

<section class="hero">
  <div class="row g-5">
    <div class="col-lg-5 hero-copy">
      <p class="kicker">Changelog SaaS</p>
      <h1>Vos releases,<br><span>sous votre marque.</span></h1>
      <p class="lead">Changelog, roadmap, bugs et wiki — sur <strong>slug.{{ config('tenancy.central_domain') }}</strong> ou votre domaine. Clair, multi-tenant, prêt à publier.</p>
      <div class="hero-cta">
        <a class="btn btn-primary" href="{{ route('register') }}">Essayer gratuitement</a>
        <a class="btn btn-line" href="#screens">Voir l’app</a>
      </div>
    </div>
    <div class="col-lg-7 hero-visual">
      <div id="chanCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4200">
        <div class="carousel-indicators">
          <button type="button" data-bs-target="#chanCarousel" data-bs-slide-to="0" class="active" aria-current="true"></button>
          <button type="button" data-bs-target="#chanCarousel" data-bs-slide-to="1"></button>
          <button type="button" data-bs-target="#chanCarousel" data-bs-slide-to="2"></button>
          <button type="button" data-bs-target="#chanCarousel" data-bs-slide-to="3"></button>
        </div>
        <div class="carousel-inner carousel-wrap">
          <div class="carousel-item active">
            <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-01-cover.png') }}" alt="Dashboard ChanLog" loading="eager">
            <div class="carousel-caption-bar"><strong>Dashboard</strong><span>Pilotage des versions</span></div>
          </div>
          <div class="carousel-item">
            <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="Roadmap" loading="lazy">
            <div class="carousel-caption-bar"><strong>Roadmap</strong><span>Fonctionnalités à venir</span></div>
          </div>
          <div class="carousel-item">
            <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-03-bugs.png') }}" alt="Bugs" loading="lazy">
            <div class="carousel-caption-bar"><strong>Bugs</strong><span>Suivi public</span></div>
          </div>
          <div class="carousel-item">
            <img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-04-wiki.png') }}" alt="Wiki" loading="lazy">
            <div class="carousel-caption-bar"><strong>Wiki</strong><span>Base de connaissances</span></div>
          </div>
        </div>
      </div>
      <p class="text-center mt-2 mb-0" style="color:var(--muted);font-size:.88rem">Faites défiler pour voir l’application</p>
    </div>
  </div>
</section>

<section class="section" id="modules">
  <div class="section-head reveal">
    <h2>Tout ce que vos utilisateurs doivent voir</h2>
    <p>Un espace public soigné par projet — sans multiplier les installations.</p>
  </div>
  <div class="values reveal reveal-delay-1">
    <div class="value"><div class="n">01</div><h3>Changelog</h3><p>Versions datées, contenu riche, images de release.</p></div>
    <div class="value"><div class="n">02</div><h3>Roadmap & bugs</h3><p>Transparence produit et signalements visibles.</p></div>
    <div class="value"><div class="n">03</div><h3>Wiki & domaine</h3><p>Docs par tenant, sous-domaine ou domaine custom.</p></div>
  </div>
</section>

<section class="section" id="screens" style="padding-top:0">
  <div class="section-head reveal">
    <h2>Des écrans qui donnent envie</h2>
    <p>Aperçus réels de l’expérience publique et admin.</p>
  </div>
  <div class="shots">
    <div class="shot tall reveal"><img src="{{ asset('Promo/02-Promotionnel/chanlog-promo-03-piliers.png') }}" alt="ChanLog modules" loading="lazy"></div>
    <div class="shot reveal reveal-delay-1"><img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="Roadmap" loading="lazy"></div>
    <div class="shot reveal reveal-delay-2"><img src="{{ asset('Promo/04-Stories/chanlog-stories-03-modules.png') }}" alt="Modules" loading="lazy"></div>
  </div>
</section>

<section class="section" id="plans" style="background:rgba(255,255,255,.5)">
  <div class="section-head reveal">
    <h2>Des plans simples</h2>
    <p>Stripe ou PayPal. Upgrade depuis l’espace client.</p>
  </div>
  <div class="plans">
    @forelse($plans as $i => $plan)
      <article class="plan reveal reveal-delay-{{ min($i+1,4) }} {{ $plan->slug === 'pro' ? 'featured' : '' }}">
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
      <p>Les plans seront affichés après installation.</p>
    @endforelse
  </div>
</section>

<section class="section" id="themes">
  <div class="section-head reveal">
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
    <article class="theme-frame reveal reveal-delay-{{ $i+1 }}">
      <div class="theme-label">
        <strong>{{ $label }}</strong>
        <span>{{ $desc }}</span>
      </div>
      <div class="theme-stage">
        <iframe title="Aperçu {{ $label }}" src="{{ route('theme.preview', $slug) }}" loading="lazy" tabindex="-1"></iframe>
      </div>
    </article>
    @endforeach
  </div>
</section>

<section class="cta reveal">
  <h2>Prêt à publier votre changelog ?</h2>
  <a class="btn" href="{{ route('register') }}">Créer mon projet</a>
</section>

<footer>
  <span><strong>ChanLog</strong> v{{ $siteVersion }}</span>
  <span>Multi-tenant · Stripe &amp; PayPal</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
(() => {
  const nav = document.getElementById('topNav');
  const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 12);
  onScroll();
  window.addEventListener('scroll', onScroll, { passive: true });

  const io = new IntersectionObserver((entries) => {
    entries.forEach((e) => { if (e.isIntersecting) e.target.classList.add('in'); });
  }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
  document.querySelectorAll('.reveal').forEach((el) => io.observe(el));
})();
</script>
</body>
</html>
