@extends('install.layout')
@section('step4', 'on')
@section('content')
<h1 class="h3 mb-2">{{ __('app.install.done_title') }}</h1>
<p>{{ __('app.install.done_lead') }}</p>
<ol>
  <li>{{ __('app.install.done_login') }}</li>
  <li>{{ __('app.install.done_env') }}</li>
  <li>{{ __('app.install.done_root') }}</li>
</ol>
<a href="{{ route('login') }}" class="btn btn-success">{{ __('app.auth.login') }}</a>
<a href="/" class="btn btn-outline-secondary">Vitrine</a>
@endsection
