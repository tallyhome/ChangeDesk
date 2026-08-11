<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PlanController extends Controller
{
    public function index()
    {
        $plans = Plan::orderBy('sort_order')->orderBy('id')->get();

        return view('superadmin.plans.index', compact('plans'));
    }

    public function create()
    {
        return view('superadmin.plans.form', [
            'plan' => new Plan([
                'interval' => 'month',
                'is_active' => true,
                'sort_order' => 10,
                'features' => [
                    'changelog' => true,
                    'todolist' => false,
                    'bugs' => false,
                    'wiki' => false,
                    'pages' => true,
                    'stats' => false,
                    'custom_domain' => false,
                    'branding' => false,
                    'priority_support' => false,
                    'themes' => ['classic'],
                ],
            ]),
        ]);
    }

    public function store(Request $request)
    {
        $plan = $this->savePlan(new Plan(), $request);
        AuditLog::record('plan.created', null, ['plan_id' => $plan->id]);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan créé.');
    }

    public function edit(Plan $plan)
    {
        return view('superadmin.plans.form', compact('plan'));
    }

    public function update(Request $request, Plan $plan)
    {
        $this->savePlan($plan, $request);
        AuditLog::record('plan.updated', null, ['plan_id' => $plan->id]);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan mis à jour.');
    }

    public function destroy(Plan $plan)
    {
        if ($plan->subscriptions()->whereIn('status', ['active', 'trialing'])->exists()) {
            return back()->with('error', 'Impossible de supprimer un plan avec des abonnements actifs.');
        }

        $plan->delete();
        AuditLog::record('plan.deleted', null, ['slug' => $plan->slug]);

        return redirect()->route('superadmin.plans.index')->with('success', 'Plan supprimé.');
    }

    protected function savePlan(Plan $plan, Request $request): Plan
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'alpha_dash', 'max:64', Rule::unique('plans', 'slug')->ignore($plan->id)],
            'price_euros' => ['nullable', 'numeric', 'min:0'],
            'interval' => ['required', 'in:month,year'],
            'stripe_price_id' => ['nullable', 'string', 'max:255'],
            'paypal_plan_id' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
            'features' => ['nullable', 'array'],
            'themes' => ['nullable', 'array'],
        ]);

        $boolFeatures = [
            'changelog', 'todolist', 'bugs', 'wiki', 'pages', 'stats',
            'custom_domain', 'branding', 'priority_support',
        ];
        $features = [];
        foreach ($boolFeatures as $key) {
            $features[$key] = (bool) data_get($data, "features.$key", false);
        }
        $features['changelog'] = true;
        $themes = array_values(array_intersect(
            $data['themes'] ?? ['classic'],
            ['classic', 'midnight', 'editorial', 'aurora']
        ));
        $features['themes'] = $themes ?: ['classic'];

        $plan->fill([
            'name' => $data['name'],
            'slug' => Str::slug($data['slug']),
            'price_cents' => (int) round(((float) ($data['price_euros'] ?? 0)) * 100),
            'interval' => $data['interval'],
            'stripe_price_id' => $data['stripe_price_id'] ?: null,
            'paypal_plan_id' => $data['paypal_plan_id'] ?: null,
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'is_active' => $request->boolean('is_active'),
            'features' => $features,
        ])->save();

        return $plan;
    }
}
