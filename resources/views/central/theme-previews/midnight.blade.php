<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Midnight preview</title>
<link rel="stylesheet" href="{{ asset('css/themes/midnight.css') }}">
</head>
<body>
<div class="md-shell">
  <aside class="md-side">
    <div class="md-brand"><span class="md-mark"></span> {{ $demoName }}</div>
    <a class="active" href="#">Changelog</a>
    <a href="#">Roadmap</a>
    <a href="#">Bugs</a>
    <a href="#">Wiki</a>
  </aside>
  <main class="md-main">
    <h1 class="md-title">Mises à jour</h1>
    <p class="md-muted">Transparence et progression produit.</p>
    @foreach($versions as $v)
    <article class="md-card">
      <div style="display:flex;justify-content:space-between;gap:1rem;flex-wrap:wrap">
        <div>
          <strong style="color:var(--md-accent)">v{{ $v['number'] }}</strong>
          <div class="md-muted">{{ $v['date'] }}</div>
          <p style="margin:.5rem 0 0"><strong>{{ $v['title'] }}</strong> — {{ $v['body'] }}</p>
        </div>
        <span class="md-badge">Publié</span>
      </div>
    </article>
    @endforeach
  </main>
</div>
</body>
</html>
