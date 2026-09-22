<?php

namespace App\Actions\V1\CustomerContacts;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\DB;

class CreateContactAction
{
    public function execute(Customer $customer, array $input): CustomerContact
    {
        $tenantId = app(TenantContext::class)->id();

        return DB::transaction(function () use ($customer, $input, $tenantId): CustomerContact {
            $contact = $customer->contacts()->create(array_merge($input, [
                'tenant_id' => $tenantId,
            ]));

            if (! empty($input['is_primary'])) {
                $customer->contacts()
                    ->where('id', '!=', $contact->id)
                    ->update(['is_primary' => false]);
            }

            return $contact->load('customer');
        });
    }
}
