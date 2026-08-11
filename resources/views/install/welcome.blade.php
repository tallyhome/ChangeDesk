@extends('install.layout')
@section('step1', 'on')
@section('content')
<h1 class="h3 mb-2">Bienvenue</h1>
<p class="text-muted">Assistant d’installation pour cPanel, Webuzo, Plesk ou VPS. Il créera le fichier <code>.env</code>, lancera les migrations et le compte superadmin.</p>
<ul>
  <li>PHP 8.2+</li>
  <li>Base MySQL / MariaDB prête</li>
  <li>Document Root pointant vers <code>/public</code> (recommandé)</li>
</ul>
<a href="{{ route('install.requirements') }}" class="btn btn-success">Commencer</a>
@endsection
