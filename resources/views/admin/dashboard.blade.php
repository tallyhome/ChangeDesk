@extends('layouts.admin')
@section('title', __('app.admin.administration'))
@section('content')
@if(session('impersonator_id') || session()->has('impersonator_id'))
@endif
@if(session()->has('impersonator_id'))
<div class="alert alert-warning d-flex justify-content-between align-items-center">
  <span>{{ __('app.admin.impersonating') }}</span>
  <form method="POST" action="{{ route('impersonation.leave') }}">@csrf<button class="btn btn-sm btn-dark">{{ __('app.admin.leave_impersonation') }}</button></form>
</div>
@endif

<div class="container">
  <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
    <div>
      <h1 class="mb-1">{{ __('app.admin.dashboard') }}</h1>
      <p class="text-muted mb-0">{{ $tenant->name }} · Plan <strong>{{ $tenant->plan?->name ?? 'Free' }}</strong></p>
    </div>
    <div class="d-flex flex-wrap gap-2">
      <a class="btn btn-outline-primary" target="_blank" href="{{ $tenant->subdomainUrl() }}">{{ __('app.admin.preview_sub') }}</a>
      @if($tenant->isCustomDomainVerified())
        <a class="btn btn-outline-success" target="_blank" href="{{ $tenant->publicBaseUrl() }}">{{ __('app.admin.preview_custom') }}</a>
      @endif
    </div>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">{{ __('app.admin.domain') }}</div>
        <div class="fw-semibold">
          @if($tenant->isCustomDomainVerified())
            <span class="text-success">{{ __('app.admin.domain_verified') }}</span>
          @elseif(filled($tenant->custom_domain) && $tenant->domain_status === 'pending')
            <span class="text-warning">{{ __('app.admin.domain_pending') }}</span>
          @elseif(filled($tenant->slug))
            <span class="text-success">{{ __('app.admin.domain_sub_ok') }}</span>
          @else
            <span class="text-muted">{{ __('app.admin.domain_none') }}</span>
          @endif
        </div>
        <div class="small text-muted mb-1">{{ parse_url($tenant->subdomainUrl(), PHP_URL_HOST) }}</div>
        <a href="{{ route('admin.domain.edit') }}" class="small">{{ __('app.admin.configure') }}</a>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">{{ __('app.admin.public_theme') }}</div>
        <div class="fw-semibold">{{ $tenant->visual_theme }}</div>
        <a href="{{ route('admin.appearance.edit') }}" class="small">{{ __('app.admin.appearance_link') }}</a>
      </div></div>
    </div>
    <div class="col-md-4">
      <div class="card h-100"><div class="card-body">
        <div class="text-muted">{{ __('app.admin.billing') }}</div>
        <div class="fw-semibold">{{ $tenant->plan?->formattedPrice() ?? __('app.common.free') }}</div>
        <a href="{{ route('admin.billing.index') }}" class="small">{{ __('app.admin.manage_link') }}</a>
      </div></div>
    </div>
  </div>

  <div class="card mb-4">
    <div class="card-header">{{ __('app.admin.onboarding') }}</div>
    <ul class="list-group list-group-flush">
      @foreach($checklist as $item)
        <li class="list-group-item d-flex justify-content-between">
          <span>{{ $item['label'] }}</span>
          @if($item['done'])
            <span class="badge text-bg-success">OK</span>
          @else
            <a href="{{ $item['url'] }}" class="btn btn-sm btn-outline-primary">{{ __('app.common.do') }}</a>
          @endif
        </li>
      @endforeach
    </ul>
  </div>

  <div class="card">
    <div class="card-header">{{ __('app.admin.pages') }}</div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead><tr><th>{{ __('app.common.title') }}</th><th>{{ __('app.common.date') }}</th><th></th></tr></thead>
        <tbody>
        @foreach($pages as $page)
          <tr>
            <td>{{ $page->title }}</td>
            <td>{{ \App\Support\Locale::formatDate($page->updated_at) }}</td>
            <td><a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-primary">{{ __('app.common.edit') }}</a></td>
          </tr>
        @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
