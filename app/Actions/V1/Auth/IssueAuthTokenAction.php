<?php

namespace App\Actions\V1\Auth;

use App\Models\User;

class IssueAuthTokenAction
{
    public function execute(User $user, array $options = []): array
    {
        $name = $options['device_name'] ?? $options['token_name'] ?? 'api-token';
        $abilities = $options['abilities'] ?? ['*'];
        $expiresAt = $options['expires_at'] ?? now()->addHours(12);

        $plainTextToken = '';
        $token = $user->createToken(
            name: $name,
            abilities: $abilities,
            expiresAt: $expiresAt,
        );

        $tokenModel = $token->accessToken;
        try {
            $tokenModel->ip_address = $options['ip_address'] ?? null;
            $tokenModel->user_agent = isset($options['user_agent']) ? substr($options['user_agent'], 0, 500) : null;
            $tokenModel->device_name = isset($options['device_name']) ? substr($options['device_name'], 0, 120) : null;
            $tokenModel->saveQuietly();
        } catch (\Throwable) {
        }

        $now = now();
        $expiresIn = $expiresAt ? $now->diffInSeconds($expiresAt, false) : null;

        return [
            'token' => $tokenModel,
            'plain_text_token' => $token->plainTextToken,
            'token_name' => $name,
            'abilities' => $abilities,
            'expires_in' => $expiresIn,
        ];
    }
}
