<?php

namespace App\Actions\V1\QrCodes;

use App\Models\QrCode;
use App\Models\User;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class GenerateQrCodeAction
{
    public function execute(array $input = [], ?User $currentUser = null): QrCode|Collection
    {
        $currentUser ??= request()->user();
        $quantity = $input['quantity'] ?? 1;

        if ($quantity > 1) {
            return DB::transaction(function () use ($quantity, $input, $currentUser) {
                $codes = [];
                for ($i = 0; $i < $quantity; $i++) {
                    $codes[] = QrCode::create([
                        'code' => 'QR-'.strtoupper(Str::random(20)),
                        'entity_type' => $input['entity_type'] ?? 'equipment',
                        'generated_by_user_id' => $currentUser?->id,
                        'status' => QrCodeStatus::Unlinked->value,
                        'printed' => false,
                        'is_active' => true,
                        'metadata' => $input['metadata'] ?? null,
                    ]);
                }

                return collect($codes);
            });
        }

        return DB::transaction(function () use ($input, $currentUser): QrCode {
            return QrCode::create([
                'code' => $input['code'] ?? ('QR-'.strtoupper(Str::random(20))),
                'entity_type' => $input['entity_type'] ?? 'equipment',
                'generated_by_user_id' => $currentUser?->id,
                'status' => QrCodeStatus::Unlinked->value,
                'printed' => false,
                'is_active' => true,
                'metadata' => $input['metadata'] ?? null,
            ]);
        });
    }
}
