<?php

namespace App\Models;

use App\Models\Concerns\BelongsToTenant;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BugWatcher extends Model
{
    use BelongsToTenant;

    protected $fillable = ['tenant_id', 'bug_report_id', 'email', 'token', 'confirmed_at'];

    protected $casts = ['confirmed_at' => 'datetime'];

    public function bugReport(): BelongsTo
    {
        return $this->belongsTo(BugReport::class);
    }
}
