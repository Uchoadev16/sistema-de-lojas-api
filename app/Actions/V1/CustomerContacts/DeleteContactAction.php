<?php

namespace App\Actions\V1\CustomerContacts;

use App\Models\CustomerContact;
use Illuminate\Support\Facades\DB;

class DeleteContactAction
{
    public function execute(CustomerContact $contact): void
    {
        DB::transaction(function () use ($contact): void {
            $contact->delete();
        });
    }
}
