<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_tenant_id')->constrained('user_tenants')->cascadeOnDelete();
            $table->foreignUuid('permission_id')->constrained('permissions')->cascadeOnDelete();
            $table->string('effect', 15)->default('allow');
            $table->text('reason')->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->foreignUuid('granted_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();

            $table->unique(['user_tenant_id', 'permission_id']);
            $table->index(['user_tenant_id', 'effect']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
