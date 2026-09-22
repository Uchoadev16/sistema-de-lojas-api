<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\TenantStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'legal_name',
        'trade_name',
        'slug',
        'document',
        'ie_rg',
        'im',
        'email',
        'phone',
        'mobile',
        'website',
        'logo_path',
        'banner_path',
        'primary_color',
        'secondary_color',
        'address_street',
        'address_number',
        'address_complement',
        'address_neighborhood',
        'address_city',
        'address_state',
        'address_postal_code',
        'address_latitude',
        'address_longitude',
        'timezone',
        'locale',
        'currency',
        'date_format',
        'about',
        'business_hours',
        'cancellation_policy',
        'terms_of_service',
        'privacy_policy',
        'settings',
        'preferences',
        'metadata',
    ];

    protected $casts = [
        'status' => TenantStatus::class,
        'is_active' => 'boolean',
        'settings' => 'json',
        'preferences' => 'json',
        'metadata' => 'json',
        'trial_ends_at' => 'datetime',
        'suspended_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'onboarded_at' => 'datetime',
    ];

    public function saasPlan(): BelongsTo
    {
        return $this->belongsTo(SaasPlan::class, 'saas_plan_id');
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(SaasSubscription::class);
    }

    public function userTenants(): HasMany
    {
        return $this->hasMany(UserTenant::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(
            User::class,
            UserTenant::class,
            'tenant_id',
            'id',
            'id',
            'user_id',
        );
    }

    public function roles(): HasMany
    {
        return $this->hasMany(Role::class);
    }

    public function permissions(): HasMany
    {
        return $this->hasMany(Permission::class);
    }

    public function invites(): HasMany
    {
        return $this->hasMany(Invite::class);
    }
}
