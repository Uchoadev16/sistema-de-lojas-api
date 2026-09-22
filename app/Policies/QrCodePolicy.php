<?php

namespace App\Policies;

use App\Models\QrCode;
use App\Models\User;

class QrCodePolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->is_platform_admin) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->hasPermission('qr_codes.view');
    }

    public function view(User $user, QrCode $qrCode): bool
    {
        return $user->hasPermission('qr_codes.view');
    }

    public function create(User $user): bool
    {
        return $user->hasPermission('qr_codes.create');
    }

    public function update(User $user, QrCode $qrCode): bool
    {
        return $user->hasPermission('qr_codes.update');
    }

    public function delete(User $user, QrCode $qrCode): bool
    {
        return $user->hasPermission('qr_codes.delete');
    }

    public function restore(User $user, QrCode $qrCode): bool
    {
        return $user->hasPermission('qr_codes.restore');
    }

    public function generate(User $user): bool
    {
        return $user->hasPermission('qr_codes.create');
    }

    public function link(User $user): bool
    {
        return $user->hasPermission('qr_codes.update');
    }

    public function unlink(User $user): bool
    {
        return $user->hasPermission('qr_codes.update');
    }
}
