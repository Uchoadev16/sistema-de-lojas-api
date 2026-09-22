<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('customer_id')->index();
            $table->uuid('unit_id')->nullable()->index();
            $table->uuid('environment_id')->nullable()->index();
            $table->uuid('category_id')->nullable();
            $table->uuid('brand_id')->nullable();
            $table->uuid('model_id')->nullable();
            $table->string('identifier', 80);
            $table->string('asset_tag', 80)->nullable();
            $table->string('serial_number', 100)->nullable();
            $table->string('equipment_type', 100)->default('air_conditioner');
            $table->string('manufacturer', 100)->nullable();
            $table->string('model_name', 100)->nullable();
            $table->decimal('capacity', 12, 2)->nullable();
            $table->string('capacity_unit', 30)->default('btu');
            $table->string('refrigerant', 50)->nullable();
            $table->string('voltage', 30)->nullable();
            $table->decimal('power_kw', 10, 3)->nullable();
            $table->timestamp('installed_at')->nullable();
            $table->timestamp('warranty_start')->nullable();
            $table->timestamp('warranty_end')->nullable();
            $table->date('purchase_date')->nullable();
            $table->bigInteger('purchase_price_cents')->default(0);
            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->string('qr_code_value', 150)->nullable()->unique();
            $table->string('condition', 30)->nullable();
            $table->timestamp('last_maintenance_at')->nullable();
            $table->timestamp('next_maintenance_at')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->string('external_id', 120)->nullable();
            $table->jsonb('metadata')->nullable();
            $table->uuid('created_by_user_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'identifier']);
            $table->foreign('category_id')->references('id')->on('equipment_categories')->nullOnDelete();
            $table->foreign('brand_id')->references('id')->on('equipment_brands')->nullOnDelete();
            $table->foreign('model_id')->references('id')->on('equipment_models')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
