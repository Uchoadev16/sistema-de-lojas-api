<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('permissions', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->foreignUuid('tenant_id')->nullable()->constrained('tenants')->cascadeOnDelete();

            $table->string('name', 120);
            $table->string('slug', 160);
            $table->string('group', 80)->nullable();
            $table->string('module', 80)->nullable();
            $table->text('description')->nullable();
            $table->string('scope', 30)->default('tenant');
            $table->boolean('is_system')->default(false);
            $table->boolean('is_active')->default(true);

            $table->jsonb('metadata')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'slug']);
            $table->index(['tenant_id', 'is_active', 'is_system']);
            $table->index(['module', 'group']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
