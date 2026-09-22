<?php

namespace App\Http\Requests\V1\QrCodes;

use App\Models\QrCode;
use App\Support\Enums\QrCodeEntityType;
use App\Support\Enums\QrCodeStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateQrCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('update', $this->route('qr_code') ?? QrCode::class) ?? false;
    }

    public function rules(): array
    {
        $qrId = $this->route('qr_code')?->id ?? $this->route('qr_code');

        return [
            'code' => ['sometimes', 'string', 'max:120', 'unique:qr_codes,code,'.$qrId],
            'entity_type' => ['sometimes', 'nullable', new Enum(QrCodeEntityType::class)],
            'entity_id' => ['sometimes', 'nullable', 'uuid'],
            'status' => ['sometimes', 'nullable', new Enum(QrCodeStatus::class)],
            'printed' => ['sometimes', 'boolean'],
            'is_active' => ['sometimes', 'boolean'],
            'metadata' => ['sometimes', 'nullable', 'array'],
        ];
    }
}
