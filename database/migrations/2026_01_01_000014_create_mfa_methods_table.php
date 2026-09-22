<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mfa_methods', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->cascadeOnDelete();

            $table->string('method_type', 30)->default('totp');
            $table->string('label', 80)->nullable();
            $table->text('secret')->nullable();
            $table->text('phone_number')->nullable();
            $table->text('email_address')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_enabled')->default(true);

            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('verified_at')->nullable();

            $table->jsonb('recovery_codes')->nullable();
            $table->jsonb('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'method_type', 'is_enabled']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mfa_methods');
    }
};
