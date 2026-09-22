<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Address extends BaseGlobalModel
{
    protected $fillable = [
        'address_type',
        'postal_code',
        'street',
        'number',
        'complement',
        'neighborhood',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_addresses', 'address_id', 'customer_id')
            ->withPivot(['type', 'is_primary']);
    }
}
