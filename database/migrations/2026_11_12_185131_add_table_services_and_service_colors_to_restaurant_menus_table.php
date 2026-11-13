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
            $table->string('service_bg_color', 20)->default('#ffffff')->after('item_text_color');
            $table->string('service_text_color', 20)->default('#1e293b')->after('service_bg_color');
            $table->boolean('table_service_enabled')->default(true)->after('nav_style');
            $table->text('whatsapp_message')->nullable()->after('whatsapp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurant_menus', function (Blueprint $table) {
            $table->dropColumn([
                'service_bg_color',
                'service_text_color',
                'table_service_enabled',
                'whatsapp_message',
            ]);
        });
    }
};
