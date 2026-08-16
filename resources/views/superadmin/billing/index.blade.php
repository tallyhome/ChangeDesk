@extends('superadmin.layout')
@section('title', __('app.superadmin.billing'))
@section('content')
<h1 class="mb-4">Billing</h1>
<form method="POST" action="{{ route('superadmin.billing.assign') }}" class="card card-body mb-4">
@csrf
<div class="row g-2 align-items-end">
<div class="col-md-5"><label class="form-label">Tenant ID</label><input name="tenant_id" class="form-control" required></div>
<div class="col-md-5"><label class="form-label">Plan</label>
<select name="plan_id" class="form-select">@foreach($plans as $p)<option value="{{ $p->id }}">{{ $p->name }}</option>@endforeach</select></div>
<div class="col-md-2"><button class="btn btn-primary w-100">Assigner</button></div>
</div>
</form>
<div class="row g-4">
<div class="col-lg-6"><div class="card"><div class="card-header">Abonnements</div><div class="table-responsive">
<table class="table mb-0"><thead><tr><th>Tenant</th><th>Plan</th><th>Status</th><th>Provider</th></tr></thead><tbody>
@foreach($subscriptions as $s)
<tr><td>{{ $s->tenant?->name }}</td><td>{{ $s->plan?->name }}</td><td>{{ $s->status }}</td><td>{{ $s->provider }}</td></tr>
@endforeach
</tbody></table></div><div class="card-body">{{ $subscriptions->links() }}</div></div></div>
<div class="col-lg-6"><div class="card"><div class="card-header">Paiements</div><div class="table-responsive">
<table class="table mb-0"><thead><tr><th>Tenant</th><th>Montant</th><th>Status</th><th>Provider</th></tr></thead><tbody>
@foreach($payments as $p)
<tr><td>{{ $p->tenant?->name }}</td><td>{{ number_format($p->amount_cents/100,2,',',' ') }} €</td><td>{{ $p->status }}</td><td>{{ $p->provider }}</td></tr>
@endforeach
</tbody></table></div><div class="card-body">{{ $payments->links() }}</div></div></div>
</div>
@endsection
