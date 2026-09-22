<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_plans', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('name', 120);
            $table->string('slug', 80)->unique();
            $table->text('description')->nullable();
            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->string('billing_cycle', 30)->default('monthly');
            $table->decimal('price_monthly', 12, 2)->default(0);
            $table->decimal('price_annual', 12, 2)->default(0);
            $table->decimal('price_biennial', 12, 2)->nullable();
            $table->unsignedInteger('trial_days')->default(14);
            $table->unsignedInteger('max_users')->nullable();
            $table->unsignedInteger('max_tenants_per_user')->nullable();
            $table->unsignedInteger('max_customers')->nullable();
            $table->unsignedInteger('max_equipment')->nullable();
            $table->unsignedInteger('max_technicians')->nullable();
            $table->unsignedInteger('storage_mb')->nullable();
            $table->boolean('support_pmoc')->default(false);
            $table->boolean('support_qrcode')->default(true);
            $table->boolean('support_mobile')->default(true);
            $table->boolean('support_billing')->default(false);
            $table->boolean('support_inventory')->default(false);
            $table->boolean('support_reports')->default(true);
            $table->boolean('support_portal_cliente')->default(false);
            $table->boolean('support_api')->default(true);
            $table->jsonb('features')->nullable();
            $table->jsonb('limits')->nullable();
            $table->text('stripe_price_monthly_id')->nullable();
            $table->text('stripe_price_annual_id')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_plans');
    }
};
