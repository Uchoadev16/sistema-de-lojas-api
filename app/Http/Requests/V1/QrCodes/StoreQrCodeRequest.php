<?php

namespace App\Http\Requests\V1\QrCodes;

use App\Models\QrCode;
use App\Support\Enums\QrCodeEntityType;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StoreQrCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', QrCode::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'code' => ['nullable', 'string', 'max:120', 'unique:qr_codes,code'],
            'entity_type' => ['nullable', new Enum(QrCodeEntityType::class)],
            'entity_id' => ['nullable', 'uuid'],
            'status' => ['nullable', new Enum(QrCodeStatus::class)],
            'printed' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'metadata' => ['nullable', 'array'],
        ];
    }
}
