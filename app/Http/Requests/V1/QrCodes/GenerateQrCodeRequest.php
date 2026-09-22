<?php

namespace App\Http\Requests\V1\QrCodes;

use Illuminate\Foundation\Http\FormRequest;

class GenerateQrCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'entity_type' => 'required|string|max:100',
            'entity_id' => 'required|string|size:36',
            'entity_name' => 'sometimes|string|max:255',
            'custom_code' => 'sometimes|string|max:255|unique:qr_codes,code',
            'printed' => 'sometimes|boolean',
            'metadata' => 'sometimes|array',
        ];
    }
}
