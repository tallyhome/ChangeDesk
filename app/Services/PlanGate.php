<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\Tenant;

class PlanGate
{
    public function planFor(?Tenant $tenant): Plan
    {
        if (! $tenant) {
            return Plan::where('slug', 'free')->firstOrFail();
        }

        $plan = $tenant->effectivePlan();
        if ($plan) {
            return $plan;
        }

        return Plan::where('slug', 'free')->firstOrFail();
    }

    public function can(?Tenant $tenant, string $feature, mixed $value = true): bool
    {
        if (! $tenant) {
            return false;
        }

        $overrides = $tenant->feature_overrides ?? [];
        if (array_key_exists($feature, $overrides)) {
            $override = $overrides[$feature];
            if (is_array($override)) {
                return in_array($value, $override, true);
            }

            return (bool) $override;
        }

        $plan = $this->planFor($tenant);
        $features = $plan->features ?? [];

        return match ($feature) {
            'changelog' => (bool) ($features['changelog'] ?? true),
            'todolist' => (bool) ($features['todolist'] ?? false),
            'bugs' => (bool) ($features['bugs'] ?? false),
            'wiki' => (bool) ($features['wiki'] ?? false),
            'pages' => (bool) ($features['pages'] ?? false),
            'stats' => (bool) ($features['stats'] ?? false),
            'custom_domain' => (bool) ($features['custom_domain'] ?? false),
            'branding' => (bool) ($features['branding'] ?? false),
            'theme' => in_array((string) $value, $features['themes'] ?? ['classic'], true),
            'priority_support' => (bool) ($features['priority_support'] ?? false),
            default => (bool) ($features[$feature] ?? false),
        };
    }

    public function allowedThemes(?Tenant $tenant): array
    {
        $plan = $this->planFor($tenant);
        $overrides = $tenant?->feature_overrides ?? [];
        if (isset($overrides['themes']) && is_array($overrides['themes'])) {
            return $overrides['themes'];
        }

        return $plan->allowedThemes();
    }
}
