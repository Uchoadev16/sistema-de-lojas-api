<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('saas_subscriptions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->constrained('tenants')->cascadeOnDelete();
            $table->foreignUuid('saas_plan_id')->nullable()->constrained('saas_plans')->nullOnDelete();

            $table->string('status', 30)->default('trialing');
            $table->boolean('is_active')->default(true);
            $table->string('stripe_customer_id', 80)->nullable();
            $table->string('stripe_subscription_id', 80)->nullable();
            $table->string('payment_method', 60)->nullable();
            $table->string('billing_cycle', 30)->default('monthly');
            $table->decimal('amount', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->string('discount_code', 60)->nullable();
            $table->text('coupon_data')->nullable();

            $table->unsignedInteger('trial_days_used')->default(0);
            $table->unsignedInteger('grace_days_remaining')->default(0);

            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('current_period_start')->nullable();
            $table->timestamp('current_period_end')->nullable();
            $table->timestamp('cancel_at_period_end')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('paused_at')->nullable();
            $table->timestamp('resumes_at')->nullable();

            $table->jsonb('metadata')->nullable();
            $table->jsonb('plan_snapshot')->nullable();
            $table->jsonb('billing_details')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->index(['tenant_id', 'status', 'is_active']);
            $table->index(['stripe_customer_id']);
            $table->index(['stripe_subscription_id']);
            $table->index(['current_period_end']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('saas_subscriptions');
    }
};
