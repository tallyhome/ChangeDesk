<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChangelogSubscriber extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'email', 'token', 'confirmed_at'];

    protected $casts = ['confirmed_at' => 'datetime'];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
