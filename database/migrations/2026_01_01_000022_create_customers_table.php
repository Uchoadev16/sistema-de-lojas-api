<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('customer_type', 30)->default('company');
            $table->string('name', 150);
            $table->string('legal_name', 200)->nullable();
            $table->string('trade_name', 150)->nullable();
            $table->string('tax_id', 30)->nullable()->index();
            $table->string('state_registration', 50)->nullable();
            $table->string('email', 150)->nullable()->index();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->string('external_id', 120)->nullable();
            $table->uuid('created_by_user_id')->nullable()->index();
            $table->jsonb('preferences')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'tax_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
