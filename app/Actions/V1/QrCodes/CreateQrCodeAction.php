<?php

namespace App\Actions\V1\QrCodes;

use App\Models\QrCode;
use App\Models\User;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateQrCodeAction
{
    public function execute(array $input, ?User $currentUser = null): QrCode
    {
        $currentUser ??= request()->user();

        return DB::transaction(function () use ($input, $currentUser): QrCode {
            $input['code'] = $input['code'] ?? ('QR-'.strtoupper(Str::random(20)));
            $input['generated_by_user_id'] = $currentUser?->id;
            if (! isset($input['status'])) {
                $input['status'] = isset($input['entity_id'])
                    ? QrCodeStatus::Linked->value
                    : QrCodeStatus::Unlinked->value;
            }
            if (isset($input['entity_id']) && ! isset($input['linked_at'])) {
                $input['linked_at'] = now();
            }

            return QrCode::create($input);
        });
    }
}
