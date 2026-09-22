<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('qr_codes', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('code', 120)->unique();
            $table->string('entity_type', 50)->default('equipment');
            $table->uuid('entity_id')->nullable()->index();
            $table->timestamp('linked_at')->nullable();
            $table->uuid('generated_by_user_id')->nullable()->index();
            $table->string('status', 30)->default('unlinked');
            $table->boolean('printed')->default(false);
            $table->boolean('is_active')->default(true);
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'entity_type', 'entity_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('qr_codes');
    }
};
