<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('invites', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('invited_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('role_id')->nullable()->constrained('roles')->nullOnDelete();
            $table->foreignUuid('accepted_by_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('email', 150);
            $table->string('name', 140)->nullable();
            $table->string('document', 20)->nullable();
            $table->string('phone', 30)->nullable();
            $table->text('message')->nullable();

            $table->string('status', 30)->default('pending');
            $table->string('token', 120)->unique();
            $table->timestamp('expires_at')->nullable();

            $table->timestamp('sent_at')->nullable();
            $table->timestamp('viewed_at')->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('declined_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedInteger('reminder_count')->default(0);
            $table->timestamp('last_reminder_at')->nullable();

            $table->jsonb('metadata')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status', 'email']);
            $table->index('token');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invites');
    }
};
