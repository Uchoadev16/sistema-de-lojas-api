<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'guard_name',
        'is_system',
        'metadata',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'json',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id')
            ->withTimestamps();
    }

    public function userTenants(): HasMany
    {
        return $this->hasMany(UserTenant::class);
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->permissions()
            ->where('slug', $permissionSlug)
            ->orWhere(function ($query) use ($permissionSlug) {
                $parts = explode('.', $permissionSlug);
                if (count($parts) >= 2) {
                    $query->where('slug', $parts[0].'.*');
                }
                if (count($parts) >= 3) {
                    $query->orWhere('slug', $parts[0].'.'.$parts[1].'.*');
                }
            })
            ->exists();
    }

    public function syncPermissionsByIds(array $permissionIds): void
    {
        $this->permissions()->sync($permissionIds);
    }
}
