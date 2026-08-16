@extends('themes.editorial.layouts.app')
@section('title', __('app.public.bug_title'))
@section('content')
<p class="ed-kicker">Support</p>
<h1 class="ed-title">{{ __('app.public.bug_title') }}</h1>
<p class="ed-lead">Décrivez le problème — on le suit jusqu’à résolution.</p>

<div class="ed-grid">
  <div class="ed-card">
    @if(session('success'))
      <div class="ed-alert">{{ session('success') }}</div>
    @endif
    <form class="ed-form" action="{{ route('bug-report.store') }}" method="POST">
      @csrf
      <div class="field">
        <label for="title">{{ __('app.public.bug_title_label') }}</label>
        <input type="text" id="title" name="title" value="{{ old('title') }}" required>
        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="field">
        <label for="description">{{ __('app.public.bug_description') }}</label>
        <textarea id="description" name="description" rows="5" required>{{ old('description') }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="row-2">
        <div class="field">
          <label for="name">{{ __('app.public.your_name') }}</label>
          <input type="text" id="name" name="name" value="{{ old('name') }}">
          @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="field">
          <label for="email">{{ __('app.public.your_email') }}</label>
          <input type="email" id="email" name="email" value="{{ old('email') }}">
          @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
      </div>
      <div class="field">
        <label for="captcha">{{ __('app.public.captcha') }}</label>
        <input type="text" id="captcha" name="captcha" required style="max-width:8rem">
        @error('captcha')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <button type="submit" class="ed-btn">{{ __('app.public.send_report') }}</button>
    </form>
  </div>

  <aside class="ed-side">
    <div class="ed-side-head">{{ __('app.admin.bug_reports') }}</div>
    <ul class="ed-list" style="padding:0 1rem 1rem">
      @forelse($recentBugs as $bug)
        <li>
          <a href="{{ route('bug-report.show', $bug->id) }}">{{ $bug->title }}</a>
          <div class="ed-muted" style="font-size:.88rem;margin-top:.2rem">
            {{ \App\Support\Locale::formatDate($bug->created_at) }}
            · {{ \App\Support\ThemeUi::statusLabel($bug->status) }}
          </div>
        </li>
      @empty
        <li class="ed-muted" style="border:0">{{ __('app.common.empty') }}</li>
      @endforelse
    </ul>
  </aside>
</div>
@endsection
