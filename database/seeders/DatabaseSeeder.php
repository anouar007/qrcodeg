<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\Permission;
use App\Models\Role;
use App\Models\ScanLog;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionSeeder::class);

        // -------------------------------------------------------------
        // 1. Fine-Grained Permissions
        // -------------------------------------------------------------
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'module' => 'dashboard', 'description' => 'Access system administrative dashboard'],

            // Roles & Permissions
            ['name' => 'Manage Roles', 'slug' => 'manage_roles', 'module' => 'roles', 'description' => 'Create, edit, and assign roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage_permissions', 'module' => 'roles', 'description' => 'Configure system permissions'],

            // Clients
            ['name' => 'View Clients', 'slug' => 'view_clients', 'module' => 'clients', 'description' => 'List and view client profiles'],
            ['name' => 'Create Clients', 'slug' => 'create_clients', 'module' => 'clients', 'description' => 'Register new clients'],
            ['name' => 'Edit Clients', 'slug' => 'edit_clients', 'module' => 'clients', 'description' => 'Update client information and credentials'],
            ['name' => 'Delete Clients', 'slug' => 'delete_clients', 'module' => 'clients', 'description' => 'Delete or deactivate clients'],

            // Users
            ['name' => 'View Users', 'slug' => 'view_users', 'module' => 'users', 'description' => 'List and view admin and staff users'],
            ['name' => 'Create Users', 'slug' => 'create_users', 'module' => 'users', 'description' => 'Create new staff members'],
            ['name' => 'Edit Users', 'slug' => 'edit_users', 'module' => 'users', 'description' => 'Edit staff profiles and credentials'],
            ['name' => 'Delete Users', 'slug' => 'delete_users', 'module' => 'users', 'description' => 'Deactivate or delete users'],

            // Dynamic QR Codes
            ['name' => 'View QR Codes', 'slug' => 'view_qr', 'module' => 'qr', 'description' => 'List and view QR codes'],
            ['name' => 'Create QR Codes', 'slug' => 'create_qr', 'module' => 'qr', 'description' => 'Generate single and dynamic QR codes'],
            ['name' => 'Edit QR Codes', 'slug' => 'edit_qr', 'module' => 'qr', 'description' => 'Modify QR target destinations and templates'],
            ['name' => 'Delete QR Codes', 'slug' => 'delete_qr', 'module' => 'qr', 'description' => 'Delete or archive QR codes'],
            ['name' => 'Assign QR Codes', 'slug' => 'assign_qr', 'module' => 'qr', 'description' => 'Assign QR codes to clients'],
            ['name' => 'Generate QR Documents', 'slug' => 'generate_qr_document', 'module' => 'qr_documents', 'description' => 'Build printable PDF and sheets'],
            ['name' => 'Download QR Codes', 'slug' => 'download_qr', 'module' => 'qr', 'description' => 'Download PNG, SVG, or ZIP bundles'],
            ['name' => 'Print QR Codes', 'slug' => 'print_qr', 'module' => 'qr', 'description' => 'Directly print formatted QR cards'],

            // Digital Cards
            ['name' => 'View Digital Cards', 'slug' => 'view_digital_cards', 'module' => 'digital_cards', 'description' => 'List and view digital business cards'],
            ['name' => 'Create Digital Cards', 'slug' => 'create_digital_cards', 'module' => 'digital_cards', 'description' => 'Design and publish digital business cards'],
            ['name' => 'Edit Digital Cards', 'slug' => 'edit_digital_cards', 'module' => 'digital_cards', 'description' => 'Modify theme config, blocks, and details'],
            ['name' => 'Delete Digital Cards', 'slug' => 'delete_digital_cards', 'module' => 'digital_cards', 'description' => 'Deactivate or delete digital business cards'],

            // NFC Tags
            ['name' => 'View NFC Tags', 'slug' => 'view_nfc_tags', 'module' => 'nfc_tags', 'description' => 'List and view NFC tags'],
            ['name' => 'Create NFC Tags', 'slug' => 'create_nfc_tags', 'module' => 'nfc_tags', 'description' => 'Register and link new NFC tags'],
            ['name' => 'Edit NFC Tags', 'slug' => 'edit_nfc_tags', 'module' => 'nfc_tags', 'description' => 'Modify destination and tag routing'],
            ['name' => 'Delete NFC Tags', 'slug' => 'delete_nfc_tags', 'module' => 'nfc_tags', 'description' => 'Deactivate or remove NFC tags'],

            // Analytics
            ['name' => 'View Analytics', 'slug' => 'view_analytics', 'module' => 'analytics', 'description' => 'Inspect scan metrics and telemetry'],
            ['name' => 'Export Analytics', 'slug' => 'export_analytics', 'module' => 'analytics', 'description' => 'Export CSV and data logs'],

            // Settings & Logs
            ['name' => 'Manage Settings', 'slug' => 'manage_settings', 'module' => 'settings', 'description' => 'Change application branding and global config'],
            ['name' => 'View Activity Logs', 'slug' => 'view_activity_logs', 'module' => 'activity_logs', 'description' => 'Inspect comprehensive audit trails'],
        ];

        $permissionModels = [];
        foreach ($permissions as $p) {
            $permissionModels[$p['slug']] = Permission::updateOrCreate(
                ['slug' => $p['slug']],
                $p
            );
        }

        // -------------------------------------------------------------
        // 2. Default User Roles
        // -------------------------------------------------------------
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full administrative access to all SaaS modules and settings',
                'is_system' => true,
            ]
        );
        $adminRole->permissions()->sync(array_values(array_map(fn ($p) => $p->id, $permissionModels)));

        $staffRole = Role::updateOrCreate(
            ['slug' => 'staff'],
            [
                'name' => 'Staff Member',
                'description' => 'Operational access to clients, dynamic QR codes, digital cards, and analytics',
                'is_system' => true,
            ]
        );
        $staffRole->permissions()->sync([
            $permissionModels['view_dashboard']->id,
            $permissionModels['view_clients']->id,
            $permissionModels['view_qr']->id,
            $permissionModels['create_qr']->id,
            $permissionModels['edit_qr']->id,
            $permissionModels['download_qr']->id,
            $permissionModels['view_digital_cards']->id,
            $permissionModels['create_digital_cards']->id,
            $permissionModels['view_nfc_tags']->id,
            $permissionModels['view_analytics']->id,
        ]);

        $qrManagerRole = Role::updateOrCreate(
            ['slug' => 'qr_manager'],
            [
                'name' => 'QR Manager',
                'description' => 'Specialist for dynamic QR generation, styling, and printable documents',
                'is_system' => false,
            ]
        );
        $qrManagerRole->permissions()->sync([
            $permissionModels['view_dashboard']->id,
            $permissionModels['view_qr']->id,
            $permissionModels['create_qr']->id,
            $permissionModels['edit_qr']->id,
            $permissionModels['generate_qr_document']->id,
            $permissionModels['download_qr']->id,
            $permissionModels['print_qr']->id,
        ]);

        $clientManagerRole = Role::updateOrCreate(
            ['slug' => 'client_manager'],
            [
                'name' => 'Client Manager',
                'description' => 'Specialist for onboarding clients and managing accounts',
                'is_system' => false,
            ]
        );
        $clientManagerRole->permissions()->sync([
            $permissionModels['view_dashboard']->id,
            $permissionModels['view_clients']->id,
            $permissionModels['create_clients']->id,
            $permissionModels['edit_clients']->id,
            $permissionModels['assign_qr']->id,
        ]);

        // -------------------------------------------------------------
        // 3. Initial Admin User
        // -------------------------------------------------------------
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Administrator',
                'first_name' => 'System',
                'last_name' => 'Administrator',
                'username' => 'admin',
                'phone' => '+1 (555) 123-4567',
                'password' => Hash::make('password'),
                'status' => 'active',
                'last_login_at' => now(),
            ]
        );
        $adminUser->roles()->sync([$adminRole->id]);

        // Secondary Staff User for multi-user verification
        $staffUser = User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
                'name' => 'John Staff',
                'first_name' => 'John',
                'last_name' => 'Staff',
                'username' => 'staff',
                'phone' => '+1 (555) 987-6543',
                'password' => Hash::make('password'),
                'status' => 'active',
                'last_login_at' => now()->subDay(),
            ]
        );
        $staffUser->roles()->sync([$staffRole->id, $qrManagerRole->id]);

        // -------------------------------------------------------------
        // 4. Default System Settings
        // -------------------------------------------------------------
        $defaultSettings = [
            ['key' => 'app_name', 'value' => 'VP SaaS Engine', 'type' => 'string', 'group' => 'general'],
            ['key' => 'tagline', 'value' => 'Enterprise Dynamic QR, NFC & Digital Business Card Platform', 'type' => 'string', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'support@vpsaas.io', 'type' => 'string', 'group' => 'general'],
            ['key' => 'primary_color', 'value' => '#4f46e5', 'type' => 'string', 'group' => 'design'],
            ['key' => 'qr_code_prefix', 'value' => 'VP', 'type' => 'string', 'group' => 'qr'],
            ['key' => 'default_qr_size', 'value' => '300', 'type' => 'integer', 'group' => 'qr'],
            ['key' => 'default_paper_size', 'value' => 'a4', 'type' => 'string', 'group' => 'document'],
            ['key' => 'enable_geo_tracking', 'value' => '1', 'type' => 'boolean', 'group' => 'analytics'],
        ];
        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // -------------------------------------------------------------
        // 5. Sample Clients
        // -------------------------------------------------------------
        $hotelClient = Client::updateOrCreate(
            ['email' => 'guestservices@abchotel.com'],
            [
                'name' => 'ABC Hotel & Luxury Resorts',
                'company_name' => 'ABC Hotel & Luxury Resorts',
                'contact_name' => 'Marcus Vance',
                'city' => 'Metropolis',
                'phone' => '+1 (555) 777-8899',
                'project_name' => 'Grand Mirage Hospitality',
                'status' => 'active',
                'notes' => 'Luxury hospitality client utilizing dynamic QR codes, digital cards, and NFC room touch points.',
                'access_code' => 'X7K9-P2M4-81QZ',
                'password' => Hash::make('secret123'),
                'is_password_set' => true,
                'last_login_at' => now()->subHours(2),
            ]
        );

        $fitnessClient = Client::updateOrCreate(
            ['email' => 'info@luminafitness.com'],
            [
                'name' => 'Lumina Fitness Club',
                'company_name' => 'Lumina Fitness Club',
                'contact_name' => 'Sarah Connor',
                'city' => 'Chicago',
                'phone' => '+1 (555) 444-2211',
                'project_name' => 'Health & Wellness 2026',
                'status' => 'active',
                'notes' => 'Multi-location gym with dynamic class schedules and digital pass cards.',
                'access_code' => 'LUMI-FIT7-99XX',
                'password' => null,
                'is_password_set' => false,
            ]
        );

        // -------------------------------------------------------------
        // 6. Sample Dynamic QR Codes ("VP-XXXXXX" format)
        // -------------------------------------------------------------
        $sampleQrs = [
            [
                'code' => 'VP-8F29A1',
                'label' => 'Front Desk Google Review',
                'target_url' => 'https://g.page/r/abchotel/review',
                'template_type' => 'google_review',
                'scans_count' => 184,
                'status' => 'active',
            ],
            [
                'code' => 'VP-7B44K2',
                'label' => 'Instagram & TikTok Social Hub',
                'target_url' => 'https://instagram.com/abchotel_official',
                'template_type' => 'social_media',
                'scans_count' => 312,
                'status' => 'active',
            ],
            [
                'code' => 'VP-3M99X8',
                'label' => 'Terrace Bistro & Wine Menu',
                'target_url' => 'https://abchotel.com/dining-menu',
                'template_type' => 'menu',
                'scans_count' => 450,
                'status' => 'active',
            ],
        ];

        foreach ($sampleQrs as $qrData) {
            $qr = DynamicQrCode::updateOrCreate(
                ['code' => $qrData['code']],
                array_merge($qrData, ['client_id' => $hotelClient->id])
            );

            // Polymorphic scan logs
            for ($i = 0; $i < 5; $i++) {
                ScanLog::create([
                    'trackable_type' => 'DynamicQrCode',
                    'trackable_id' => $qr->id,
                    'ip_address' => '192.168.1.'.rand(10, 250),
                    'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_4 like Mac OS X) AppleWebKit/605.1.15',
                    'scanned_at' => now()->subDays(rand(0, 10))->subHours(rand(1, 12)),
                ]);
            }
        }

        // -------------------------------------------------------------
        // 7. Sample Digital Business Card
        // -------------------------------------------------------------
        $digitalCard = DigitalCard::updateOrCreate(
            ['slug' => 'CD-MARCUSVANCE'],
            [
                'client_id' => $hotelClient->id,
                'user_id' => $adminUser->id,
                'title' => 'Marcus Vance - General Manager',
                'name' => 'Marcus Vance - General Manager',
                'slug' => 'CD-MARCUSVANCE',
                'template_id' => 'modern',
                'template' => 'modern',
                'theme_config' => [
                    'colors' => [
                        'primary' => '#4f46e5',
                        'accent' => '#06b6d4',
                        'background' => '#0f172a',
                        'card_bg' => '#1e293b',
                        'text' => '#ffffff',
                        'subtext' => '#94a3b8',
                    ],
                    'fonts' => [
                        'heading' => 'Plus Jakarta Sans',
                        'body' => 'Inter',
                    ],
                    'shadows' => '0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3)',
                    'borders' => [
                        'radius' => '16px',
                        'width' => '1px',
                        'color' => 'rgba(255, 255, 255, 0.1)',
                    ],
                    'layout' => 'centered-avatar',
                ],
                'content_blocks' => [
                    'profile_img' => 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?auto=format&fit=crop&w=400&q=80',
                    'cover_img' => 'https://images.unsplash.com/photo-1566073771259-6a8506099945?auto=format&fit=crop&w=1200&q=80',
                    'bio' => 'General Manager at ABC Hotel & Luxury Resorts. Dedicated to delivering world-class bespoke guest experiences.',
                    'contact_details' => [
                        ['type' => 'phone', 'label' => 'Direct Phone', 'value' => '+1 (555) 777-8899', 'icon' => 'bi-telephone-fill'],
                        ['type' => 'email', 'label' => 'Direct Email', 'value' => 'marcus.vance@abchotel.com', 'icon' => 'bi-envelope-fill'],
                        ['type' => 'website', 'label' => 'Official Website', 'value' => 'https://abchotel.com', 'icon' => 'bi-globe'],
                        ['type' => 'address', 'label' => 'Headquarters', 'value' => '742 Evergreen Terrace, Metropolis', 'icon' => 'bi-geo-alt-fill'],
                    ],
                    'social_links' => [
                        ['platform' => 'linkedin', 'label' => 'LinkedIn Profile', 'url' => 'https://linkedin.com/in/marcusvance', 'icon' => 'bi-linkedin'],
                        ['platform' => 'instagram', 'label' => 'Instagram', 'url' => 'https://instagram.com/marcus_gm', 'icon' => 'bi-instagram'],
                        ['platform' => 'whatsapp', 'label' => 'WhatsApp VIP Concierge', 'url' => 'https://wa.me/15557778899', 'icon' => 'bi-whatsapp'],
                    ],
                    'custom_links' => [
                        ['label' => 'Book VIP Suite Online', 'url' => 'https://abchotel.com/reservations', 'icon' => 'bi-star-fill'],
                        ['label' => 'Download Restaurant Menu PDF', 'url' => 'https://abchotel.com/menu.pdf', 'icon' => 'bi-file-earmark-pdf-fill'],
                    ],
                    'positions' => [
                        'avatar' => 'center-top',
                        'social_section' => 2,
                        'actions_section' => 3,
                    ],
                ],
                'views_count' => 124,
                'status' => 'active',
                'full_name' => 'Marcus Vance',
                'job_title' => 'General Manager',
                'company' => 'ABC Hotel & Luxury Resorts',
                'bio' => 'General Manager at ABC Hotel & Luxury Resorts. Dedicated to delivering world-class bespoke guest experiences.',
            ]
        );

        // -------------------------------------------------------------
        // 8. Sample NFC Tags
        // -------------------------------------------------------------
        $nfcTag = NfcTag::updateOrCreate(
            ['tag_uid' => 'NFC-8A7F92'],
            [
                'client_id' => $hotelClient->id,
                'created_by' => $adminUser->id,
                'label' => 'Executive Suite NFC Touch Card',
                'name' => 'Executive Suite NFC Touch Card',
                'destination_url' => 'https://abchotel.com/vip-suite-101',
                'target_url' => 'https://abchotel.com/vip-suite-101',
                'target_type' => 'url',
                'scans_count' => 48,
                'tap_count' => 48,
                'status' => 'active',
                'notes' => 'Installed on wooden room card for Deluxe Suite 101 guests.',
                'last_tapped_at' => now()->subMinutes(35),
            ]
        );

        // Polymorphic scan logs for NFC Tag
        for ($i = 0; $i < 4; $i++) {
            ScanLog::create([
                'trackable_type' => 'NfcTag',
                'trackable_id' => $nfcTag->id,
                'ip_address' => '10.0.0.'.rand(2, 100),
                'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_2 like Mac OS X) AppleWebKit/605.1.15',
                'scanned_at' => now()->subDays(rand(0, 5))->subHours(rand(1, 8)),
            ]);
        }
    }
}
