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
        Schema::create('qr_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->enum('paper_size', ['a4', 'a5', 'letter'])->default('a4');
            $table->unsignedTinyInteger('columns')->default(2);
            $table->unsignedTinyInteger('rows')->default(4);
            $table->unsignedSmallInteger('qr_size_mm')->default(45);
            $table->boolean('show_access_code')->default(true);
            $table->boolean('show_public_code')->default(true);
            $table->boolean('show_logo')->default(true);
            $table->string('custom_text')->nullable();
            $table->string('logo_path')->nullable();
            $table->string('status', 32)->default('ready');
            $table->timestamps();
        });

        Schema::create('qr_document_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('qr_document_id')->constrained('qr_documents')->cascadeOnDelete();
            $table->foreignId('qr_code_id')->constrained('qr_codes')->cascadeOnDelete();
            $table->unsignedInteger('position_order')->default(0);
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_document_items');
        Schema::dropIfExists('qr_documents');
    }
};
