<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('units', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('customer_id');
            $table->uuid('address_id')->nullable();
            $table->string('name', 150);
            $table->string('code', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->uuid('responsible_contact_id')->nullable()->index();
            $table->text('notes')->nullable();
            $table->string('external_id', 120)->nullable();
            $table->decimal('area_m2', 10, 2)->nullable();
            $table->integer('floors')->default(1);
            $table->jsonb('metadata')->nullable();
            $table->uuid('created_by_user_id')->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreign('address_id')->references('id')->on('addresses')->nullOnDelete();

            $table->unique(['tenant_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('units');
    }
};
