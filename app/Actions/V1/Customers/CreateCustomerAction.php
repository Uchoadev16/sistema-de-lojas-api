<?php

namespace App\Actions\V1\Customers;

use App\Models\Customer;
use App\Services\Tenancy\TenantContext;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CreateCustomerAction
{
    public function execute(array $input): Customer
    {
        $tenantId = app(TenantContext::class)->id();
        $userId = Auth::id();

        return DB::transaction(function () use ($input, $tenantId, $userId): Customer {
            $tagIds = $input['tag_ids'] ?? [];
            unset($input['tag_ids']);

            $customer = Customer::create(array_merge($input, [
                'tenant_id' => $tenantId,
                'created_by_user_id' => $userId,
            ]));

            if (! empty($tagIds)) {
                $customer->tags()->sync($tagIds);
            }

            return $customer->load(['tags', 'addresses', 'contacts']);
        });
    }
}
