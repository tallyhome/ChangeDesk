<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function leave(Request $request)
    {
        $impersonatorId = $request->session()->pull('impersonator_id');
        abort_unless($impersonatorId, 403);

        $admin = User::findOrFail($impersonatorId);
        Auth::login($admin);
        Tenant::forgetCurrent();

        AuditLog::record('impersonation.stop', null, ['superadmin_id' => $admin->id]);

        return redirect()->route('superadmin.dashboard')->with('success', 'Impersonation terminée.');
    }
}
