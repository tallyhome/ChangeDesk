@extends('layouts.admin')
@section('title', 'Facturation')
@section('content')
<div class="container">
  <h1 class="mb-3">Facturation</h1>
  @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
  @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

  <div class="alert alert-info">Plan actuel : <strong>{{ $tenant->plan?->name ?? 'Free' }}</strong> ({{ $tenant->plan?->formattedPrice() }})</div>

  <div class="row g-3 mb-4">
    @foreach($plans as $plan)
      <div class="col-md-4">
        <div class="card h-100 @if($tenant->plan_id === $plan->id) border-primary @endif">
          <div class="card-body d-flex flex-column">
            <h5>{{ $plan->name }}</h5>
            <p class="fs-4">{{ $plan->formattedPrice() }}</p>
            <ul class="small text-muted flex-grow-1">
              @foreach(($plan->features ?? []) as $k => $v)
                @if(is_bool($v) && $v)<li>{{ $k }}</li>@endif
                @if($k === 'themes' && is_array($v))<li>Thèmes : {{ implode(', ', $v) }}</li>@endif
              @endforeach
            </ul>
            @if($plan->slug === 'free')
              <span class="badge text-bg-secondary">Inclus</span>
            @elseif($tenant->plan_id === $plan->id)
              <span class="badge text-bg-success">Actuel</span>
            @else
              <div class="d-grid gap-2">
                <form method="POST" action="{{ route('admin.billing.checkout') }}">@csrf
                  <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                  <input type="hidden" name="provider" value="stripe">
                  <button class="btn btn-primary w-100">Payer avec Stripe</button>
                </form>
                <form method="POST" action="{{ route('admin.billing.checkout') }}">@csrf
                  <input type="hidden" name="plan_id" value="{{ $plan->id }}">
                  <input type="hidden" name="provider" value="paypal">
                  <button class="btn btn-outline-primary w-100">Payer avec PayPal</button>
                </form>
              </div>
            @endif
          </div>
        </div>
      </div>
    @endforeach
  </div>

  <div class="card">
    <div class="card-header">Historique paiements</div>
    <div class="table-responsive">
      <table class="table mb-0">
        <thead><tr><th>Date</th><th>Provider</th><th>Montant</th><th>Statut</th></tr></thead>
        <tbody>
        @forelse($payments as $p)
          <tr>
            <td>{{ $p->created_at }}</td>
            <td>{{ $p->provider }}</td>
            <td>{{ number_format($p->amount_cents/100, 2, ',', ' ') }} €</td>
            <td>{{ $p->status }}</td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-muted">Aucun paiement</td></tr>
        @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
