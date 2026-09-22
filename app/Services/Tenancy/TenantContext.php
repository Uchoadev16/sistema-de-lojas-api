<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;

class TenantContext
{
    protected ?Tenant $tenant = null;

    public function set(Tenant $tenant): self
    {
        $this->tenant = $tenant;

        return $this;
    }

    public function hasTenant(): bool
    {
        return $this->tenant instanceof Tenant;
    }

    public function tenant(): Tenant
    {
        if (! $this->hasTenant()) {
            throw new \RuntimeException('TenantContext has no tenant set.');
        }

        return $this->tenant;
    }

    public function id(): string
    {
        return $this->tenant()->getKey();
    }

    public function clear(): void
    {
        $this->tenant = null;
    }
}
