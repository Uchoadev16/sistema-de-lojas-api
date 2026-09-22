<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('service_team_members', function (Blueprint $table): void {
            $table->uuid('service_team_id');
            $table->uuid('technician_id');
            $table->date('joined_at')->nullable();
            $table->boolean('is_lead')->default(false);
            $table->date('left_at')->nullable();
            $table->string('notes', 255)->nullable();

            $table->primary(['service_team_id', 'technician_id']);
            $table->foreign('service_team_id')->references('id')->on('service_teams')->cascadeOnDelete();
            $table->foreign('technician_id')->references('id')->on('technicians')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('service_team_members');
    }
};
