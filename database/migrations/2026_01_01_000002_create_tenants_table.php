<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tenants', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('legal_name', 180);
            $table->string('trade_name', 180)->nullable();
            $table->string('slug', 120)->unique();
            $table->string('document', 20)->nullable()->unique();
            $table->string('ie_rg', 30)->nullable();
            $table->string('im', 30)->nullable();
            $table->string('status', 30)->default('trial');
            $table->boolean('is_active')->default(true);
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('website', 255)->nullable();
            $table->text('logo_path')->nullable();
            $table->text('banner_path')->nullable();
            $table->string('primary_color', 12)->nullable();
            $table->string('secondary_color', 12)->nullable();

            $table->string('address_street', 180)->nullable();
            $table->string('address_number', 30)->nullable();
            $table->string('address_complement', 120)->nullable();
            $table->string('address_neighborhood', 100)->nullable();
            $table->string('address_city', 100)->nullable();
            $table->string('address_state', 4)->nullable();
            $table->string('address_postal_code', 14)->nullable();
            $table->decimal('address_latitude', 11, 8)->nullable();
            $table->decimal('address_longitude', 11, 8)->nullable();

            $table->string('timezone', 60)->default('America/Sao_Paulo');
            $table->string('locale', 8)->default('pt_BR');
            $table->string('currency', 6)->default('BRL');
            $table->string('date_format', 16)->default('DD/MM/YYYY');

            $table->text('about')->nullable();
            $table->text('business_hours')->nullable();
            $table->text('cancellation_policy')->nullable();
            $table->text('terms_of_service')->nullable();
            $table->text('privacy_policy')->nullable();

            $table->uuid('saas_plan_id')->nullable();
            $table->jsonb('settings')->nullable();
            $table->jsonb('preferences')->nullable();
            $table->jsonb('metadata')->nullable();

            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('onboarded_at')->nullable();

            $table->unsignedBigInteger('created_by_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('saas_plan_id')->references('id')->on('saas_plans')->nullOnDelete();

            $table->index(['status', 'is_active']);
            $table->index(['saas_plan_id', 'is_active']);
            $table->index('created_by_user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
