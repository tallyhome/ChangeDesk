<?php

namespace App\Http\Controllers;

use App\Models\Page;
use App\Models\Tenant;
use App\Models\Version;
use App\Services\PlanGate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(PlanGate $gate)
    {
        $tenant = Tenant::current()->load('plan');
        $pages = Page::select('id', 'title', 'updated_at', 'created_at')->get();

        $checklist = [
            [
                'label' => 'Créer une première version changelog',
                'done' => Version::count() > 0,
                'url' => route('admin.changelog.create'),
            ],
            [
                'label' => 'Configurer le domaine (slug ou custom)',
                'done' => filled($tenant->slug) && ($tenant->domain_status !== 'none' || true),
                'url' => route('admin.domain.edit'),
            ],
            [
                'label' => 'Choisir un thème public',
                'done' => filled($tenant->visual_theme),
                'url' => route('admin.appearance.edit'),
            ],
            [
                'label' => 'Choisir / upgrader le plan',
                'done' => (bool) $tenant->plan_id,
                'url' => route('admin.billing.index'),
            ],
        ];

        // Domain config "done" if custom verified OR slug exists (always after signup)
        $checklist[1]['done'] = $tenant->isCustomDomainVerified()
            || $tenant->domain_status === 'pending'
            || filled($tenant->custom_domain)
            || Version::count() > 0; // soft: mark after first content OR domain work
        if (! $tenant->custom_domain && $tenant->domain_status === 'none') {
            $checklist[1]['done'] = false;
        }

        return view('admin.dashboard', compact('pages', 'tenant', 'checklist'));
    }

    public function index()
    {
        $pages = Page::select('id', 'title', 'slug')->orderBy('title')->get();

        return view('admin.pages.index', compact('pages'));
    }

    public function edit($id)
    {
        $page = Page::select('id', 'title', 'content')->findOrFail($id);

        return view('admin.pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = Page::findOrFail($id);
        $page->update($request->all());

        return redirect()->route('admin.pages.index')->with('success', 'Page mise à jour avec succès');
    }

    public function changelog()
    {
        $page = Page::select('id', 'title', 'content', 'slug')
            ->where('slug', 'changelog')
            ->orWhere('title', 'Changelog')
            ->first();

        return view('admin.changelog', compact('page'));
    }
}
