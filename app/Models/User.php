<?php

namespace App\Models;

use App\Services\Rbac\PermissionChecker;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserStatus;
use App\Support\Enums\UserTenantStatus;
use App\Support\Traits\HasUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use HasUuid;
    use Notifiable;
    use SoftDeletes;

    protected $primaryKey = 'id';

    protected $keyType = 'string';

    public $incrementing = false;

    protected $fillable = [
        'name',
        'email',
        'password',
        'document',
        'phone',
        'mobile',
        'birth_date',
        'gender',
        'avatar_path',
        'about',
        'locale',
        'timezone',
        'currency',
        'date_format',
        'preferences',
        'metadata',
        'external_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'current_mfa_challenge_id',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'status' => UserStatus::class,
        'birth_date' => 'date',
        'is_active' => 'boolean',
        'receive_email_notifications' => 'boolean',
        'receive_sms_notifications' => 'boolean',
        'receive_whatsapp_notifications' => 'boolean',
        'receive_push_notifications' => 'boolean',
        'is_platform_admin' => 'boolean',
        'mfa_enabled' => 'boolean',
        'last_login_at' => 'datetime',
        'locked_until' => 'datetime',
        'preferences' => 'json',
        'metadata' => 'json',
    ];

    public function userTenants(): HasMany
    {
        return $this->hasMany(UserTenant::class);
    }

    public function tenants(): HasManyThrough
    {
        return $this->hasManyThrough(
            Tenant::class,
            UserTenant::class,
            'user_id',
            'id',
            'id',
            'tenant_id',
        );
    }

    public function defaultUserTenant()
    {
        return $this->hasOne(UserTenant::class)->where('is_default', true);
    }

    public function mfaMethods(): HasMany
    {
        return $this->hasMany(MfaMethod::class);
    }

    public function devices(): HasMany
    {
        return $this->hasMany(Device::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function auditEvents(): HasMany
    {
        return $this->hasMany(AuditEvent::class);
    }

    public function hasPermission(string $permissionSlug, ?Tenant $tenant = null): bool
    {
        if ($this->is_platform_admin) {
            return true;
        }

        $tenantId = $tenant?->id ?: (app(TenantContext::class)->hasTenant()
            ? app(TenantContext::class)->id()
            : null);

        if (! $tenantId) {
            return false;
        }

        return app(PermissionChecker::class)->userHasPermissionTo($this, $tenantId, $permissionSlug);
    }

    public function hasRole(string $roleSlug, ?Tenant $tenant = null): bool
    {
        $tenantId = $tenant?->id ?: (app(TenantContext::class)->hasTenant()
            ? app(TenantContext::class)->id()
            : null);

        if (! $tenantId) {
            return false;
        }

        return $this->userTenants()
            ->where('tenant_id', $tenantId)
            ->where('status', UserTenantStatus::Active->value)
            ->whereHas('role', fn ($q) => $q->where('slug', $roleSlug))
            ->exists();
    }
}
