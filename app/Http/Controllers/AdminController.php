<?php

namespace App\Http\Controllers;

use App\Models\ChangelogSubscriber;
use App\Models\Page;
use App\Models\RoadmapVote;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Version;
use App\Models\Visit;
use App\Services\PlanGate;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(PlanGate $gate)
    {
        $tenant = Tenant::current()->load('plan');
        $pages = Page::select('id', 'title', 'updated_at', 'created_at')->get();
        $analytics = Visit::overview();
        $publishedMonth = Version::where('release_date', '>=', now()->startOfMonth())->count();
        $publishedTotal = Version::count();
        $recentVersions = Version::orderByDesc('release_date')->orderByDesc('id')->limit(5)->get();

        $domainReady = filled($tenant->slug)
            || filled($tenant->custom_domain)
            || $tenant->domain_status === Tenant::DOMAIN_PENDING
            || $tenant->isCustomDomainVerified();

        $owner = (bool) auth()->user()?->isProjectOwner();
        $checklist = [
            [
                'label' => __('app.admin.check_changelog'),
                'done' => Version::count() > 0,
                'url' => route('admin.changelog.create'),
                'owner' => false,
            ],
            [
                'label' => __('app.admin.check_domain'),
                'done' => $domainReady,
                'url' => route('admin.domain.edit'),
                'owner' => true,
            ],
            [
                'label' => __('app.admin.check_theme'),
                'done' => filled($tenant->visual_theme),
                'url' => route('admin.appearance.edit'),
                'owner' => false,
            ],
            [
                'label' => __('app.admin.check_plan'),
                'done' => (bool) $tenant->plan_id,
                'url' => route('admin.billing.index'),
                'owner' => true,
            ],
        ];
        if (! $owner) {
            $checklist = array_values(array_filter($checklist, fn (array $item) => ! $item['owner']));
        }

        $growth = [
            'subscribers' => ChangelogSubscriber::query()->whereNotNull('confirmed_at')->count(),
            'subscribers_pending' => ChangelogSubscriber::query()->whereNull('confirmed_at')->count(),
            'votes' => RoadmapVote::query()->count(),
            'editors' => User::query()
                ->where('tenant_id', $tenant->id)
                ->where('role', User::ROLE_CLIENT)
                ->where('project_role', User::PROJECT_EDITOR)
                ->count(),
            'public_url' => $tenant->publicBaseUrl(),
            'mail_log' => config('mail.default') === 'log',
            'owner' => $owner,
        ];

        return view('admin.dashboard', compact(
            'pages',
            'tenant',
            'checklist',
            'analytics',
            'publishedMonth',
            'publishedTotal',
            'recentVersions',
            'growth',
            'owner'
        ));
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
        $page->update($request->only(['title', 'content', 'slug']));

        return redirect()->route('admin.pages.index')->with('success', __('app.flash.page_saved'));
    }

    public function changelog()
    {
        $page = Page::select('id', 'title', 'content', 'slug')
            ->where(function ($query) {
                $query->where('slug', 'changelog')->orWhere('title', 'Changelog');
            })
            ->first();

        return view('admin.changelog', compact('page'));
    }
}
