<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use App\Services\Tenancy\TenantContext;
use App\Support\Enums\UserTenantStatus;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            return $next($request);
        }

        if ($user->is_platform_admin) {
            $explicitTenantId = $request->header('X-Tenant-ID') ?: $request->input('tenant_id');
            if ($explicitTenantId) {
                $tenant = Tenant::find($explicitTenantId);
                if ($tenant) {
                    app(TenantContext::class)->set($tenant);
                }
            }

            try {
                return $next($request);
            } finally {
                app(TenantContext::class)->clear();
            }
        }

        $explicitTenantId = $request->header('X-Tenant-ID') ?: $request->input('tenant_id');
        $tenant = null;

        if ($explicitTenantId) {
            $userTenant = $user->userTenants()
                ->where('tenant_id', $explicitTenantId)
                ->where('status', UserTenantStatus::Active->value)
                ->first();

            abort_if(! $userTenant, 403, __('You do not have access to this tenant.'));
            $tenant = $userTenant->tenant;
        } else {
            $default = $user->userTenants()
                ->where('status', UserTenantStatus::Active->value)
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'asc')
                ->first();

            abort_if(! $default, 403, __('You have no active tenants.'));
            $tenant = $default->tenant;
        }

        if ($tenant && ! $tenant->is_active) {
            abort(403, __('Tenant is not active.'));
        }

        app(TenantContext::class)->set($tenant);

        try {
            $response = $next($request);
        } finally {
            app(TenantContext::class)->clear();
        }

        return $response;
    }
}
