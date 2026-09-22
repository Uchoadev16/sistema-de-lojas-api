<?php

namespace App\Http\Requests\V1\QrCodes;

use Illuminate\Foundation\Http\FormRequest;

class LinkQrCodeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'code' => 'required|string|max:255|exists:qr_codes,code',
            'entity_type' => 'required|string|max:100',
            'entity_id' => 'required|string|size:36',
        ];
    }
}
