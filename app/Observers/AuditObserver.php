<?php

namespace App\Observers;

use App\Services\Tenancy\TenantContext;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AuditObserver
{
    public function created(Model $model): void
    {
        $this->audit($model, 'created');
    }

    public function updated(Model $model): void
    {
        $this->audit($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->audit($model, 'deleted');
    }

    protected function audit(Model $model, string $action): void
    {
        $table = 'audit_events';
        try {
            if (! Schema::hasTable($table)) {
                return;
            }
        } catch (\Throwable) {
            return;
        }

        $previous = method_exists($model, 'getRawOriginal') ? $model->getRawOriginal() : $model->getOriginal();

        try {
            DB::table($table)->insert([
                'id' => Str::uuid(),
                'tenant_id' => app(TenantContext::class)->hasTenant() ? app(TenantContext::class)->id() : null,
                'user_id' => Auth::id(),
                'action' => $action,
                'resource_type' => $model::class,
                'resource_id' => $model->getKey(),
                'previous_values' => $action !== 'created' && $previous ? json_encode($previous, JSON_THROW_ON_ERROR) : null,
                'new_values' => $action !== 'deleted' ? json_encode($model->getAttributes(), JSON_THROW_ON_ERROR) : null,
                'ip_address' => request()?->ip(),
                'user_agent' => request()?->userAgent(),
                'created_at' => now(),
            ]);
        } catch (\Throwable) {
            // never let audit break request
        }
    }
}
