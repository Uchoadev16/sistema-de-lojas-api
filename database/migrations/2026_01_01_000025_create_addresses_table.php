<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('address_type', 30)->default('commercial');
            $table->string('postal_code', 20)->nullable();
            $table->string('street', 200);
            $table->string('number', 30)->nullable();
            $table->string('complement', 100)->nullable();
            $table->string('neighborhood', 100)->nullable();
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('country', 100)->default('Brasil');
            $table->decimal('latitude', 11, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->jsonb('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
