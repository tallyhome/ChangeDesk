<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Classic preview</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{background:#f8f9fa;margin:0;font-family:system-ui,sans-serif}
.navbar{background:#0d6efd!important}
.card{border:0;box-shadow:0 8px 24px rgba(0,0,0,.06)}
.badge-soft{background:#e7f1ff;color:#0d6efd}
</style>
</head>
<body>
<nav class="navbar navbar-dark px-3 py-2">
  <span class="navbar-brand mb-0 h1 fs-5">{{ $demoName }}</span>
  <div class="text-white-50 small">Changelog · Roadmap · Bugs · Wiki</div>
</nav>
<main class="container py-4" style="max-width:860px">
  <h1 class="h3 mb-1">Changelog</h1>
  <p class="text-muted mb-4">Mises à jour produit pour vos utilisateurs.</p>
  @foreach($versions as $v)
  <div class="card mb-3 p-3">
    <div class="d-flex justify-content-between align-items-start">
      <div>
        <span class="badge badge-soft mb-2">v{{ $v['number'] }}</span>
        <h2 class="h5 mb-1">{{ $v['title'] }}</h2>
        <p class="mb-0 text-muted">{{ $v['body'] }}</p>
      </div>
      <small class="text-muted">{{ $v['date'] }}</small>
    </div>
  </div>
  @endforeach
</main>
</body>
</html>
