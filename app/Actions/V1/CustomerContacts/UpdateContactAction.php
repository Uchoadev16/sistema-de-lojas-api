<?php

namespace App\Actions\V1\CustomerContacts;

use App\Models\CustomerContact;
use Illuminate\Support\Facades\DB;

class UpdateContactAction
{
    public function execute(CustomerContact $contact, array $input): CustomerContact
    {
        return DB::transaction(function () use ($contact, $input): CustomerContact {
            $wasPrimary = (bool) $contact->is_primary;
            $isPrimaryNow = $input['is_primary'] ?? $wasPrimary;

            $contact->update($input);

            if ($isPrimaryNow && ! $wasPrimary) {
                $contact->customer->contacts()
                    ->where('id', '!=', $contact->id)
                    ->update(['is_primary' => false]);
            }

            return $contact->load('customer');
        });
    }
}
