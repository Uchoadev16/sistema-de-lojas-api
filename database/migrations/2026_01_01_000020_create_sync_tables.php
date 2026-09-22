<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sync_queues', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('device_id')->nullable()->constrained('devices')->nullOnDelete();

            $table->string('resource_type', 180);
            $table->uuid('resource_id')->nullable();
            $table->string('operation', 30);
            $table->string('status', 30)->default('pending');
            $table->unsignedBigInteger('version_number')->default(1);
            $table->text('client_sync_id')->nullable();
            $table->timestamp('client_timestamp')->nullable();
            $table->uuid('remote_id')->nullable();

            $table->jsonb('payload')->nullable();
            $table->jsonb('changed_fields')->nullable();

            $table->text('error_message')->nullable();
            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('last_attempt_at')->nullable();
            $table->timestamp('synced_at')->nullable();

            $table->timestamps();

            $table->index(['device_id', 'status', 'created_at']);
            $table->index(['tenant_id', 'resource_type', 'resource_id']);
            $table->index(['client_sync_id']);
        });

        Schema::create('sync_conflicts', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('device_id')->nullable()->constrained('devices')->nullOnDelete();
            $table->foreignUuid('sync_queue_id')->nullable()->constrained('sync_queues')->nullOnDelete();

            $table->string('resource_type', 180);
            $table->uuid('resource_id');
            $table->string('conflict_type', 60);
            $table->string('resolution_strategy', 30)->nullable();
            $table->string('status', 30)->default('pending');

            $table->jsonb('server_version')->nullable();
            $table->jsonb('client_version')->nullable();
            $table->jsonb('merged_version')->nullable();
            $table->jsonb('resolved_by_user_id')->nullable();

            $table->timestamp('resolved_at')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'status', 'resource_type']);
            $table->index(['device_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sync_conflicts');
        Schema::dropIfExists('sync_queues');
    }
};
