<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_events', function (Blueprint $table): void {
            $table->uuid('id')->primary();

            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->nullOnDelete();
            $table->foreignUuid('user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->string('action', 80);
            $table->string('resource_type', 180);
            $table->uuid('resource_id')->nullable();
            $table->string('event_type', 120)->nullable();
            $table->string('event_category', 80)->nullable();
            $table->string('result', 30)->default('success');

            $table->jsonb('previous_values')->nullable();
            $table->jsonb('new_values')->nullable();
            $table->jsonb('changes')->nullable();
            $table->jsonb('context')->nullable();

            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('session_id', 120)->nullable();
            $table->text('request_id')->nullable();
            $table->string('host', 255)->nullable();
            $table->string('url_path', 255)->nullable();
            $table->string('http_method', 12)->nullable();
            $table->unsignedInteger('http_status_code')->nullable();

            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('city', 120)->nullable();
            $table->string('region', 80)->nullable();
            $table->string('country', 4)->nullable();

            $table->timestamps();

            $table->index(['tenant_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            $table->index(['resource_type', 'resource_id']);
            $table->index(['action', 'created_at']);
            $table->index('created_at');
            $table->index(['event_category', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_events');
    }
};
