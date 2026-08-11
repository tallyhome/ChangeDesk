<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function index()
    {
        $tenants = Tenant::query()
            ->withCount('users')
            ->latest()
            ->paginate(20);

        $stats = [
            'tenants' => Tenant::count(),
            'active' => Tenant::where('is_active', true)->count(),
            'clients' => User::where('role', User::ROLE_CLIENT)->count(),
            'verified_domains' => Tenant::where('domain_status', Tenant::DOMAIN_VERIFIED)->count(),
        ];

        return view('superadmin.tenants.index', compact('tenants', 'stats'));
    }

    public function toggle(Tenant $tenant)
    {
        $tenant->update(['is_active' => ! $tenant->is_active]);

        return back()->with(
            'success',
            $tenant->is_active ? 'Tenant activé.' : 'Tenant désactivé.'
        );
    }

    public function show(Tenant $tenant)
    {
        $tenant->load('users');

        return view('superadmin.tenants.show', compact('tenant'));
    }
}
