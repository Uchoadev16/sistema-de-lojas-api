<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customer_addresses', function (Blueprint $table): void {
            $table->uuid('customer_id');
            $table->uuid('address_id');
            $table->string('type', 30)->default('service');
            $table->boolean('is_primary')->default(false);
            $table->primary(['customer_id', 'address_id']);

            $table->foreign('customer_id')->references('id')->on('customers')->cascadeOnDelete();
            $table->foreign('address_id')->references('id')->on('addresses')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customer_addresses');
    }
};
