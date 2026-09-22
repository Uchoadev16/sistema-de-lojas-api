<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\UserTenantStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserTenant extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'role_id',
        'status',
        'is_active',
        'is_default',
        'is_owner',
        'joined_at',
        'accepted_at',
        'invited_at',
        'invite_token',
        'invite_expires_at',
        'can_approve_budget',
        'max_budget_amount_cents',
        'approval_limit_scope',
        'external_id',
        'last_login_at',
        'settings',
        'created_by_user_id',
    ];

    protected $casts = [
        'status' => UserTenantStatus::class,
        'is_active' => 'boolean',
        'is_default' => 'boolean',
        'is_owner' => 'boolean',
        'accepted_at' => 'datetime',
        'invited_at' => 'datetime',
        'invite_expires_at' => 'datetime',
        'can_approve_budget' => 'boolean',
        'preferences' => 'json',
        'metadata' => 'json',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function userPermissions(): HasMany
    {
        return $this->hasMany(UserPermission::class);
    }
}
