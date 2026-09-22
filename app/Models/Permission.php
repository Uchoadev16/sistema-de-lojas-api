<?php

namespace App\Models;

use App\Models\Abstracts\BaseGlobalModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends BaseGlobalModel
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'code',
        'description',
        'module',
        'action',
        'group',
        'is_system',
    ];

    protected $casts = [
        'is_system' => 'boolean',
        'is_active' => 'boolean',
        'metadata' => 'json',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'role_permissions', 'permission_id', 'role_id')
            ->withTimestamps();
    }
}
