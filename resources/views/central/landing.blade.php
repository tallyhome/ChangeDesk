<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>ChangeDesk — Changelog pour vos projets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            --ink: #0f172a;
            --accent: #0d9488;
            --muted: #64748b;
            --bg: #f1f5f9;
        }
        body {
            margin: 0;
            font-family: "Segoe UI", system-ui, sans-serif;
            color: var(--ink);
            background:
                radial-gradient(1200px 600px at 10% -10%, rgba(13, 148, 136, 0.18), transparent 60%),
                linear-gradient(180deg, #ffffff 0%, var(--bg) 100%);
            min-height: 100vh;
        }
        .hero {
            max-width: 920px;
            margin: 0 auto;
            padding: 4rem 1.5rem 3rem;
        }
        .brand {
            font-size: clamp(2.5rem, 6vw, 4rem);
            font-weight: 800;
            letter-spacing: -0.03em;
            margin-bottom: 0.75rem;
        }
        .brand span { color: var(--accent); }
        .lead {
            font-size: 1.2rem;
            color: var(--muted);
            max-width: 36rem;
            margin-bottom: 2rem;
        }
        .cta .btn { padding: 0.75rem 1.4rem; font-weight: 600; }
        .btn-accent {
            background: var(--accent);
            border-color: var(--accent);
            color: #fff;
        }
        .btn-accent:hover { background: #0f766e; border-color: #0f766e; color: #fff; }
        .points {
            display: grid;
            gap: 1rem;
            margin-top: 3rem;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        }
        .point {
            padding: 1.25rem;
            border-left: 3px solid var(--accent);
            background: rgba(255,255,255,0.7);
        }
        .point h3 { font-size: 1rem; margin: 0 0 0.4rem; }
        .point p { margin: 0; color: var(--muted); font-size: 0.95rem; }
        footer {
            text-align: center;
            color: var(--muted);
            padding: 2rem 1rem 3rem;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <main class="hero">
        <div class="brand">Change<span>Desk</span></div>
        <p class="lead">
            Publiez le changelog, la todolist, les bugs et le wiki de votre produit
            sur <strong>slug.{{ config('tenancy.central_domain') }}</strong>
            ou votre propre domaine <strong>changelog.monsite.fr</strong>.
        </p>
        <div class="cta d-flex flex-wrap gap-2">
            <a href="{{ route('register') }}" class="btn btn-accent">Créer mon projet</a>
            <a href="{{ route('login') }}" class="btn btn-outline-dark">Se connecter</a>
        </div>
        <div class="points">
            <div class="point">
                <h3>Sous-domaine gratuit</h3>
                <p>Votre espace public est immédiatement disponible après inscription.</p>
            </div>
            <div class="point">
                <h3>Domaine personnalisé</h3>
                <p>Pointez un CNAME vers ChangeDesk et vérifiez le DNS en un clic.</p>
            </div>
            <div class="point">
                <h3>Isolation totale</h3>
                <p>Chaque client ne voit que les données de son propre projet.</p>
            </div>
        </div>
    </main>
    <footer>ChangeDesk {{ $appVersion ?? config('version.number') }}</footer>
</body>
</html>
