<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_teams', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->string('name', 100);
            $table->string('code', 50)->nullable();
            $table->text('description')->nullable();
            $table->string('status', 30)->default('active');
            $table->boolean('is_active')->default(true);
            $table->uuid('leader_technician_id')->nullable();
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->uuid('created_by_user_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['tenant_id', 'code']);
            $table->foreign('leader_technician_id')->references('id')->on('technicians')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_teams');
    }
};
