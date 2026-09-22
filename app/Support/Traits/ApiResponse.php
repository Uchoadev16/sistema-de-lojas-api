<?php

namespace App\Support\Traits;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\JsonResource;

trait ApiResponse
{
    protected function success(mixed $data = null, int $code = 200, array $meta = []): JsonResponse
    {
        $payload = ['success' => true, 'data' => $data];
        if ($meta !== []) {
            $payload['meta'] = $meta;
        }

        return response()->json($payload, $code);
    }

    protected function error(string $message, int $code = 400, mixed $errors = null, mixed $data = null): JsonResponse
    {
        $payload = ['success' => false, 'message' => $message];
        if ($errors !== null) {
            $payload['errors'] = $errors;
        }
        if ($data !== null) {
            $payload['data'] = $data;
        }

        return response()->json($payload, $code);
    }

    protected function paginated(mixed $resource, int $code = 200): JsonResponse
    {
        $resource instanceof JsonResource;

        return response()->json($resource, $code);
    }
}
