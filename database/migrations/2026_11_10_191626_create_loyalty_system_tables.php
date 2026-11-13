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
        // 1. Quota & Loyalty permissions on clients table
        Schema::table('clients', function (Blueprint $table) {
            $table->unsignedInteger('loyalty_card_limit')->default(20)->after('status');
            $table->boolean('loyalty_enabled')->default(true)->after('loyalty_card_limit');
        });

        // 2. Loyalty Cards table (QR code, NFC tag, or both)
        Schema::create('loyalty_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('card_code', 32)->unique();
            $table->string('customer_name', 150);
            $table->string('customer_phone', 50)->nullable();
            $table->string('customer_email', 150)->nullable();
            $table->string('card_type', 20)->default('both'); // qr_code, nfc, both
            $table->string('nfc_uid', 100)->nullable()->index();
            $table->unsignedInteger('stamps_count')->default(0);
            $table->unsignedInteger('max_stamps')->default(10);
            $table->string('reward_text', 255)->default('1 Récompense offerte');
            $table->unsignedInteger('total_stamps_earned')->default(0);
            $table->unsignedInteger('rewards_redeemed_count')->default(0);
            $table->integer('points_balance')->default(0);
            $table->string('status', 20)->default('active'); // active, suspended, expired
            $table->text('notes')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        // 3. Loyalty Transactions / Stamp logs table
        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyalty_card_id')->constrained('loyalty_cards')->cascadeOnDelete();
            $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
            $table->string('action', 50); // stamp_added, reward_redeemed, points_adjusted, reset
            $table->integer('stamps_delta')->default(0);
            $table->integer('points_delta')->default(0);
            $table->string('notes', 255)->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('loyalty_cards');

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['loyalty_card_limit', 'loyalty_enabled']);
        });
    }
};
