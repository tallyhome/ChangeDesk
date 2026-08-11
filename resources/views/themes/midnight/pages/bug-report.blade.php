@extends('themes.midnight.layouts.app')
@section('title', 'Bugs')
@section('content')
<h1 class="md-title">Rapport de bugs</h1>
<p class="md-muted">Signalez un problème et suivez sa résolution.</p>
<div class="md-card">
  @if(session('success'))<p style="color:var(--md-accent)">{{ session('success') }}</p>@endif
  <form method="POST" action="{{ route('bug-report.store') }}">
    @csrf
    <p><input name="title" required placeholder="Résumé" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff"></p>
    <p><textarea name="description" required rows="5" placeholder="Description" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff"></textarea></p>
    <p><input name="name" placeholder="Nom" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff;margin-bottom:.5rem">
    <input name="email" type="email" placeholder="Email" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff"></p>
    <p><label class="md-muted">Captcha : 2+3 =</label> <input name="captcha" required style="width:80px;padding:.5rem;border-radius:8px;border:1px solid #243036;background:#0f1619;color:#fff"></p>
    <button style="background:var(--md-accent);border:0;color:#042f2e;padding:.7rem 1.2rem;border-radius:10px;font-weight:700">Soumettre</button>
  </form>
</div>
<h2>Bugs récents</h2>
@foreach($recentBugs as $bug)
  <div class="md-card"><strong>{{ $bug->title }}</strong><div class="md-muted">{{ $bug->status }} · {{ $bug->severity }}</div></div>
@endforeach
@endsection
