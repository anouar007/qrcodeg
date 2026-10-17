<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\Permission;
use App\Models\QrCode;
use App\Models\QrContent;
use App\Models\QrDesign;
use App\Models\QrScan;
use App\Models\QrTemplate;
use App\Models\Role;
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
        // 1. Permissions List
        $permissions = [
            // Dashboard
            ['name' => 'View Dashboard', 'slug' => 'view_dashboard', 'module' => 'dashboard', 'description' => 'Access system administrative dashboard'],
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
            // Roles & Permissions
            ['name' => 'Manage Roles', 'slug' => 'manage_roles', 'module' => 'roles', 'description' => 'Create, edit, and assign roles'],
            ['name' => 'Manage Permissions', 'slug' => 'manage_permissions', 'module' => 'roles', 'description' => 'Configure system permissions'],
            // QR Codes
            ['name' => 'View QR Codes', 'slug' => 'view_qr', 'module' => 'qr', 'description' => 'List and view QR codes'],
            ['name' => 'Create QR Codes', 'slug' => 'create_qr', 'module' => 'qr', 'description' => 'Generate single and batch QR codes'],
            ['name' => 'Edit QR Codes', 'slug' => 'edit_qr', 'module' => 'qr', 'description' => 'Modify QR destination, types, designs'],
            ['name' => 'Delete QR Codes', 'slug' => 'delete_qr', 'module' => 'qr', 'description' => 'Delete or archive QR codes'],
            ['name' => 'Assign QR Codes', 'slug' => 'assign_qr', 'module' => 'qr', 'description' => 'Assign QR codes to specific clients'],
            ['name' => 'Generate QR Documents', 'slug' => 'generate_qr_document', 'module' => 'qr_documents', 'description' => 'Build printable PDF and print sheets'],
            ['name' => 'Download QR Codes', 'slug' => 'download_qr', 'module' => 'qr', 'description' => 'Download PNG, SVG, or ZIP bundles'],
            ['name' => 'Print QR Codes', 'slug' => 'print_qr', 'module' => 'qr', 'description' => 'Directly print formatted QR cards'],
            // Analytics
            ['name' => 'View Analytics', 'slug' => 'view_analytics', 'module' => 'analytics', 'description' => 'Inspect scan metrics, geolocation, and device statistics'],
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

        // 2. Roles
        $adminRole = Role::updateOrCreate(
            ['slug' => 'admin'],
            ['name' => 'Administrator', 'description' => 'Full administrative access to all system features', 'is_system' => true]
        );
        $adminRole->permissions()->sync(array_values(array_map(fn ($p) => $p->id, $permissionModels)));

        $staffRole = Role::updateOrCreate(
            ['slug' => 'staff'],
            ['name' => 'Staff Member', 'description' => 'Standard staff operational access', 'is_system' => true]
        );
        $staffRole->permissions()->sync([
            $permissionModels['view_dashboard']->id,
            $permissionModels['view_clients']->id,
            $permissionModels['view_qr']->id,
            $permissionModels['create_qr']->id,
            $permissionModels['edit_qr']->id,
            $permissionModels['download_qr']->id,
            $permissionModels['print_qr']->id,
            $permissionModels['view_analytics']->id,
        ]);

        $qrManagerRole = Role::updateOrCreate(
            ['slug' => 'qr_manager'],
            ['name' => 'QR Manager', 'description' => 'Specialist for QR generation, design, and printable documents', 'is_system' => false]
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
            ['name' => 'Client Manager', 'description' => 'Specialist for client onboarding and QR assignment', 'is_system' => false]
        );
        $clientManagerRole->permissions()->sync([
            $permissionModels['view_dashboard']->id,
            $permissionModels['view_clients']->id,
            $permissionModels['create_clients']->id,
            $permissionModels['edit_clients']->id,
            $permissionModels['assign_qr']->id,
        ]);

        $analyticsManagerRole = Role::updateOrCreate(
            ['slug' => 'analytics_manager'],
            ['name' => 'Analytics Manager', 'description' => 'Specialist for scan traffic and performance reporting', 'is_system' => false]
        );
        $analyticsManagerRole->permissions()->sync([
            $permissionModels['view_dashboard']->id,
            $permissionModels['view_analytics']->id,
            $permissionModels['export_analytics']->id,
        ]);

        // 3. Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'first_name' => 'System',
                'last_name' => 'Admin',
                'username' => 'admin',
                'phone' => '+1 (555) 123-4567',
                'password' => Hash::make('password'),
                'status' => 'active',
                'last_login_at' => now(),
            ]
        );
        $adminUser->roles()->sync([$adminRole->id]);

        // 4. Staff User
        $staffUser = User::updateOrCreate(
            ['email' => 'staff@example.com'],
            [
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

        // 5. Settings
        $defaultSettings = [
            ['key' => 'app_name', 'value' => 'QR Studio', 'type' => 'string', 'group' => 'general'],
            ['key' => 'tagline', 'value' => 'Modern Dynamic QR Code Management Platform', 'type' => 'string', 'group' => 'general'],
            ['key' => 'contact_email', 'value' => 'support@qrstudio.io', 'type' => 'string', 'group' => 'general'],
            ['key' => 'primary_color', 'value' => '#4f46e5', 'type' => 'string', 'group' => 'design'],
            ['key' => 'default_qr_size', 'value' => '300', 'type' => 'integer', 'group' => 'qr'],
            ['key' => 'default_paper_size', 'value' => 'a4', 'type' => 'string', 'group' => 'document'],
            ['key' => 'enable_geo_tracking', 'value' => '1', 'type' => 'boolean', 'group' => 'analytics'],
            ['key' => 'enable_client_registration', 'value' => '0', 'type' => 'boolean', 'group' => 'auth'],
        ];
        foreach ($defaultSettings as $setting) {
            Setting::updateOrCreate(['key' => $setting['key']], $setting);
        }

        // 6. Templates
        $templates = [
            [
                'name' => 'Modern Restaurant Menu',
                'type' => 'custom_landing',
                'description' => 'Digital menu landing page with sections for food, drinks, daily specials, and reservation links.',
                'default_colors' => ['primary' => '#e11d48', 'background' => '#fff1f2', 'text' => '#1f2937'],
                'layout_config' => ['banner' => true, 'columns' => 2, 'header_style' => 'centered'],
                'content_preset' => [
                    'heading' => 'The Grand Bistro & Terrace',
                    'paragraph' => 'Experience artisan farm-to-table cuisine prepared with local seasonal ingredients.',
                    'menu_categories' => [
                        ['title' => 'Starters', 'items' => ['Truffle Burrata', 'Crispy Calamari']],
                        ['title' => 'Main Courses', 'items' => ['Wagyu Ribeye Steak', 'Wild Mushroom Risotto']],
                        ['title' => 'Signature Cocktails', 'items' => ['Smoked Bourbon Old Fashioned', 'Spiced Passionfruit Mojito']],
                    ],
                ],
            ],
            [
                'name' => 'Corporate Business Card (vCard)',
                'type' => 'contact',
                'description' => 'Instant downloadable digital business card with direct phone, email, WhatsApp, and social profiles.',
                'default_colors' => ['primary' => '#2563eb', 'background' => '#f8fafc', 'text' => '#0f172a'],
                'layout_config' => ['card_style' => 'glassmorphic', 'avatar_size' => 'large'],
                'content_preset' => [
                    'name' => 'Alexander Wright',
                    'job_title' => 'Chief Executive Officer',
                    'company' => 'Acrobat Technologies Inc.',
                    'email' => 'alexander@acrobattree.io',
                    'phone' => '+1 (555) 234-5678',
                    'website' => 'https://acrobattree.io',
                    'address' => '500 Innovation Parkway, Suite 400, San Francisco, CA',
                ],
            ],
            [
                'name' => 'Luxury Hotel Concierge & Room Guide',
                'type' => 'custom_landing',
                'description' => 'In-room QR code for WiFi login, room service orders, spa appointments, and reception WhatsApp.',
                'default_colors' => ['primary' => '#0f766e', 'background' => '#f0fdfa', 'text' => '#134e4a'],
                'layout_config' => ['show_wifi' => true, 'show_quick_call' => true],
                'content_preset' => [
                    'heading' => 'Welcome to The Grand Mirage Hotel',
                    'paragraph' => 'Scan to access guest services, high-speed WiFi, 24/7 room dining, and concierge reservations.',
                    'wifi_ssid' => 'GrandMirage_Guest',
                    'wifi_encryption' => 'WPA',
                    'whatsapp' => '+15550198273',
                ],
            ],
            [
                'name' => 'Product Showcase & Manual',
                'type' => 'product',
                'description' => 'Detailed product presentation with pricing, specs sheet, warranty registration, and video tutorial.',
                'default_colors' => ['primary' => '#7c3aed', 'background' => '#faf5ff', 'text' => '#1e1b4b'],
                'layout_config' => ['media_type' => 'gallery', 'show_price' => true],
                'content_preset' => [
                    'product_name' => 'AeroGlide Pro Wireless Earbuds',
                    'description' => 'Active noise cancelling studio-grade audio with 36-hour battery life and fast USB-C charge.',
                    'price' => '$149.00',
                    'button_url' => 'https://example.com/shop/aeroglide',
                ],
            ],
            [
                'name' => 'WiFi Fast-Connect',
                'type' => 'wifi',
                'description' => 'Allow guests to instantly connect to your WiFi network without typing cumbersome passwords.',
                'default_colors' => ['primary' => '#0284c7', 'background' => '#f0f9ff', 'text' => '#0c4a6e'],
                'layout_config' => ['auto_prompt' => true],
                'content_preset' => [
                    'ssid' => 'Guest_Lounge_5G',
                    'encryption' => 'WPA',
                    'password' => 'EnjoyYourStay2026',
                    'hidden' => false,
                ],
            ],
        ];

        foreach ($templates as $tmpl) {
            QrTemplate::updateOrCreate(['name' => $tmpl['name']], $tmpl);
        }

        // 7. Sample Client (ABC HOTEL) as outlined in requirement Section 4
        $hotelClient = Client::updateOrCreate(
            ['email' => 'guestservices@abchotel.com'],
            [
                'company_name' => 'ABC Hotel & Resorts',
                'contact_name' => 'Marcus Vance',
                'phone' => '+1 (555) 777-8899',
                'address' => '742 Evergreen Terrace',
                'city' => 'Metropolis',
                'country' => 'United States',
                'status' => 'active',
                'notes' => 'Luxury 5-star hotel client with 5 operational QR codes (Rooms, Restaurant, Reception).',
                'access_code' => 'X7K9-P2M4-81QZ', // Specific sample access code from requirement prompt
                'password' => Hash::make('secret123'),
                'is_password_set' => true,
                'last_login_at' => now()->subHours(3),
            ]
        );

        // Another client who has NOT set password yet (for first-time flow testing)
        Client::updateOrCreate(
            ['email' => 'info@luminafitness.com'],
            [
                'company_name' => 'Lumina Fitness Club',
                'contact_name' => 'Sarah Connor',
                'phone' => '+1 (555) 444-2211',
                'address' => '120 Broadway Blvd',
                'city' => 'Chicago',
                'country' => 'United States',
                'status' => 'active',
                'notes' => 'Fitness center onboarding - pending first time access code login.',
                'access_code' => 'LUMI-FIT7-99XX',
                'password' => null,
                'is_password_set' => false,
            ]
        );

        // 8. Sample Dynamic QR Codes for ABC Hotel
        $baseUrl = config('app.url', 'http://localhost:8000');

        $sampleQrs = [
            [
                'public_code' => 'QR-X7K9P2M4', // Sample public code from requirement prompt
                'access_code' => '7K29-LP82-AA11',
                'name' => 'Reception & Concierge Welcome',
                'description' => 'Main lobby desk QR code for instant guest assistance and registration.',
                'type' => 'business',
                'status' => 'active',
                'scan_count' => 142,
                'content' => [
                    'business_name' => 'ABC Hotel & Resorts - Front Desk',
                    'description' => 'Welcome to ABC Hotel! Our front desk is at your service 24 hours a day, 7 days a week.',
                    'phone' => '+1 (555) 777-8899',
                    'email' => 'concierge@abchotel.com',
                    'website' => 'https://abchotel.com',
                    'address' => '742 Evergreen Terrace, Metropolis',
                    'opening_hours' => 'Open 24/7',
                    'whatsapp' => '+15557778899',
                ],
                'design' => [
                    'foreground_color' => '#1e3a8a',
                    'background_color' => '#ffffff',
                    'dot_style' => 'rounded',
                    'corner_style' => 'rounded',
                    'frame_style' => 'badge',
                    'frame_text' => 'SCAN FOR DESK',
                    'frame_color' => '#1e3a8a',
                ],
            ],
            [
                'public_code' => 'QR-K4M91P82',
                'access_code' => '8M42-QX19-BB22',
                'name' => 'The Terrace Restaurant & Bar',
                'description' => 'Dining table QR for daily menu, wine list, and online ordering.',
                'type' => 'website',
                'status' => 'active',
                'scan_count' => 389,
                'content' => [
                    'url' => 'https://abchotel.com/menu',
                    'open_new_tab' => true,
                ],
                'design' => [
                    'foreground_color' => '#b45309',
                    'background_color' => '#fffbeb',
                    'dot_style' => 'square',
                    'corner_style' => 'square',
                    'frame_style' => 'bottom-bar',
                    'frame_text' => 'VIEW MENU',
                    'frame_color' => '#b45309',
                ],
            ],
            [
                'public_code' => 'QR-R101DELX',
                'access_code' => 'R101-7K2A-CC33',
                'name' => 'Room 101 - Deluxe Suite',
                'description' => 'In-room guide for Deluxe Suite 101.',
                'type' => 'custom_landing',
                'status' => 'active',
                'scan_count' => 64,
                'content' => [
                    'heading' => 'Suite 101 - Guest Experience',
                    'paragraph' => 'Welcome to your Deluxe Suite! Control climate, browse in-room dining, or order housekeeping.',
                    'blocks' => [
                        ['type' => 'heading', 'text' => 'WiFi Network'],
                        ['type' => 'paragraph', 'text' => 'Connect to "ABCHotel_Guests" using passcode "DeluxeStay2026"'],
                        ['type' => 'button', 'text' => 'Order Room Dining', 'url' => 'https://abchotel.com/dining/order', 'color' => '#1e3a8a'],
                        ['type' => 'whatsapp', 'text' => 'Chat with Concierge', 'phone' => '+15557778899'],
                    ],
                ],
                'design' => [
                    'foreground_color' => '#0f172a',
                    'background_color' => '#ffffff',
                    'dot_style' => 'dots',
                    'corner_style' => 'rounded',
                    'frame_style' => 'none',
                ],
            ],
            [
                'public_code' => 'QR-WIFI5GHZ',
                'access_code' => 'WIFI-9988-DD44',
                'name' => 'Guest High-Speed WiFi',
                'description' => 'Instant WiFi setup for guests in conference center and lounge.',
                'type' => 'wifi',
                'status' => 'active',
                'scan_count' => 512,
                'content' => [
                    'ssid' => 'ABCHotel_Guests_5G',
                    'encryption' => 'WPA',
                    'password' => 'ComfortAndLuxury2026',
                    'hidden' => false,
                ],
                'design' => [
                    'foreground_color' => '#0284c7',
                    'background_color' => '#ffffff',
                    'dot_style' => 'rounded',
                    'corner_style' => 'extra-rounded',
                    'frame_style' => 'badge',
                    'frame_text' => 'CONNECT WIFI',
                    'frame_color' => '#0284c7',
                ],
            ],
            [
                'public_code' => 'QR-INACTIVE',
                'access_code' => 'INAC-1122-EE55',
                'name' => 'Seasonal Pool Bar (Winter Paused)',
                'description' => 'Currently paused until Summer season reopens.',
                'type' => 'website',
                'status' => 'inactive',
                'scan_count' => 19,
                'content' => [
                    'url' => 'https://abchotel.com/pool',
                ],
                'design' => [
                    'foreground_color' => '#475569',
                    'background_color' => '#f8fafc',
                    'dot_style' => 'square',
                ],
            ],
        ];

        foreach ($sampleQrs as $data) {
            $qr = QrCode::updateOrCreate(
                ['public_code' => $data['public_code']],
                [
                    'access_code' => $data['access_code'],
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'client_id' => $hotelClient->id,
                    'type' => $data['type'],
                    'status' => $data['status'],
                    'dynamic_url' => "{$baseUrl}/q/{$data['public_code']}",
                    'created_by' => $adminUser->id,
                    'scan_count' => $data['scan_count'],
                    'last_scanned_at' => now()->subMinutes(rand(5, 720)),
                ]
            );

            QrContent::updateOrCreate(
                ['qr_code_id' => $qr->id],
                [
                    'type' => $data['type'],
                    'title' => $data['name'],
                    'content_data' => $data['content'],
                ]
            );

            QrDesign::updateOrCreate(
                ['qr_code_id' => $qr->id],
                array_merge([
                    'foreground_color' => '#000000',
                    'background_color' => '#ffffff',
                    'dot_style' => 'square',
                    'corner_style' => 'square',
                    'eye_style' => 'square',
                    'frame_style' => 'none',
                    'qr_size' => 300,
                ], $data['design'])
            );

            // Generate realistic scan records over the last 14 days
            $devices = ['mobile', 'mobile', 'mobile', 'tablet', 'desktop'];
            $oss = ['iOS', 'iOS', 'Android', 'Android', 'macOS', 'Windows'];
            $browsers = ['Mobile Safari', 'Chrome Mobile', 'Chrome', 'Safari', 'Firefox'];
            $cities = ['New York', 'London', 'Paris', 'Tokyo', 'San Francisco', 'Toronto', 'Berlin'];
            $countries = ['United States', 'United Kingdom', 'France', 'Japan', 'Canada', 'Germany'];

            for ($s = 0; $s < 15; $s++) {
                $daysAgo = rand(0, 14);
                QrScan::create([
                    'qr_code_id' => $qr->id,
                    'scanned_at' => now()->subDays($daysAgo)->subHours(rand(1, 23))->subMinutes(rand(1, 59)),
                    'ip_hash' => hash('sha256', 'ip_'.rand(1, 100)),
                    'country' => $countries[array_rand($countries)],
                    'city' => $cities[array_rand($cities)],
                    'device_type' => $devices[array_rand($devices)],
                    'operating_system' => $oss[array_rand($oss)],
                    'browser' => $browsers[array_rand($browsers)],
                    'referrer' => 'https://qrcodeg.test',
                    'user_agent' => 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0 like Mac OS X) AppleWebKit/605.1.15',
                ]);
            }
        }
    }
}
