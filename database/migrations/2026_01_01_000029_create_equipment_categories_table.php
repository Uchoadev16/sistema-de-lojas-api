<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipment_categories', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->nullable()->index();
            $table->string('name', 100);
            $table->string('slug', 120)->nullable();
            $table->string('code', 50)->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_system')->default(false);
            $table->string('color', 30)->default('blue');
            $table->timestamps();

            $table->unique(['tenant_id', 'slug']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipment_categories');
    }
};
