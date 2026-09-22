<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use App\Support\Enums\TechnicianAvailability;
use App\Support\Enums\TechnicianStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Technician extends BaseTenantModel
{
    protected $fillable = [
        'name',
        'document',
        'email',
        'phone',
        'mobile',
        'whatsapp',
        'photo_url',
        'specialty',
        'professional_registration',
        'service_region',
        'color',
        'status',
        'availability_status',
        'hire_date',
        'termination_date',
        'salary_cents',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'status' => TechnicianStatus::class,
        'availability_status' => TechnicianAvailability::class,
        'salary_cents' => 'integer',
        'hire_date' => 'date',
        'termination_date' => 'date',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function serviceTeams(): BelongsToMany
    {
        return $this->belongsToMany(ServiceTeam::class, 'service_team_members', 'technician_id', 'service_team_id')
            ->withPivot('joined_at', 'is_lead', 'left_at', 'notes');
    }
}
