<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('technicians', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('tenant_id')->index();
            $table->uuid('user_id')->nullable();
            $table->string('name', 150);
            $table->string('document', 30)->nullable();
            $table->string('email', 150)->nullable();
            $table->string('phone', 30)->nullable();
            $table->string('mobile', 30)->nullable();
            $table->string('whatsapp', 30)->nullable();
            $table->string('photo_url', 500)->nullable();
            $table->string('specialty', 100)->nullable();
            $table->string('professional_registration', 100)->nullable();
            $table->string('service_region', 150)->nullable();
            $table->string('color', 30)->default('blue');
            $table->string('status', 30)->default('active');
            $table->string('availability_status', 30)->default('available');
            $table->date('hire_date')->nullable();
            $table->date('termination_date')->nullable();
            $table->bigInteger('salary_cents')->default(0);
            $table->text('notes')->nullable();
            $table->jsonb('metadata')->nullable();
            $table->uuid('created_by_user_id')->nullable()->index();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('technicians');
    }
};
