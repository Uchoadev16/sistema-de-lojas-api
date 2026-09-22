<?php

namespace App\Actions\V1\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DeleteCustomerAction
{
    public function execute(Customer $customer): void
    {
        DB::transaction(function () use ($customer): void {
            $customer->tags()->detach();
            $customer->addresses()->detach();
            $customer->delete();
        });
    }
}
