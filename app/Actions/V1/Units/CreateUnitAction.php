<?php

namespace App\Actions\V1\Units;

use App\Models\Unit;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateUnitAction
{
    public function execute(array $input): Unit
    {
        $tenantId = app(TenantContext::class)->id();
        $userId = Auth::id();

        return DB::transaction(function () use ($input, $tenantId, $userId): Unit {
            $unit = Unit::create(array_merge($input, [
                'tenant_id' => $tenantId,
                'created_by_user_id' => $userId,
            ]));

            return $unit->load(['customer', 'address', 'environments', 'responsibleContact']);
        });
    }
}
