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
        Schema::create('qr_codes', function (Blueprint $table) {
            $table->id();
            $table->string('public_code', 32)->unique();
            $table->string('access_code', 64)->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('type', 32)->default('website');
            $table->enum('status', ['active', 'inactive', 'suspended', 'archived'])->default('active')->index();
            $table->string('dynamic_url');
            $table->string('password')->nullable();
            $table->boolean('is_password_set')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('scan_count')->default(0);
            $table->timestamp('last_scanned_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('type');
            $table->index('created_at');
        });

        Schema::create('qr_contents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->unique()->constrained('qr_codes')->cascadeOnDelete();
            $table->string('type', 32)->default('website');
            $table->string('title')->nullable();
            $table->json('content_data')->nullable();
            $table->timestamps();
        });

        Schema::create('qr_designs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->unique()->constrained('qr_codes')->cascadeOnDelete();
            $table->string('foreground_color', 16)->default('#000000');
            $table->string('background_color', 16)->default('#ffffff');
            $table->string('dot_style', 32)->default('square');
            $table->string('corner_style', 32)->default('square');
            $table->string('eye_style', 32)->default('square');
            $table->string('logo_path')->nullable();
            $table->unsignedSmallInteger('logo_size')->default(20);
            $table->unsignedSmallInteger('logo_padding')->default(2);
            $table->string('frame_style', 32)->default('none');
            $table->string('frame_text')->nullable();
            $table->string('frame_color', 16)->default('#000000');
            $table->string('error_correction', 4)->default('M');
            $table->unsignedSmallInteger('qr_size')->default(300);
            $table->timestamps();
        });

        Schema::create('qr_scans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_code_id')->constrained('qr_codes')->cascadeOnDelete();
            $table->timestamp('scanned_at')->index();
            $table->string('ip_hash', 64)->nullable()->index();
            $table->string('country', 100)->nullable()->index();
            $table->string('city', 100)->nullable();
            $table->string('device_type', 32)->nullable()->index();
            $table->string('operating_system', 64)->nullable()->index();
            $table->string('browser', 64)->nullable()->index();
            $table->string('referrer')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_scans');
        Schema::dropIfExists('qr_designs');
        Schema::dropIfExists('qr_contents');
        Schema::dropIfExists('qr_codes');
    }
};
