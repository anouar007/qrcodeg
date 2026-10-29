<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // 1. Clients
            [
                'name' => 'View Clients',
                'slug' => 'view_clients',
                'module' => 'clients',
                'description' => 'View client profiles and lists',
            ],
            [
                'name' => 'Create Clients',
                'slug' => 'create_clients',
                'module' => 'clients',
                'description' => 'Register new clients',
            ],
            [
                'name' => 'Edit Clients',
                'slug' => 'edit_clients',
                'module' => 'clients',
                'description' => 'Update client information and settings',
            ],
            [
                'name' => 'Delete Clients',
                'slug' => 'delete_clients',
                'module' => 'clients',
                'description' => 'Delete or archive clients',
            ],

            // 2. Digital Cards
            [
                'name' => 'View Digital Cards',
                'slug' => 'view_digital_cards',
                'module' => 'digital_cards',
                'description' => 'List and inspect digital business cards',
            ],
            [
                'name' => 'Create Digital Cards',
                'slug' => 'create_digital_cards',
                'module' => 'digital_cards',
                'description' => 'Build and publish new digital business cards',
            ],
            [
                'name' => 'Edit Digital Cards',
                'slug' => 'edit_digital_cards',
                'module' => 'digital_cards',
                'description' => 'Modify themes, content blocks, and details',
            ],
            [
                'name' => 'Delete Digital Cards',
                'slug' => 'delete_digital_cards',
                'module' => 'digital_cards',
                'description' => 'Deactivate or delete digital business cards',
            ],
            [
                'name' => 'Export Digital Cards',
                'slug' => 'export_digital_cards',
                'module' => 'digital_cards',
                'description' => 'Export card files, vCards, and summaries',
            ],

            // 3. QR Codes
            [
                'name' => 'View QR Codes',
                'slug' => 'view_qr',
                'module' => 'qr',
                'description' => 'View dynamic and standard QR codes',
            ],
            [
                'name' => 'Create QR Codes',
                'slug' => 'create_qr',
                'module' => 'qr',
                'description' => 'Generate single and bulk VP QR codes',
            ],
            [
                'name' => 'Edit QR Codes',
                'slug' => 'edit_qr',
                'module' => 'qr',
                'description' => 'Update QR configuration and visual styling',
            ],
            [
                'name' => 'Manage QR Redirection',
                'slug' => 'redirect_manage_qr',
                'module' => 'qr',
                'description' => 'Instantly update scan target destinations',
            ],
            [
                'name' => 'Delete QR Codes',
                'slug' => 'delete_qr',
                'module' => 'qr',
                'description' => 'Delete or archive QR codes',
            ],

            // 4. NFC Tags
            [
                'name' => 'View NFC Tags',
                'slug' => 'view_nfc_tags',
                'module' => 'nfc_tags',
                'description' => 'View physical NFC tags and telemetry',
            ],
            [
                'name' => 'Create NFC Tags',
                'slug' => 'create_nfc_tags',
                'module' => 'nfc_tags',
                'description' => 'Register and configure new hardware chips',
            ],
            [
                'name' => 'Edit NFC Tags',
                'slug' => 'edit_nfc_tags',
                'module' => 'nfc_tags',
                'description' => 'Swap NFC target destinations and settings',
            ],
            [
                'name' => 'Delete NFC Tags',
                'slug' => 'delete_nfc_tags',
                'module' => 'nfc_tags',
                'description' => 'Deactivate or delete physical NFC tags',
            ],

            // 5. Users & Settings (Full Control)
            [
                'name' => 'View Users',
                'slug' => 'view_users',
                'module' => 'users_settings',
                'description' => 'List and view internal staff users',
            ],
            [
                'name' => 'Create Users',
                'slug' => 'create_users',
                'module' => 'users_settings',
                'description' => 'Create new internal team members',
            ],
            [
                'name' => 'Edit Users',
                'slug' => 'edit_users',
                'module' => 'users_settings',
                'description' => 'Edit user accounts, credentials, and roles',
            ],
            [
                'name' => 'Delete Users',
                'slug' => 'delete_users',
                'module' => 'users_settings',
                'description' => 'Delete or suspend user accounts',
            ],
            [
                'name' => 'Full Users Control',
                'slug' => 'manage_users',
                'module' => 'users_settings',
                'description' => 'Full administrative control over user accounts',
            ],
            [
                'name' => 'Manage Settings',
                'slug' => 'manage_settings',
                'module' => 'users_settings',
                'description' => 'System configuration, branding, and parameters',
            ],
            [
                'name' => 'Manage Roles',
                'slug' => 'manage_roles',
                'module' => 'users_settings',
                'description' => 'Create and customize roles and permissions',
            ],
        ];

        $permissionIds = [];
        foreach ($permissions as $permissionData) {
            $permission = Permission::updateOrCreate(
                ['slug' => $permissionData['slug']],
                $permissionData
            );
            $permissionIds[] = $permission->id;
        }

        // Ensure Administrator role exists and has all permissions
        $adminRole = Role::firstOrCreate(
            ['slug' => 'admin'],
            [
                'name' => 'Administrator',
                'description' => 'Full administrative access across all SaaS modules and settings',
                'is_system' => true,
            ]
        );

        $adminRole->permissions()->sync($permissionIds);

        // Ensure Staff role exists with baseline operational permissions
        $staffRole = Role::firstOrCreate(
            ['slug' => 'staff'],
            [
                'name' => 'Collaborateur',
                'description' => 'Standard internal collaborator with operational permissions',
                'is_system' => true,
            ]
        );

        $staffPermissionSlugs = [
            'view_clients', 'create_clients', 'edit_clients',
            'view_digital_cards', 'create_digital_cards', 'edit_digital_cards',
            'view_qr', 'create_qr', 'edit_qr', 'redirect_manage_qr',
            'view_nfc_tags', 'create_nfc_tags', 'edit_nfc_tags',
        ];

        $staffPermissionIds = Permission::whereIn('slug', $staffPermissionSlugs)->pluck('id');
        $staffRole->permissions()->sync($staffPermissionIds);
    }
}
