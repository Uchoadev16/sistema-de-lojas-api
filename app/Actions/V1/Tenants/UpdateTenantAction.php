<?php

namespace App\Actions\V1\Tenants;

use App\Events\Tenancy\TenantUpdated;
use App\Models\Tenant;
use App\Services\Tenancy\TenantContext;

class UpdateTenantAction
{
    public function execute(array $input): Tenant
    {
        $tenant = app(TenantContext::class)->tenant();

        $addressFields = [
            'address_street', 'address_number', 'address_complement', 'address_neighborhood',
            'address_city', 'address_state', 'address_postal_code', 'address_latitude', 'address_longitude',
        ];

        foreach ($input as $key => $value) {
            if (in_array($key, $addressFields, true)) {
                $tenant->{$key} = $value;

                continue;
            }
            if (in_array($key, ['settings', 'preferences'], true) && is_array($value)) {
                $current = is_array($tenant->{$key}) ? $tenant->{$key} : ($tenant->{$key} ?? []);
                $tenant->{$key} = array_replace_recursive($current, $value);

                continue;
            }
            $tenant->{$key} = $value;
        }

        $tenant->save();

        event(new TenantUpdated($tenant));

        return $tenant;
    }
}
