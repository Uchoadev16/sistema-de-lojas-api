<?php

namespace App\Actions\V1\Customers;

use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class RestoreCustomerAction
{
    public function execute(Customer $customer): Customer
    {
        return DB::transaction(function () use ($customer): Customer {
            $customer->restore();

            return $customer;
        });
    }
}
