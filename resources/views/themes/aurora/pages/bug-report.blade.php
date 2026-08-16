@extends('themes.aurora.layouts.app')
@section('title', __('app.public.bug_title'))
@section('content')
@php use App\Support\ThemeUi; @endphp
<h1 class="au-title">{{ __('app.public.bug_title') }}</h1>

<div class="au-panel">
  <div class="au-panel-pad">
    @if(session('success'))
      <div class="au-alert">{{ session('success') }}</div>
    @endif

    <form class="au-form" action="{{ route('bug-report.store') }}" method="POST">
      @csrf
      <div class="au-bug-layout">
        <div>
          <div class="field">
            <label for="title">{{ __('app.public.bug_summary') }}</label>
            <input type="text" id="title" name="title" value="{{ old('title') }}" placeholder="{{ __('app.public.bug_placeholder_title') }}" required>
            @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
          <div class="field">
            <label for="description">{{ __('app.public.bug_steps') }}</label>
            <textarea id="description" name="description" rows="6" placeholder="{{ __('app.public.bug_placeholder_desc') }}" required>{{ old('description') }}</textarea>
            @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
        <div>
          <div class="field">
            <label for="name">{{ __('app.public.your_name') }}</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}">
          </div>
          <div class="field">
            <label for="email">{{ __('app.public.your_email') }}</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}">
          </div>
          <div class="field">
            <label for="captcha">{{ __('app.public.captcha_short') }}</label>
            <input type="text" id="captcha" name="captcha" required>
            @error('captcha')<div class="invalid-feedback">{{ $message }}</div>@enderror
          </div>
        </div>
      </div>
      <div class="au-form-actions">
        <a href="{{ url('/') }}" class="au-btn-ghost">{{ __('app.common.cancel') }}</a>
        <button type="submit" class="au-btn">{{ __('app.public.send_report') }}</button>
      </div>
    </form>
  </div>
</div>

<div class="au-panel">
  <div class="au-panel-pad">
    <div class="au-panel-head">
      <div>
        <h2 style="margin:0;font-size:1.15rem;font-weight:800;color:#fff">{{ __('app.admin.bug_reports') }}</h2>
      </div>
    </div>

    <table class="au-table">
      <thead>
        <tr>
          <th>ID</th>
          <th>{{ __('app.public.bug_summary') }}</th>
          <th>{{ __('app.common.status') }}</th>
          <th>{{ __('app.common.status') }}</th>
          <th>{{ __('app.common.date') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($recentBugs as $bug)
          @php
            $prio = strtolower((string) ($bug->priority ?? 'medium'));
            $sevClass = match ($prio) {
              'high', 'critical', 'élevée', 'elevee' => 'high',
              'low', 'faible' => 'low',
              default => 'medium',
            };
            $sevLabel = match ($sevClass) {
              'high' => 'Élevée',
              'low' => 'Faible',
              default => 'Moyenne',
            };
            $st = strtolower((string) $bug->status);
            $stClass = match (true) {
              in_array($st, ['resolved', 'closed', 'completed'], true) => 'done',
              $st === 'in_progress' => 'progress',
              default => 'open',
            };
          @endphp
          <tr class="au-clickable" onclick="location.href='{{ route('bug-report.show', $bug->id) }}'">
            <td class="au-muted">#{{ $bug->id }}</td>
            <td><strong style="color:#fff">{{ $bug->title }}</strong></td>
            <td><span class="au-sev {{ $sevClass }}"><span class="dot"></span>{{ $sevLabel }}</span></td>
            <td><span class="au-status {{ $stClass }}"><span class="dot"></span>{{ ThemeUi::statusLabel($bug->status) }}</span></td>
            <td class="au-muted">{{ \App\Support\Locale::formatDate($bug->updated_at) }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="au-muted">{{ __('app.common.empty') }}</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
