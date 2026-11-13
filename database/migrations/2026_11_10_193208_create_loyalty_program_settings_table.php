<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('loyalty_program_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->unique()->constrained('clients')->cascadeOnDelete();
            $table->boolean('points_enabled')->default(true);
            $table->string('calculation_mode', 30)->default('spend_amount'); // spend_amount, services, both, custom
            $table->string('currency', 10)->default('DH');
            $table->decimal('spend_amount_ratio', 10, 2)->default(10.00); // e.g. 10 DH
            $table->unsignedInteger('points_per_ratio')->default(1);       // e.g. = 1 point
            $table->decimal('min_spend_amount', 10, 2)->default(0.00);     // min purchase to earn
            $table->unsignedInteger('redemption_points')->default(100);    // e.g. 100 points
            $table->decimal('redemption_value', 10, 2)->default(10.00);   // = 10 DH discount
            $table->json('services_catalog')->nullable();                 // predefined services with points
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_program_settings');
    }
};
