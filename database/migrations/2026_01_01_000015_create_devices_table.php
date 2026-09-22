<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('device_name', 120)->nullable();
            $table->string('device_model', 120)->nullable();
            $table->string('device_manufacturer', 120)->nullable();
            $table->string('device_type', 60)->nullable();
            $table->string('platform', 30)->nullable();
            $table->string('os_version', 80)->nullable();
            $table->string('app_version', 40)->nullable();
            $table->string('build_number', 40)->nullable();
            $table->string('device_unique_id', 160)->nullable();
            $table->string('push_token', 255)->nullable();

            $table->string('imei', 40)->nullable();
            $table->string('serial_number', 80)->nullable();
            $table->string('mac_address', 40)->nullable();

            $table->string('status', 30)->default('active');
            $table->boolean('is_trusted')->default(false);
            $table->boolean('push_enabled')->default(true);
            $table->boolean('notifications_enabled')->default(true);
            $table->boolean('location_enabled')->default(false);
            $table->boolean('offline_mode_enabled')->default(true);

            $table->unsignedBigInteger('storage_total_bytes')->nullable();
            $table->unsignedBigInteger('storage_used_bytes')->nullable();
            $table->unsignedBigInteger('storage_app_bytes')->nullable();

            $table->decimal('last_known_latitude', 11, 8)->nullable();
            $table->decimal('last_known_longitude', 11, 8)->nullable();

            $table->timestamp('last_seen_at')->nullable();
            $table->timestamp('registered_at')->nullable();
            $table->timestamp('activated_at')->nullable();
            $table->timestamp('last_sync_at')->nullable();
            $table->timestamp('last_sync_successful_at')->nullable();
            $table->unsignedInteger('pending_sync_count')->default(0);

            $table->text('public_key')->nullable();
            $table->text('public_key_fingerprint')->nullable();

            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignUuid('technician_id')->nullable()->constrained('users')->nullOnDelete();
            $table->jsonb('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['device_unique_id']);
            $table->index(['tenant_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
