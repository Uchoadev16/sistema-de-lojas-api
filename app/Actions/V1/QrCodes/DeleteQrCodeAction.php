<?php

namespace App\Actions\V1\QrCodes;

use App\Models\QrCode;
use Illuminate\Support\Facades\DB;

class DeleteQrCodeAction
{
    public function execute(QrCode $qrCode): void
    {
        DB::transaction(function () use ($qrCode): void {
            $qrCode->delete();
        });
    }
}
