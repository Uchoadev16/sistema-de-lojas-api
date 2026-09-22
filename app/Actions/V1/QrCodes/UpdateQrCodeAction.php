<?php

namespace App\Actions\V1\QrCodes;

use App\Models\QrCode;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Support\Facades\DB;

class UpdateQrCodeAction
{
    public function execute(QrCode $qrCode, array $input): QrCode
    {
        return DB::transaction(function () use ($qrCode, $input): QrCode {
            if (isset($input['entity_id']) && ! $qrCode->linked_at) {
                $input['linked_at'] = now();
                if (! isset($input['status'])) {
                    $input['status'] = QrCodeStatus::Linked->value;
                }
            }
            $qrCode->fill($input);
            $qrCode->save();

            return $qrCode;
        });
    }
}
