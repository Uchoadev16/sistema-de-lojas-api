<?php

namespace App\Actions\V1\Addresses;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class CreateAddressAction
{
    public function execute(array $input): Address
    {
        return DB::transaction(function () use ($input): Address {
            return Address::create($input);
        });
    }
}
