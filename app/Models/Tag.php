<?php

namespace App\Models;

use App\Models\Abstracts\BaseTenantModel;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tag extends BaseTenantModel
{
    protected $fillable = [
        'name',
        'slug',
        'color',
        'description',
        'is_system',
        'metadata',
    ];

    protected $casts = [
        'is_system' => 'boolean',
    ];

    public function customers(): BelongsToMany
    {
        return $this->belongsToMany(Customer::class, 'customer_tags', 'tag_id', 'customer_id');
    }
}
