<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('digital_cards', function (Blueprint $table) {
            if (! Schema::hasColumn('digital_cards', 'profile_photo_path')) {
                $table->string('profile_photo_path')->nullable()->after('cover_image');
            }
            if (! Schema::hasColumn('digital_cards', 'cover_photo_path')) {
                $table->string('cover_photo_path')->nullable()->after('profile_photo_path');
            }
        });

        // Sync existing values
        if (Schema::hasColumn('digital_cards', 'profile_image') && Schema::hasColumn('digital_cards', 'profile_photo_path')) {
            DB::statement('UPDATE digital_cards SET profile_photo_path = profile_image WHERE profile_photo_path IS NULL AND profile_image IS NOT NULL');
        }
        if (Schema::hasColumn('digital_cards', 'cover_image') && Schema::hasColumn('digital_cards', 'cover_photo_path')) {
            DB::statement('UPDATE digital_cards SET cover_photo_path = cover_image WHERE cover_photo_path IS NULL AND cover_image IS NOT NULL');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('digital_cards', function (Blueprint $table) {
            if (Schema::hasColumn('digital_cards', 'profile_photo_path')) {
                $table->dropColumn('profile_photo_path');
            }
            if (Schema::hasColumn('digital_cards', 'cover_photo_path')) {
                $table->dropColumn('cover_photo_path');
            }
        });
    }
};
