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
        Schema::table('qr_designs', function (Blueprint $table) {
            $table->string('template_image_path')->nullable()->after('qr_size');
            $table->json('custom_template_config')->nullable()->after('template_image_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('qr_designs', function (Blueprint $table) {
            $table->dropColumn(['template_image_path', 'custom_template_config']);
        });
    }
};
