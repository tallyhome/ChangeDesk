@extends('themes.midnight.layouts.app')
@section('title', __('app.public.bug_title'))
@section('content')
@php use App\Support\ThemeUi; @endphp
<h1 class="md-title">{{ __('app.public.bug_title') }}</h1>
<p class="md-muted">{{ __('app.landing.mod_bugs_text') }}</p>
<div class="md-card">
  @if(session('success'))<p style="color:var(--md-accent)">{{ session('success') }}</p>@endif
  <form method="POST" action="{{ route('bug-report.store') }}">
    @csrf
    <p><input name="title" required placeholder="{{ __('app.public.bug_summary') }}" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff"></p>
    <p><textarea name="description" required rows="5" placeholder="{{ __('app.common.description') }}" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff"></textarea></p>
    <p><input name="name" placeholder="{{ __('app.common.name') }}" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff;margin-bottom:.5rem">
    <input name="email" type="email" placeholder="{{ __('app.common.email') }}" style="width:100%;padding:.7rem;border-radius:10px;border:1px solid #243036;background:#0f1619;color:#fff"></p>
    <p><label class="md-muted">{{ __('app.public.captcha_short') }}</label> <input name="captcha" required style="width:80px;padding:.5rem;border-radius:8px;border:1px solid #243036;background:#0f1619;color:#fff"></p>
    <button style="background:var(--md-accent);border:0;color:#042f2e;padding:.7rem 1.2rem;border-radius:10px;font-weight:700">{{ __('app.public.send_report') }}</button>
  </form>
</div>
<h2>{{ __('app.admin.bug_reports') }}</h2>
@foreach($recentBugs as $bug)
  <div class="md-card"><strong>{{ $bug->title }}</strong><div class="md-muted">{{ ThemeUi::statusLabel($bug->status) }} · {{ $bug->priority }}</div></div>
@endforeach
@endsection
