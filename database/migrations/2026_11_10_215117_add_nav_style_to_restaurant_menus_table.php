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
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->string('nav_style', 30)->default('vertical_scroll')->after('categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->dropColumn('nav_style');
        });
    }
};
