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
        Schema::table('digital_cards', function (Blueprint $table) {
            if (! Schema::hasColumn('digital_cards', 'menu_config')) {
                $table->json('menu_config')->nullable()->after('content_blocks');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_cards', function (Blueprint $table) {
            if (Schema::hasColumn('digital_cards', 'menu_config')) {
                $table->dropColumn('menu_config');
            }
        });
    }
};
