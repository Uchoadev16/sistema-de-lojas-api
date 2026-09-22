<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CustomerCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => CustomerResource::collection($this->collection),
        ];
    }
}
