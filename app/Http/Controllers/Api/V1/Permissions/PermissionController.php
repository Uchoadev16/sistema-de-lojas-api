<?php

namespace App\Http\Controllers\Api\V1\Permissions;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\PermissionCollection;
use App\Models\Permission;
use App\Services\Tenancy\TenantContext;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize('viewAny', Permission::class);

        $tenantId = app(TenantContext::class)->hasTenant() ? app(TenantContext::class)->id() : null;

        $query = Permission::query()
            ->where(fn ($q) => $q->where('tenant_id', $tenantId)->orWhereNull('tenant_id'))
            ->where('is_active', true);

        if ($request->filled('module')) {
            $query->whereIn('module', (array) $request->input('module'));
        }
        if ($request->filled('group')) {
            $query->whereIn('group', (array) $request->input('group'));
        }
        if ($request->filled('search')) {
            $s = $request->input('search');
            $query->where(fn ($q) => $q->where('name', 'ILIKE', "%{$s}%")->orWhere('slug', 'ILIKE', "%{$s}%"));
        }

        $query->orderBy('module')->orderBy('group')->orderBy('slug');

        return $this->success(
            (new PermissionCollection($query->get()))->response()->getData(true)
        );
    }
}
