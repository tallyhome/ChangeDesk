<?php

namespace App\Models\Concerns;

use App\Models\Tenant;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    public static function bootBelongsToTenant(): void
    {
        static::addGlobalScope('tenant', function (Builder $builder) {
            $tenant = Tenant::current();

            if ($tenant) {
                $builder->where($builder->getModel()->getTable().'.tenant_id', $tenant->id);

                return;
            }

            if (app()->bound('tenancy.bypass') && app('tenancy.bypass') === true) {
                return;
            }

            // Artisan (hors tests) : pas de filtre pour seed/migrate.
            if (app()->runningInConsole() && ! app()->runningUnitTests()) {
                return;
            }

            $builder->whereRaw('1 = 0');
        });

        static::creating(function (Model $model) {
            if (! $model->getAttribute('tenant_id') && Tenant::current()) {
                $model->setAttribute('tenant_id', Tenant::current()->id);
            }
        });
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function scopeWithoutTenancy(Builder $query): Builder
    {
        return $query->withoutGlobalScope('tenant');
    }
}
