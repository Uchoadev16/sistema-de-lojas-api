<?php

namespace App\Actions\V1\QrCodes;

use App\Models\QrCode;
use Illuminate\Support\Facades\DB;

class RestoreQrCodeAction
{
    public function execute(QrCode $qrCode): QrCode
    {
        return DB::transaction(function () use ($qrCode): QrCode {
            $qrCode->restore();

            return $qrCode;
        });
    }
}
