@extends('themes.aurora.layouts.app')
@section('title', $bug->title)
@section('content')
@php use App\Support\ThemeUi; @endphp
<div class="au-panel">
  <div class="au-panel-pad">
    <div class="au-panel-head">
      <div>
        <h1>{{ $bug->title }}</h1>
        <p>{{ \App\Support\Locale::formatDate($bug->created_at) }}</p>
      </div>
      <a href="{{ route('bug-report') }}" class="au-btn-ghost"><i class="fas fa-arrow-left"></i> {{ __('app.common.back') }}</a>
    </div>
    <p style="margin:0 0 1rem">
      <span class="au-status {{ in_array($bug->status, ['resolved','closed','completed']) ? 'done' : ($bug->status === 'in_progress' ? 'progress' : 'open') }}">
        <span class="dot"></span>{{ ThemeUi::statusLabel($bug->status) }}
      </span>
    </p>
    <div class="au-prose" style="color:#e2e8f0">{!! nl2br(e($bug->description)) !!}</div>
  </div>
</div>
@endsection
