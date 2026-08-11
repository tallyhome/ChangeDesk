<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>ChanLog — Changelog multi-tenant</title>
@include('partials.favicon')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@600;700;800&family=DM+Sans:opsz,wght@9..40,400;9..40,500;9..40,600;9..40,700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
:root{
  --bg:#f3f1ec; --bg2:#f7f5f0; --ink:#1c1917; --muted:#57534e;
  --accent:#c2410c; --accent-2:#9a3412; --line:#d6d3d1; --card:#fffdf8;
  --font-d:"Syne",system-ui,sans-serif; --font-b:"DM Sans",system-ui,sans-serif;
}
*{box-sizing:border-box}
body{margin:0;font-family:var(--font-b);color:var(--ink);background:linear-gradient(180deg,var(--bg2),var(--bg));line-height:1.55}
a{color:inherit;text-decoration:none}
img{max-width:100%;display:block}

.nav{position:relative;z-index:10;display:flex;justify-content:space-between;align-items:center;gap:1rem;padding:1.25rem clamp(1.2rem,5vw,4rem)}
.brand{display:flex;align-items:center;gap:.65rem;font-family:var(--font-d);font-weight:800;font-size:1.25rem;letter-spacing:-.03em}
.brand img{width:34px;height:34px}
.nav-links{display:flex;align-items:center;gap:1.1rem;flex-wrap:wrap}
.nav-links a{font-weight:600;color:var(--muted);font-size:.95rem}
.nav-links a:hover{color:var(--ink)}
.btn{display:inline-flex;align-items:center;justify-content:center;gap:.4rem;border:0;border-radius:999px;padding:.8rem 1.3rem;font-weight:700;font-family:inherit;cursor:pointer;transition:transform .2s ease,background .2s ease}
.btn:hover{transform:translateY(-2px)}
.btn-primary{background:var(--accent);color:#fff}
.btn-primary:hover{background:var(--accent-2)}
.btn-line{background:transparent;border:1px solid var(--line);color:var(--ink)}

/* Hero clair — texte + carrousel screens (style ObiLab atelier) */
.hero{padding:2rem clamp(1.2rem,5vw,4rem) 3.5rem;position:relative}
.hero::before{content:"";position:absolute;inset:0;background-image:linear-gradient(rgba(28,25,23,.035) 1px,transparent 1px),linear-gradient(90deg,rgba(28,25,23,.035) 1px,transparent 1px);background-size:56px 56px;mask-image:linear-gradient(#000,transparent 85%);pointer-events:none}
.hero .row{position:relative;z-index:1;align-items:center}
.kicker{text-transform:uppercase;letter-spacing:.16em;font-size:.75rem;font-weight:700;color:var(--accent);margin:0 0 .85rem}
.hero h1{font-family:var(--font-d);font-weight:800;font-size:clamp(2.4rem,6vw,4.2rem);line-height:.98;letter-spacing:-.045em;margin:0 0 1rem}
.hero h1 span{color:var(--accent)}
.hero .lead{color:var(--muted);font-size:1.08rem;max-width:28rem;margin:0 0 1.5rem}
.hero-cta{display:flex;flex-wrap:wrap;gap:.7rem}

.carousel-wrap{background:#1c1917;border-radius:18px;overflow:hidden;box-shadow:0 28px 60px rgba(28,25,23,.18);aspect-ratio:16/10;position:relative}
.carousel-wrap img{width:100%;height:100%;object-fit:contain;background:#0f0e0d}
.carousel-caption-bar{position:absolute;left:0;right:0;bottom:0;padding:.85rem 1.1rem;background:linear-gradient(transparent,rgba(0,0,0,.75));color:#f5f5f4;display:flex;justify-content:space-between;gap:1rem;font-size:.9rem}
.carousel-indicators [data-bs-target]{width:8px;height:8px;border-radius:50%;background:#a8a29e}
.carousel-indicators .active{background:#fb923c}

.section{padding:4.25rem clamp(1.2rem,5vw,4rem)}
.section-head{max-width:34rem;margin-bottom:2rem}
.section-head h2{font-family:var(--font-d);font-weight:800;font-size:clamp(1.7rem,3vw,2.4rem);letter-spacing:-.03em;margin:0 0 .5rem;line-height:1.15}
.section-head p{margin:0;color:var(--muted)}

.values{background:#1c1917;color:#f5f5f4;border-radius:18px;padding:1.75rem;display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem}
.value .n{font-family:var(--font-d);color:#fb923c;font-weight:800;margin-bottom:.35rem}
.value h3{margin:0 0 .35rem;font-size:1.05rem}
.value p{margin:0;color:#a8a29e;font-size:.95rem}

.shots{display:grid;grid-template-columns:1.2fr 1fr;gap:1rem}
.shot{border-radius:16px;overflow:hidden;background:#0f0e0d;min-height:220px;box-shadow:0 16px 40px rgba(28,25,23,.1)}
.shot img{width:100%;height:100%;object-fit:cover;min-height:220px}
.shot.tall{grid-row:span 2;min-height:460px}
.shot.tall img{min-height:460px}

.plans{display:grid;grid-template-columns:repeat(3,1fr);gap:1rem}
.plan{background:var(--card);border:1px solid var(--line);border-radius:16px;padding:1.45rem;display:flex;flex-direction:column}
.plan.featured{border-color:var(--accent);box-shadow:0 16px 40px rgba(194,65,12,.12)}
.plan h3{font-family:var(--font-d);margin:0;font-size:1.2rem}
.plan .price{font-family:var(--font-d);font-size:1.9rem;font-weight:800;margin:.5rem 0 1rem;letter-spacing:-.03em}
.plan ul{margin:0 0 1.2rem;padding-left:1.1rem;color:var(--muted);flex:1}
.plan .btn{width:100%}

.themes{display:grid;grid-template-columns:repeat(2,1fr);gap:1rem}
.theme-frame{background:var(--card);border:1px solid var(--line);border-radius:16px;overflow:hidden}
.theme-label{display:flex;justify-content:space-between;padding:.8rem 1rem;border-bottom:1px solid var(--line);background:#faf8f4}
.theme-label strong{font-family:var(--font-d)}
.theme-label span{color:var(--muted);font-size:.82rem}
.theme-stage{height:220px;overflow:hidden;position:relative;background:#fff}
.theme-stage iframe{width:1280px;height:800px;border:0;pointer-events:none;transform:scale(.26);transform-origin:top left;position:absolute;inset:0}

.cta{margin:0 clamp(1.2rem,5vw,4rem) 2.5rem;padding:2.4rem;border-radius:18px;background:linear-gradient(135deg,#c2410c,#9a3412);color:#fff;display:flex;justify-content:space-between;align-items:center;gap:1rem;flex-wrap:wrap}
.cta h2{font-family:var(--font-d);margin:0;font-size:clamp(1.4rem,3vw,1.9rem);letter-spacing:-.03em}
.cta .btn{background:#fff;color:#9a3412}

footer{padding:1.25rem clamp(1.2rem,5vw,4rem) 2.5rem;color:var(--muted);font-size:.9rem;display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap}

.reveal{opacity:0;transform:translateY(16px);transition:opacity .7s ease,transform .7s ease}
.reveal.in{opacity:1;transform:none}

@media(max-width:960px){
  .values,.plans,.shots,.themes{grid-template-columns:1fr}
  .shot.tall{grid-row:auto;min-height:240px}
  .shot.tall img{min-height:240px}
  .nav-links a:not(.btn){display:none}
}
</style>
</head>
<body>
<header class="nav">
  <a class="brand" href="/">
    <img src="{{ asset('Promo/05-Branding/chanlog-mark.svg') }}" alt="">
    ChanLog
  </a>
  <nav class="nav-links">
    <a href="#modules">Modules</a>
    <a href="#plans">Tarifs</a>
    <a href="#themes">Thèmes</a>
    <a href="{{ route('login') }}">Connexion</a>
    <a class="btn btn-primary" href="{{ route('register') }}">Créer mon projet</a>
  </nav>
</header>

<section class="hero">
  <div class="row g-5">
    <div class="col-lg-5">
      <p class="kicker">Changelog SaaS</p>
      <h1>Vos releases,<br><span>sous votre marque.</span></h1>
      <p class="lead">Changelog, roadmap, bugs et wiki — sur <strong>slug.{{ config('tenancy.central_domain') }}</strong> ou votre domaine. Clair, multi-tenant, prêt à publier.</p>
      <div class="hero-cta">
        <a class="btn btn-primary" href="{{ route('register') }}">Essayer gratuitement</a>
        <a class="btn btn-line" href="#screens">Voir l’app</a>
      </div>
    </div>
    <div class="col-lg-7">
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
  <div class="values reveal">
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
  <div class="shots reveal">
    <div class="shot tall"><img src="{{ asset('Promo/02-Promotionnel/chanlog-promo-03-piliers.png') }}" alt="ChanLog modules" loading="lazy"></div>
    <div class="shot"><img src="{{ asset('Promo/03-Marketplace/chanlog-marketplace-02-roadmap.png') }}" alt="Roadmap" loading="lazy"></div>
    <div class="shot"><img src="{{ asset('Promo/04-Stories/chanlog-stories-03-modules.png') }}" alt="Modules" loading="lazy"></div>
  </div>
</section>

<section class="section" id="plans" style="background:rgba(255,255,255,.45)">
  <div class="section-head reveal">
    <h2>Des plans simples</h2>
    <p>Stripe ou PayPal. Upgrade depuis l’espace client.</p>
  </div>
  <div class="plans">
    @forelse($plans as $plan)
      <article class="plan reveal {{ $plan->slug === 'pro' ? 'featured' : '' }}">
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
    <p>Le rendu s’applique à Accueil, Changelog, Roadmap, Bugs et Wiki.</p>
  </div>
  <div class="themes">
    @foreach([
      ['classic','Classic','Bootstrap clair'],
      ['midnight','Midnight','Sombre teal'],
      ['editorial','Editorial','Chronologique'],
      ['aurora','Aurora','Glass / gradient'],
    ] as [$slug,$label,$desc])
    <article class="theme-frame reveal">
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
  <a class="btn" href="{{ route('register') }}">Créer mon projet</a>
</section>

<footer>
  <span>ChanLog {{ $appVersion ?? config('version.number') }}</span>
  <span>Multi-tenant · Stripe &amp; PayPal</span>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
const io=new IntersectionObserver((entries)=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('in')})},{threshold:.12});
document.querySelectorAll('.reveal').forEach(el=>io.observe(el));
</script>
</body>
</html>
