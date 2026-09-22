<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invite extends BaseGlobalModel
{
    protected $fillable = [
        'email',
        'role_id',
        'status',
        'is_active',
        'invited_at',
        'accepted_at',
        'expires_at',
        'token',
        'message',
        'invite_type',
        'max_redemptions',
        'redemptions_count',
        'locale',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'sent_at' => 'datetime',
        'viewed_at' => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'last_reminder_at' => 'datetime',
        'metadata' => 'json',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by_user_id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function acceptedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'accepted_by_user_id');
    }
}
