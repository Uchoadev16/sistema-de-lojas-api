<?php

namespace App\Actions\V1\Environments;

use App\Models\Environment;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;

class CreateEnvironmentAction
{
    public function execute(array $input): Environment
    {
        $tenantId = app(TenantContext::class)->id();

        return DB::transaction(function () use ($input, $tenantId): Environment {
            $environment = Environment::create(array_merge($input, [
                'tenant_id' => $tenantId,
            ]));

            return $environment->load(['unit']);
        });
    }
}
