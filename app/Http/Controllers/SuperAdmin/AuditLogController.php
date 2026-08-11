<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $logs = AuditLog::query()
            ->with(['actor', 'tenant'])
            ->when($request->action, fn ($q) => $q->where('action', 'like', '%'.$request->action.'%'))
            ->latest()
            ->paginate(40)
            ->withQueryString();

        return view('superadmin.audit.index', compact('logs'));
    }
}
