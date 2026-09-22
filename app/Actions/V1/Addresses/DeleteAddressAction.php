<?php

namespace App\Actions\V1\Addresses;

use App\Models\Address;

class DeleteAddressAction
{
    public function execute(Address $address): void
    {
        if (method_exists($address, 'trashed') && $address->trashed()) {
            abort(400, 'Already deleted');
        }
        $address->delete();
    }
}
