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
        Schema::create('restaurant_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('title', 150);
            $table->string('slug', 150)->unique();
            $table->string('brand_name', 150);
            $table->string('brand_type', 50)->default('restaurant'); // restaurant, fast_food, cafe, lounge, bakery, other
            $table->string('currency', 10)->default('MAD');
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('theme_color', 20)->default('#5736e9');
            $table->json('categories')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('whatsapp', 50)->nullable();
            $table->string('instagram', 100)->nullable();
            $table->string('website', 255)->nullable();
            $table->string('wifi_ssid', 100)->nullable();
            $table->string('wifi_password', 100)->nullable();
            $table->string('address', 255)->nullable();
            $table->unsignedBigInteger('views_count')->default(0);
            $table->string('status', 20)->default('active'); // active, draft, archived
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_menus');
    }
};
