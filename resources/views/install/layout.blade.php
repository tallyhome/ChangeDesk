<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Installation ChanLog</title>
@include('partials.favicon')
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@500;700;800&display=swap" rel="stylesheet">
<style>
body{font-family:Manrope,system-ui,sans-serif;background:#f3faf8;min-height:100vh}
.wrap{max-width:720px;margin:0 auto;padding:2.5rem 1rem}
.cardx{background:#fff;border-radius:18px;padding:1.75rem;box-shadow:0 12px 40px rgba(15,31,28,.06)}
.brand{font-weight:800;font-size:1.4rem}.brand span{color:#0f9f8a}
.steps{display:flex;gap:.5rem;margin-bottom:1.25rem;flex-wrap:wrap}
.steps span{padding:.35rem .7rem;border-radius:999px;background:#e7f5f1;font-size:.85rem;font-weight:700;color:#147a6c}
.steps .on{background:#0f9f8a;color:#fff}
</style>
</head>
<body>
<div class="wrap">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <div class="brand">Chan<span>Log</span> Installer</div>
    @include('partials.lang-switcher', ['variant' => 'light'])
  </div>
  <div class="steps">
    <span class="@yield('step1','')">1. Accueil</span>
    <span class="@yield('step2','')">2. Prérequis</span>
    <span class="@yield('step3','')">3. Configuration</span>
    <span class="@yield('step4','')">4. Terminé</span>
  </div>
  <div class="cardx">
    @if($errors->any())
      <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
    @endif
    @yield('content')
  </div>
</div>
</body>
</html>
