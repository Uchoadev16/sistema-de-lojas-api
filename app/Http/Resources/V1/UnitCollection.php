<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UnitCollection extends ResourceCollection
{
    public function toArray(Request $request): array
    {
        return [
            'data' => UnitResource::collection($this->collection),
        ];
    }
}
