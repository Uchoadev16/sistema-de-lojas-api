<?php

namespace App\Actions\V1\Addresses;

use App\Models\Address;
use Illuminate\Support\Facades\DB;

class UpdateAddressAction
{
    public function execute(Address $address, array $input): Address
    {
        return DB::transaction(function () use ($address, $input): Address {
            $address->update($input);

            return $address;
        });
    }
}
