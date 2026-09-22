<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('environments', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('unit_id');
            $table->string('name', 150);
            $table->string('code', 50)->nullable();
            $table->string('floor', 50)->nullable();
            $table->decimal('area', 12, 2)->nullable();
            $table->string('purpose', 100)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('unit_id')->references('id')->on('units')->cascadeOnDelete();

            $table->unique(['tenant_id', 'unit_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('environments');
    }
};
