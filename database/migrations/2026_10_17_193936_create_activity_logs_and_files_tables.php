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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->string('action', 64)->index();
            $table->string('resource_type', 64)->nullable()->index();
            $table->unsignedBigInteger('resource_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });

        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');
            $table->string('file_path');
            $table->string('file_type', 32)->default('image');
            $table->unsignedBigInteger('file_size');
            $table->string('mime_type', 64);
            $table->string('uploaded_by_type', 32)->nullable();
            $table->unsignedBigInteger('uploaded_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('file_uploads');
        Schema::dropIfExists('activity_logs');
    }
};
