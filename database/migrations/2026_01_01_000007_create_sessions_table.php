<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sessions', function (Blueprint $table): void {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index()->constrained('users')->nullOnDelete();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();

            $table->uuid('tenant_id')->nullable();
            $table->string('device_platform', 30)->nullable();
            $table->string('device_name', 120)->nullable();
            $table->string('device_model', 120)->nullable();
            $table->string('device_os_version', 80)->nullable();
            $table->string('app_version', 40)->nullable();
            $table->string('push_token', 255)->nullable();
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamp('expires_at')->nullable();

            $table->index('tenant_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sessions');
    }
};
