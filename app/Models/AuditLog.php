<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AuditLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'actor_user_id',
        'tenant_id',
        'action',
        'payload',
        'ip_address',
    ];

    protected $casts = [
        'payload' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_user_id');
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public static function record(string $action, ?Tenant $tenant = null, ?array $payload = null, ?User $actor = null): self
    {
        return static::create([
            'actor_user_id' => $actor?->id ?? auth()->id(),
            'tenant_id' => $tenant?->id,
            'action' => $action,
            'payload' => $payload,
            'ip_address' => request()?->ip(),
        ]);
    }
}
