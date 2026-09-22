<?php

namespace App\Actions\V1\QrCodes;

use App\Models\QrCode;
use App\Models\User;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LinkQrCodeAction
{
    public function execute(array $input, ?User $currentUser = null): QrCode
    {
        $currentUser ??= request()->user();

        return DB::transaction(function () use ($input): QrCode {
            $qrCode = null;

            if (! empty($input['qr_id'])) {
                $qrCode = QrCode::findOrFail($input['qr_id']);
            } elseif (! empty($input['code'])) {
                $qrCode = QrCode::where('code', $input['code'])->firstOrFail();
            }

            if (! $qrCode) {
                throw ValidationException::withMessages([
                    'qr_id' => __('qr_codes.not_found'),
                ]);
            }

            if ($qrCode->status === QrCodeStatus::Revoked) {
                throw ValidationException::withMessages([
                    'qr_id' => __('qr_codes.revoked'),
                ]);
            }

            $qrCode->entity_type = $input['entity_type'] ?? 'equipment';
            $qrCode->entity_id = $input['entity_id'];
            $qrCode->linked_at = now();
            $qrCode->status = QrCodeStatus::Linked->value;
            $qrCode->save();

            return $qrCode;
        });
    }
}
