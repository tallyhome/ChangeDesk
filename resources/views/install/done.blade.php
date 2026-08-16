@extends('install.layout')
@section('step4', 'on')
@section('content')
<h1 class="h3 mb-2">Installation terminée</h1>
<p>ChanLog est prêt. Pour la sécurité, supprimez l’accès install si besoin et vérifiez que <code>storage/app/installed</code> existe.</p>
<ol>
  <li>Connectez-vous avec l’email superadmin défini à l’étape précédente (ou <code>superadmin@chanlog.app</code> / <code>password</code> si vous avez lancé le seed démo).</li>
  <li>Configurez Stripe / PayPal dans le <code>.env</code></li>
  <li>Idéalement : Document Root = dossier <code>public/</code></li>
</ol>
<a href="{{ route('login') }}" class="btn btn-success">Aller à la connexion</a>
<a href="/" class="btn btn-outline-secondary">Vitrine</a>
@endsection
