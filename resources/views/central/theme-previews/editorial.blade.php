<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Editorial preview</title>
<link rel="stylesheet" href="{{ asset('css/themes/editorial.css') }}">
</head>
<body>
<header class="ed-header">
  <div class="ed-wrap">
    <a class="ed-logo" href="#">{{ $demoName }}</a>
    <nav>
      <a href="#">Changelog</a>
      <a href="#">Roadmap</a>
      <a href="#">Wiki</a>
    </nav>
  </div>
</header>
<main class="ed-wrap ed-main">
  <div class="ed-kicker">Release notes</div>
  <h1 class="ed-title">Journal des versions</h1>
  @foreach($versions as $v)
  <article class="ed-item">
    <div class="ed-date">{{ $v['date'] }}</div>
    <div>
      <h2>v{{ $v['number'] }} — {{ $v['title'] }}</h2>
      <p>{{ $v['body'] }}</p>
    </div>
  </article>
  @endforeach
</main>
</body>
</html>
