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
        Schema::create('restaurant_menu_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('restaurant_menu_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'restaurant_menu_id']);
        });

        Schema::create('digital_card_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('digital_card_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['user_id', 'digital_card_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('digital_card_user');
        Schema::dropIfExists('restaurant_menu_user');
    }
};
