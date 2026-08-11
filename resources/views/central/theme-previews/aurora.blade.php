<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Aurora preview</title>
<link rel="stylesheet" href="{{ asset('css/themes/aurora.css') }}">
</head>
<body>
<div class="au-bg"></div>
<header class="au-top">
  <strong>{{ $demoName }}</strong>
  <nav>
    <a href="#">Changelog</a>
    <a href="#">Roadmap</a>
    <a href="#">Wiki</a>
  </nav>
</header>
<main class="au-main">
  <h1 class="au-title">Changelog</h1>
  <p class="au-muted">Glass &amp; gradients — rendu Aurora.</p>
  @foreach($versions as $v)
  <article class="au-card">
    <span class="au-chip">v{{ $v['number'] }}</span>
    <h2 style="margin:.6rem 0 .35rem;font-size:1.25rem">{{ $v['title'] }}</h2>
    <p class="au-muted" style="margin:0">{{ $v['body'] }} · {{ $v['date'] }}</p>
  </article>
  @endforeach
</main>
</body>
</html>
