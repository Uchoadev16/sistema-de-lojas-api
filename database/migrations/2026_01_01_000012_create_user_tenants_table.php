<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_tenants', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('role_id')->nullable()->constrained('roles')->nullOnDelete();

            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->boolean('is_owner')->default(false);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('invited_at')->nullable();
            $table->string('invite_token', 80)->nullable()->unique();
            $table->timestamp('invite_expires_at')->nullable();
            $table->string('external_id', 120)->nullable();

            $table->unsignedInteger('max_budget_amount_cents')->nullable();
            $table->boolean('can_approve_budget')->default(false);
            $table->string('approval_limit_scope', 60)->nullable();

            $table->jsonb('preferences')->nullable();
            $table->jsonb('metadata')->nullable();

            $table->foreignUuid('created_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['user_id', 'tenant_id']);
            $table->index(['tenant_id', 'status', 'is_active']);
            $table->index(['user_id', 'status', 'is_active']);
            $table->index(['role_id']);
            $table->index('invite_token');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_tenants');
    }
};
