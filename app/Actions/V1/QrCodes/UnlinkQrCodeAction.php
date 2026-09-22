<?php

namespace App\Actions\V1\QrCodes;

use App\Models\QrCode;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Support\Facades\DB;

class UnlinkQrCodeAction
{
    public function execute(QrCode $qrCode): QrCode
    {
        return DB::transaction(function () use ($qrCode): QrCode {
            $qrCode->entity_id = null;
            $qrCode->linked_at = null;
            $qrCode->status = QrCodeStatus::Unlinked->value;
            $qrCode->save();

            return $qrCode;
        });
    }
}
