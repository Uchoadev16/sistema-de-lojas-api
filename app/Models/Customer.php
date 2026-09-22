<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use App\Support\Enums\CustomerStatus;
use App\Support\Enums\CustomerType;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends BaseTenantModel
{
    protected $fillable = [
        'customer_type',
        'name',
        'legal_name',
        'trade_name',
        'tax_id',
        'state_registration',
        'email',
        'phone',
        'mobile',
        'status',
        'is_active',
        'notes',
        'external_id',
        'preferences',
        'metadata',
    ];

    protected $casts = [
        'customer_type' => CustomerType::class,
        'status' => CustomerStatus::class,
        'is_active' => 'boolean',
        'preferences' => 'array',
        'metadata' => 'array',
    ];

    public function contacts(): HasMany
    {
        return $this->hasMany(CustomerContact::class);
    }

    public function addresses(): BelongsToMany
    {
        return $this->belongsToMany(Address::class, 'customer_addresses', 'customer_id', 'address_id')
            ->withPivot(['type', 'is_primary']);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'customer_tags', 'customer_id', 'tag_id');
    }

    public function units(): HasMany
    {
        return $this->hasMany(Unit::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
