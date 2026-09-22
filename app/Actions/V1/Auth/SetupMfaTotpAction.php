<?php

namespace App\Actions\V1\Auth;

use App\Models\MfaMethod;
use App\Models\User;
use App\Support\Enums\MfaType;
use ParagonIE\ConstantTime\Base32;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class SetupMfaTotpAction
{
    public function execute(User $user, array $input): array
    {
        $label = $input['label'] ?? 'TOTP';

        [$secret, $provisioningUri, $qrCodeDataUri] = $this->generateTotp($user, $label);

        $method = MfaMethod::create([
            'user_id' => $user->id,
            'method_type' => MfaType::Totp->value,
            'label' => $label,
            'secret' => encrypt($secret),
            'is_enabled' => false,
            'is_default' => false,
        ]);

        return [
            'mfa_method' => $method,
            'secret' => $secret,
            'provisioning_uri' => $provisioningUri,
            'qr_code_data_uri' => $qrCodeDataUri,
        ];
    }

    protected function generateTotp(User $user, string $label): array
    {
        $secret = trim(Base32::encodeUpper(random_bytes(16)), '=');
        $account = $user->email;
        $issuer = config('app.name', 'ClimaOps');
        $provisioningUri = sprintf(
            'otpauth://totp/%s:%s?secret=%s&issuer=%s&algorithm=SHA1&digits=6&period=30',
            rawurlencode($issuer),
            rawurlencode($account),
            $secret,
            rawurlencode($issuer)
        );

        $qrCode = QrCode::format('png')
            ->size(256)
            ->errorCorrection('M')
            ->generate($provisioningUri);
        $qrCodeDataUri = 'data:image/png;base64,'.base64_encode($qrCode);

        return [$secret, $provisioningUri, $qrCodeDataUri];
    }
}
