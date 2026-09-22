<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notification_logs', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignUuid('device_id')->nullable()->constrained('devices')->nullOnDelete();

            $table->string('type', 80);
            $table->string('notification_type', 80)->nullable();
            $table->string('channel', 30);
            $table->string('recipient', 255)->nullable();

            $table->string('status', 30)->default('pending');
            $table->text('subject')->nullable();
            $table->text('content')->nullable();

            $table->jsonb('payload')->nullable();
            $table->jsonb('provider_response')->nullable();

            $table->string('external_reference_id', 120)->nullable();
            $table->text('error_message')->nullable();

            $table->unsignedInteger('attempts')->default(0);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('delivered_at')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamp('failed_at')->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'user_id', 'created_at']);
            $table->index(['channel', 'status', 'created_at']);
            $table->index('external_reference_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notification_logs');
    }
};
