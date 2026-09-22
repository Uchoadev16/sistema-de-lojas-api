<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use App\Support\Enums\PermissionEffect;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends BaseGlobalModel
{
    protected $fillable = [
        'permission_id',
        'effect',
        'valid_from',
        'valid_until',
        'assigned_by_user_id',
        'reason',
    ];

    protected $casts = [
        'effect' => PermissionEffect::class,
        'expires_at' => 'datetime',
    ];

    public function userTenant(): BelongsTo
    {
        return $this->belongsTo(UserTenant::class);
    }

    public function permission(): BelongsTo
    {
        return $this->belongsTo(Permission::class);
    }

    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'granted_by_user_id');
    }
}
