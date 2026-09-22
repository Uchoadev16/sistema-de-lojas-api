<?php

namespace App\Support\Traits;

use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;

trait HasTenant
{
    public static function bootHasTenant(): void
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function (Model $model): void {
            /** @var HasTenant $model */
            $column = $model->getTenantColumn();

            if (empty($model->{$column}) && app(TenantContext::class)->hasTenant()) {
                $model->{$column} = app(TenantContext::class)->id();
            }

            if (empty($model->{$column})) {
                throw new RuntimeException(
                    sprintf('Model [%s] requires a tenant context or explicit tenant_id.', $model::class)
                );
            }
        });
    }

    public function getTenantColumn(): string
    {
        return defined(static::class.'::TENANT_COLUMN')
            ? static::TENANT_COLUMN
            : 'tenant_id';
    }
}
