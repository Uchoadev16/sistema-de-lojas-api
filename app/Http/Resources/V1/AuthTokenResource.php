<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AuthTokenResource extends JsonResource
{
    public static $wrap = null;

    public function toArray(Request $request): array
    {
        $token = $this->resource['token'] ?? null;
        $plainToken = $this->resource['plain_text_token'] ?? null;
        $expiresIn = $this->resource['expires_in'] ?? null;
        $abilities = $this->resource['abilities'] ?? ['*'];
        $tokenName = $this->resource['token_name'] ?? 'api-token';
        $mfaRequired = (bool) ($this->resource['mfa_required'] ?? false);
        $mfaChallengeId = $this->resource['mfa_challenge_id'] ?? null;

        $payload = [
            'token_type' => 'Bearer',
            'access_token' => $plainToken,
            'token_id' => $token?->getKey(),
            'token_name' => $tokenName,
            'expires_in' => $expiresIn,
            'abilities' => $abilities,
        ];

        if ($mfaRequired) {
            $payload['mfa_required'] = true;
            $payload['mfa_challenge_id'] = $mfaChallengeId;
            $payload['available_methods'] = $this->resource['available_methods'] ?? [];
            unset($payload['access_token']);
            unset($payload['expires_in']);
        }

        if (isset($this->resource['user'])) {
            $payload['user'] = new UserResource($this->resource['user']);
        }
        if (isset($this->resource['tenant'])) {
            $payload['tenant'] = new TenantResource($this->resource['tenant']);
        }
        if (isset($this->resource['available_tenants'])) {
            $payload['available_tenants'] = TenantResource::collection($this->resource['available_tenants']);
        }
        if (isset($this->resource['role'])) {
            $payload['role'] = new RoleResource($this->resource['role']);
        }

        return $payload;
    }
}
