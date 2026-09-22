<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name', 140);
            $table->string('email', 150)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('remember_token', 100)->nullable();

            $table->string('document', 20)->nullable()->unique();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->date('birth_date')->nullable();
            $table->string('gender', 20)->nullable();
            $table->text('avatar_path')->nullable();

            $table->string('status', 30)->default('pending');
            $table->boolean('is_active')->default(true);

            $table->string('timezone', 60)->nullable();
            $table->string('locale', 8)->nullable();
            $table->boolean('receive_email_notifications')->default(true);
            $table->boolean('receive_sms_notifications')->default(false);
            $table->boolean('receive_whatsapp_notifications')->default(false);
            $table->boolean('receive_push_notifications')->default(true);

            $table->boolean('is_platform_admin')->default(false);
            $table->boolean('mfa_enabled')->default(false);

            $table->string('current_mfa_challenge_id', 80)->nullable();
            $table->timestamp('last_login_at')->nullable();
            $table->string('last_login_ip', 45)->nullable();
            $table->string('last_login_user_agent', 500)->nullable();
            $table->unsignedInteger('failed_login_attempts')->default(0);
            $table->timestamp('locked_until')->nullable();

            $table->jsonb('preferences')->nullable();
            $table->jsonb('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_active']);
            $table->index('is_platform_admin');
            $table->index('last_login_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
