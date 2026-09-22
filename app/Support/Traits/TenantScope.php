<?php

namespace App\Support\Traits;

use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class TenantScope implements Scope
{
    public function apply(Builder $builder, Model $model): void
    {
        /** @var HasTenant $model */
        $tenantColumn = $model->getTenantColumn();

        if (app(TenantContext::class)->hasTenant()) {
            $builder->where($model->getTable().'.'.$tenantColumn, app(TenantContext::class)->id());
        }
    }

    public function extend(Builder $builder): void
    {
        $builder->macro('withoutTenant', function (Builder $builder): Builder {
            return $builder->withoutGlobalScope($this);
        });

        $builder->macro('forTenant', function (Builder $builder, string $tenantId): Builder {
            /** @var Model $model */
            $model = $builder->getModel();
            $column = method_exists($model, 'getTenantColumn')
                ? $model->getTenantColumn()
                : 'tenant_id';

            return $builder->withoutGlobalScope($this)->where($model->getTable().'.'.$column, $tenantId);
        });
    }
}
