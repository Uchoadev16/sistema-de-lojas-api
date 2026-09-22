<?php

namespace App\Http\Requests\V1\Customers;

use Illuminate\Foundation\Http\FormRequest;

class DetachCustomerTagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tag_ids' => 'required|array|min:1',
            'tag_ids.*' => 'required|string|size:36|exists:tags,id',
        ];
    }
}
