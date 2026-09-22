<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_models', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->nullable()->index();
            $table->uuid('brand_id');
            $table->uuid('category_id')->nullable();
            $table->string('name', 150);
            $table->string('slug', 180)->nullable();
            $table->text('description')->nullable();
            $table->string('image_url', 500)->nullable();
            $table->jsonb('technical_specifications')->nullable();
            $table->timestamps();

            $table->foreign('brand_id')->references('id')->on('equipment_brands')->cascadeOnDelete();
            $table->foreign('category_id')->references('id')->on('equipment_categories')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_models');
    }
};
