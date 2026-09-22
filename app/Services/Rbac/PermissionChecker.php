<?php

namespace App\Services\Rbac;

use App\Models\User;
use App\Support\Enums\PermissionEffect;
use App\Support\Enums\UserTenantStatus;
use Illuminate\Support\Facades\Cache;

class PermissionChecker
{
    protected const WILDCARD_ALL = '*';

    protected const VERSION_KEY_PREFIX = 'rbac:version:user:';

    public function userHasPermissionTo(User $user, string $tenantId, string $permissionSlug): bool
    {
        $cacheDriver = Cache::driver(config('cache.default'));
        $version = (int) $cacheDriver->get(self::VERSION_KEY_PREFIX.$user->id, 1);
        $cacheKey = "rbac:user:{$user->id}:tenant:{$tenantId}:perm:".sha1($permissionSlug).":v{$version}";

        return $cacheDriver->remember($cacheKey, 3600, function () use ($user, $tenantId, $permissionSlug): bool {
            $userTenant = $user->userTenants()
                ->with(['role.permissions', 'userPermissions.permission'])
                ->where('tenant_id', $tenantId)
                ->where('status', UserTenantStatus::Active->value)
                ->where('is_active', true)
                ->first();

            if (! $userTenant) {
                return false;
            }

            foreach ($userTenant->userPermissions as $up) {
                if (! $up->permission) {
                    continue;
                }
                if ($this->matches($up->permission->slug, $permissionSlug)) {
                    return $up->effect === PermissionEffect::Allow;
                }
            }

            if (! $userTenant->role) {
                return false;
            }

            foreach ($userTenant->role->permissions as $permission) {
                if ($this->matches($permission->slug, $permissionSlug)) {
                    return true;
                }
            }

            return false;
        });
    }

    protected function matches(string $pattern, string $permissionSlug): bool
    {
        if ($pattern === self::WILDCARD_ALL || $pattern === $permissionSlug) {
            return true;
        }

        $patternParts = explode('.', $pattern);
        $slugParts = explode('.', $permissionSlug);

        foreach ($patternParts as $i => $part) {
            if ($part === self::WILDCARD_ALL) {
                return true;
            }
            if (! isset($slugParts[$i]) || $slugParts[$i] !== $part) {
                return false;
            }
        }

        return count($patternParts) === count($slugParts);
    }

    public function flushCacheForUser(User $user): void
    {
        if (app()->runningUnitTests()) {
            return;
        }
        $cache = Cache::driver(config('cache.default'));
        if (method_exists($cache->getStore(), 'tags')) {
            try {
                $cache->tags(['rbac', 'user:'.$user->id])->flush();

                return;
            } catch (\Throwable) {
            }
        }

        $versionKey = self::VERSION_KEY_PREFIX.$user->id;
        $currentVersion = (int) $cache->get($versionKey, 1);
        $cache->forever($versionKey, $currentVersion + 1);
    }
}
