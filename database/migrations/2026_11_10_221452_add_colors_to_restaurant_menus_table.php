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
            $table->string('bg_color', 20)->default('#f8fafc')->after('theme_color');
            $table->string('item_bg_color', 20)->default('#ffffff')->after('bg_color');
            $table->string('item_text_color', 20)->default('#1e293b')->after('item_bg_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->dropColumn(['bg_color', 'item_bg_color', 'item_text_color']);
        });
    }
};
