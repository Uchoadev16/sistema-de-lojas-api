<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use App\Support\Enums\ServiceTeamStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServiceTeam extends BaseTenantModel
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'is_active',
        'leader_technician_id',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'status' => ServiceTeamStatus::class,
        'is_active' => 'boolean',
        'metadata' => 'array',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(Technician::class, 'leader_technician_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function technicians(): BelongsToMany
    {
        return $this->belongsToMany(Technician::class, 'service_team_members', 'service_team_id', 'technician_id')
            ->withPivot('joined_at', 'is_lead', 'left_at', 'notes');
    }
}
