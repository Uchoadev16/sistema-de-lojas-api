<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerContact extends BaseTenantModel
{
    protected $fillable = [
        'name',
        'position',
        'email',
        'phone',
        'whatsapp',
        'is_primary',
        'receives_notifications',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'receives_notifications' => 'boolean',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
