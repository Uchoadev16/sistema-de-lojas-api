<?php

namespace App\Actions\V1\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class UpdateCustomerAction
{
    public function execute(Customer $customer, array $input): Customer
    {
        return DB::transaction(function () use ($customer, $input): Customer {
            $tagIds = $input['tag_ids'] ?? null;
            unset($input['tag_ids']);

            $customer->update($input);

            if (is_array($tagIds)) {
                $customer->tags()->sync($tagIds);
            }

            return $customer->load(['tags', 'addresses', 'contacts']);
        });
    }
}
