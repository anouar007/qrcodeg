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
        // 1. Ensure `users` table has standard `name` column
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
        });

        // Backfill name from first_name and last_name if present
        if (Schema::hasColumn('users', 'first_name') && Schema::hasColumn('users', 'last_name')) {
            DB::statement("UPDATE users SET name = TRIM(CONCAT(COALESCE(first_name, ''), ' ', COALESCE(last_name, ''))) WHERE name IS NULL OR name = ''");
        }

        // 2. Ensure `clients` table has `name`, `city`, `phone`, `project_name`, `status`, `notes`
        Schema::table('clients', function (Blueprint $table) {
            if (! Schema::hasColumn('clients', 'name')) {
                $table->string('name')->nullable()->after('id');
            }
            if (! Schema::hasColumn('clients', 'project_name')) {
                $table->string('project_name')->nullable()->after('name');
            }
        });

        if (Schema::hasColumn('clients', 'contact_name') || Schema::hasColumn('clients', 'company_name')) {
            DB::statement("UPDATE clients SET name = COALESCE(NULLIF(contact_name, ''), NULLIF(company_name, ''), 'Client') WHERE name IS NULL OR name = ''");
        }

        // 3. Create `client_histories` table
        if (! Schema::hasTable('client_histories')) {
            Schema::create('client_histories', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained('clients')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('action_type', 64)->index();
                $table->text('description');
                $table->json('metadata')->nullable();
                $table->timestamp('created_at')->useCurrent()->index();
            });
        }

        // 4. Update / create `digital_cards` schema columns
        if (Schema::hasTable('digital_cards')) {
            Schema::table('digital_cards', function (Blueprint $table) {
                if (! Schema::hasColumn('digital_cards', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('client_id')->constrained('users')->nullOnDelete();
                }
                if (! Schema::hasColumn('digital_cards', 'title')) {
                    $table->string('title')->nullable()->after('user_id');
                }
                if (! Schema::hasColumn('digital_cards', 'template_id')) {
                    $table->string('template_id', 64)->nullable()->after('slug');
                }
                if (! Schema::hasColumn('digital_cards', 'theme_config')) {
                    $table->json('theme_config')->nullable()->after('template_id');
                }
                if (! Schema::hasColumn('digital_cards', 'content_blocks')) {
                    $table->json('content_blocks')->nullable()->after('theme_config');
                }
            });

            // Backfill title from name & user_id from created_by if present
            if (Schema::hasColumn('digital_cards', 'name') && Schema::hasColumn('digital_cards', 'title')) {
                DB::statement("UPDATE digital_cards SET title = name WHERE title IS NULL OR title = ''");
            }
            if (Schema::hasColumn('digital_cards', 'created_by') && Schema::hasColumn('digital_cards', 'user_id')) {
                DB::statement('UPDATE digital_cards SET user_id = created_by WHERE user_id IS NULL');
            }
        } else {
            Schema::create('digital_cards', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('title');
                $table->string('slug', 64)->unique()->index();
                $table->string('template_id', 64)->nullable()->default('modern');
                $table->json('theme_config')->nullable();
                $table->json('content_blocks')->nullable();
                $table->unsignedBigInteger('views_count')->default(0);
                $table->enum('status', ['active', 'inactive'])->default('active')->index();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 5. Update / create `nfc_tags` schema columns
        if (Schema::hasTable('nfc_tags')) {
            Schema::table('nfc_tags', function (Blueprint $table) {
                if (! Schema::hasColumn('nfc_tags', 'label')) {
                    $table->string('label')->nullable()->after('tag_uid');
                }
                if (! Schema::hasColumn('nfc_tags', 'destination_url')) {
                    $table->text('destination_url')->nullable()->after('label');
                }
                if (! Schema::hasColumn('nfc_tags', 'scans_count')) {
                    $table->unsignedBigInteger('scans_count')->default(0)->after('destination_url');
                }
            });

            // Make tag_uid nullable if it was strictly required previously
            try {
                DB::statement('ALTER TABLE nfc_tags MODIFY tag_uid VARCHAR(64) NULL');
            } catch (Throwable $e) {
                // SQLite or unsupported alter
            }

            // Sync backfilled values
            if (Schema::hasColumn('nfc_tags', 'name') && Schema::hasColumn('nfc_tags', 'label')) {
                DB::statement("UPDATE nfc_tags SET label = name WHERE label IS NULL OR label = ''");
            }
            if (Schema::hasColumn('nfc_tags', 'target_url') && Schema::hasColumn('nfc_tags', 'destination_url')) {
                DB::statement("UPDATE nfc_tags SET destination_url = target_url WHERE destination_url IS NULL OR destination_url = ''");
            }
            if (Schema::hasColumn('nfc_tags', 'tap_count') && Schema::hasColumn('nfc_tags', 'scans_count')) {
                DB::statement('UPDATE nfc_tags SET scans_count = tap_count WHERE scans_count = 0');
            }
        } else {
            Schema::create('nfc_tags', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
                $table->string('tag_uid', 64)->nullable()->unique();
                $table->string('label');
                $table->text('destination_url')->nullable();
                $table->unsignedBigInteger('scans_count')->default(0);
                $table->enum('status', ['active', 'inactive', 'locked'])->default('active')->index();
                $table->softDeletes();
                $table->timestamps();
            });
        }

        // 6. Create `dynamic_qr_codes` table
        if (! Schema::hasTable('dynamic_qr_codes')) {
            Schema::create('dynamic_qr_codes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->nullable()->constrained('clients')->cascadeOnDelete();
                $table->string('code', 32)->unique()->index(); // format "VP-XXXXXX"
                $table->string('label');
                $table->text('target_url');
                $table->enum('template_type', ['google_review', 'social_media', 'menu'])->default('google_review');
                $table->unsignedBigInteger('scans_count')->default(0);
                $table->enum('status', ['active', 'inactive', 'archived'])->default('active')->index();
                $table->timestamps();
            });
        }

        // 7. Create `scan_logs` table (polymorphic tracking for NfcTag and DynamicQrCode)
        if (! Schema::hasTable('scan_logs')) {
            Schema::create('scan_logs', function (Blueprint $table) {
                $table->id();
                $table->string('trackable_type', 128); // NfcTag or DynamicQrCode
                $table->unsignedBigInteger('trackable_id');
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->timestamp('scanned_at')->useCurrent()->index();

                $table->index(['trackable_type', 'trackable_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scan_logs');
        Schema::dropIfExists('dynamic_qr_codes');
        Schema::dropIfExists('client_histories');

        if (Schema::hasTable('digital_cards')) {
            Schema::table('digital_cards', function (Blueprint $table) {
                if (Schema::hasColumn('digital_cards', 'content_blocks')) {
                    $table->dropColumn('content_blocks');
                }
                if (Schema::hasColumn('digital_cards', 'theme_config')) {
                    $table->dropColumn('theme_config');
                }
                if (Schema::hasColumn('digital_cards', 'template_id')) {
                    $table->dropColumn('template_id');
                }
                if (Schema::hasColumn('digital_cards', 'title')) {
                    $table->dropColumn('title');
                }
                if (Schema::hasColumn('digital_cards', 'user_id')) {
                    $table->dropForeign(['user_id']);
                    $table->dropColumn('user_id');
                }
            });
        }

        if (Schema::hasTable('nfc_tags')) {
            Schema::table('nfc_tags', function (Blueprint $table) {
                if (Schema::hasColumn('nfc_tags', 'scans_count')) {
                    $table->dropColumn('scans_count');
                }
                if (Schema::hasColumn('nfc_tags', 'destination_url')) {
                    $table->dropColumn('destination_url');
                }
                if (Schema::hasColumn('nfc_tags', 'label')) {
                    $table->dropColumn('label');
                }
            });
        }

        if (Schema::hasTable('clients')) {
            Schema::table('clients', function (Blueprint $table) {
                if (Schema::hasColumn('clients', 'name')) {
                    $table->dropColumn('name');
                }
            });
        }

        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'name')) {
                    $table->dropColumn('name');
                }
            });
        }
    }
};
