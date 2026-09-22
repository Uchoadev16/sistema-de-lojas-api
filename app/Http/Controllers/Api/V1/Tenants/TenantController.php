<?php

namespace App\Http\Controllers\Api\V1\Tenants;

use App\Actions\V1\Tenants\UpdateTenantAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\V1\Tenants\UpdateTenantRequest;
use App\Http\Resources\V1\TenantResource;
use App\Services\Tenancy\TenantContext;

class TenantController extends Controller
{
    public function show()
    {
        $tenant = app(TenantContext::class)->tenant();
        $this->authorize('view', $tenant);

        $tenant->load([
            'saasPlan',
            'subscriptions' => fn ($q) => $q->latest()->limit(3),
        ]);

        return $this->success(['tenant' => new TenantResource($tenant)]);
    }

    public function update(UpdateTenantRequest $request, UpdateTenantAction $action)
    {
        $updated = $action->execute($request->validated());

        return $this->success(['tenant' => new TenantResource($updated)]);
    }
}
