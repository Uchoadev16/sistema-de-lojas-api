<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use App\Support\Enums\UnitStatus;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Unit extends BaseTenantModel
{
    protected $fillable = [
        'address_id',
        'name',
        'code',
        'description',
        'status',
        'is_active',
        'responsible_contact_id',
        'notes',
        'external_id',
        'area_m2',
        'floors',
        'metadata',
    ];

    protected $casts = [
        'status' => UnitStatus::class,
        'is_active' => 'boolean',
        'area_m2' => 'decimal:2',
        'floors' => 'integer',
        'metadata' => 'array',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function environments(): HasMany
    {
        return $this->hasMany(Environment::class);
    }

    public function responsibleContact(): BelongsTo
    {
        return $this->belongsTo(CustomerContact::class, 'responsible_contact_id');
    }
}
