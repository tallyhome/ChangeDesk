<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    public const ROLE_SUPERADMIN = 'superadmin';

    public const ROLE_CLIENT = 'client';

    public const PROJECT_OWNER = 'owner';

    public const PROJECT_EDITOR = 'editor';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'project_role',
        'tenant_id',
        'preferred_plan_id',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function preferredPlan(): BelongsTo
    {
        return $this->belongsTo(Plan::class, 'preferred_plan_id');
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === self::ROLE_SUPERADMIN;
    }

    public function isClient(): bool
    {
        return $this->role === self::ROLE_CLIENT;
    }

    public function isProjectOwner(): bool
    {
        return $this->isClient() && ($this->project_role ?: self::PROJECT_OWNER) !== self::PROJECT_EDITOR;
    }

    public function isEditor(): bool
    {
        return $this->isClient() && $this->project_role === self::PROJECT_EDITOR;
    }
}
