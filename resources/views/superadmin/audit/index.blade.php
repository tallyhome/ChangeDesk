@extends('superadmin.layout')
@section('title', 'Audit')
@section('content')
<h1 class="mb-4">Logs d'audit</h1>
<form method="GET" class="mb-3"><input name="action" value="{{ request('action') }}" class="form-control" placeholder="Filtrer action" style="max-width:320px"></form>
<div class="card table-responsive">
<table class="table mb-0">
<thead><tr><th>Date</th><th>Action</th><th>Acteur</th><th>Tenant</th><th>Payload</th></tr></thead>
<tbody>
@foreach($logs as $log)
<tr>
<td>{{ $log->created_at }}</td>
<td><code>{{ $log->action }}</code></td>
<td>{{ $log->actor?->email ?? '—' }}</td>
<td>{{ $log->tenant?->name ?? '—' }}</td>
<td><small>{{ Str::limit(json_encode($log->payload), 80) }}</small></td>
</tr>
@endforeach
</tbody></table>
<div class="card-body">{{ $logs->links() }}</div>
</div>
@endsection
