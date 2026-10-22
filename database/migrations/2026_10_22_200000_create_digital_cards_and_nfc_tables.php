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
        // 1. Add project_name to clients table
        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'project_name')) {
                $table->string('project_name')->nullable()->after('company_name');
            }
        });

        // 2. Add card_template & target_redirect_url to qr_codes table
        Schema::table('qr_codes', function (Blueprint $table) {
            if (! Schema::hasColumn('qr_codes', 'card_template')) {
                $table->string('card_template', 32)->default('google_review')->after('type');
            }
            if (! Schema::hasColumn('qr_codes', 'target_redirect_url')) {
                $table->text('target_redirect_url')->nullable()->after('dynamic_url');
            }
        });

        // 3. Carte Visite Digitale (Digital Business Card)
        Schema::create('digital_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('slug', 64)->unique();
            $table->string('name');
            $table->string('template', 32)->default('modern');
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('full_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('company')->nullable();
            $table->text('bio')->nullable();
            $table->string('badge_text')->nullable();
            $table->json('links')->nullable();
            $table->json('layout_settings')->nullable();
            $table->json('style_settings')->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active')->index();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('template');
            $table->index('created_at');
        });

        // 4. NFC Tags
        Schema::create('nfc_tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('tag_uid', 64)->unique();
            $table->string('name');
            $table->enum('target_type', ['url', 'digital_card', 'qr_code'])->default('url');
            $table->text('target_url')->nullable();
            $table->foreignId('digital_card_id')->nullable()->constrained('digital_cards')->nullOnDelete();
            $table->foreignId('qr_code_id')->nullable()->constrained('qr_codes')->nullOnDelete();
            $table->unsignedBigInteger('tap_count')->default(0);
            $table->timestamp('last_tapped_at')->nullable();
            $table->enum('status', ['active', 'inactive', 'locked'])->default('active')->index();
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // 5. NFC Taps (Log of every NFC tap event)
        Schema::create('nfc_taps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('nfc_tag_id')->constrained('nfc_tags')->cascadeOnDelete();
            $table->timestamp('tapped_at')->useCurrent();
            $table->string('ip_hash', 64)->nullable();
            $table->string('device_type', 32)->nullable();
            $table->string('operating_system', 64)->nullable();
            $table->string('browser', 64)->nullable();
            $table->string('referrer')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->index(['nfc_tag_id', 'tapped_at']);
        });

        // 6. Direct User Permissions (permission_user)
        if (! Schema::hasTable('permission_user')) {
            Schema::create('permission_user', function (Blueprint $table) {
                $table->foreignId('permission_id')->constrained('permissions')->cascadeOnDelete();
                $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
                $table->primary(['permission_id', 'user_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permission_user');
        Schema::dropIfExists('nfc_taps');
        Schema::dropIfExists('nfc_tags');
        Schema::dropIfExists('digital_cards');

        Schema::table('qr_codes', function (Blueprint $table) {
            if (Schema::hasColumn('qr_codes', 'target_redirect_url')) {
                $table->dropColumn('target_redirect_url');
            }
            if (Schema::hasColumn('qr_codes', 'card_template')) {
                $table->dropColumn('card_template');
            }
        });

        Schema::table('clients', function (Blueprint $table) {
            if (Schema::hasColumn('clients', 'project_name')) {
                $table->dropColumn('project_name');
            }
        });
    }
};
