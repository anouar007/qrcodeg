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
        Schema::table('dynamic_qr_codes', function (Blueprint $table) {
            $table->string('template_type', 64)->default('google_review')->change();
            $table->string('template_image')->nullable()->after('template_type');
            $table->json('design_config')->nullable()->after('template_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dynamic_qr_codes', function (Blueprint $table) {
            $table->dropColumn(['template_image', 'design_config']);
        });
    }
};
