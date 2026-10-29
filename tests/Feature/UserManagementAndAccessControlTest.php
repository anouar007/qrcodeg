<?php

namespace Tests\Feature;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Tests\TestCase;

class UserManagementAndAccessControlTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected Role $adminRole;

    protected Role $staffRole;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(PermissionSeeder::class);

        $this->adminRole = Role::where('slug', 'admin')->first();
        $this->staffRole = Role::where('slug', 'staff')->first();

        $this->admin = User::factory()->create([
            'name' => 'System SuperAdmin',
            'email' => 'admin@saas-master.com',
            'username' => 'superadmin',
            'status' => 'active',
        ]);
        $this->admin->roles()->attach($this->adminRole);
    }

    /**
     * 1. Test PermissionSeeder seeds the five required module categories
     */
    public function test_permission_seeder_creates_all_five_module_categories(): void
    {
        // 1. Clients
        $this->assertDatabaseHas('permissions', ['slug' => 'view_clients', 'module' => 'clients']);
        $this->assertDatabaseHas('permissions', ['slug' => 'create_clients', 'module' => 'clients']);
        $this->assertDatabaseHas('permissions', ['slug' => 'edit_clients', 'module' => 'clients']);
        $this->assertDatabaseHas('permissions', ['slug' => 'delete_clients', 'module' => 'clients']);

        // 2. Digital Cards
        $this->assertDatabaseHas('permissions', ['slug' => 'view_digital_cards', 'module' => 'digital_cards']);
        $this->assertDatabaseHas('permissions', ['slug' => 'create_digital_cards', 'module' => 'digital_cards']);
        $this->assertDatabaseHas('permissions', ['slug' => 'edit_digital_cards', 'module' => 'digital_cards']);
        $this->assertDatabaseHas('permissions', ['slug' => 'delete_digital_cards', 'module' => 'digital_cards']);
        $this->assertDatabaseHas('permissions', ['slug' => 'export_digital_cards', 'module' => 'digital_cards']);

        // 3. QR Codes
        $this->assertDatabaseHas('permissions', ['slug' => 'view_qr', 'module' => 'qr']);
        $this->assertDatabaseHas('permissions', ['slug' => 'create_qr', 'module' => 'qr']);
        $this->assertDatabaseHas('permissions', ['slug' => 'edit_qr', 'module' => 'qr']);
        $this->assertDatabaseHas('permissions', ['slug' => 'redirect_manage_qr', 'module' => 'qr']);
        $this->assertDatabaseHas('permissions', ['slug' => 'delete_qr', 'module' => 'qr']);

        // 4. NFC Tags
        $this->assertDatabaseHas('permissions', ['slug' => 'view_nfc_tags', 'module' => 'nfc_tags']);
        $this->assertDatabaseHas('permissions', ['slug' => 'create_nfc_tags', 'module' => 'nfc_tags']);
        $this->assertDatabaseHas('permissions', ['slug' => 'edit_nfc_tags', 'module' => 'nfc_tags']);
        $this->assertDatabaseHas('permissions', ['slug' => 'delete_nfc_tags', 'module' => 'nfc_tags']);

        // 5. Users & Settings
        $this->assertDatabaseHas('permissions', ['slug' => 'view_users', 'module' => 'users_settings']);
        $this->assertDatabaseHas('permissions', ['slug' => 'create_users', 'module' => 'users_settings']);
        $this->assertDatabaseHas('permissions', ['slug' => 'edit_users', 'module' => 'users_settings']);
        $this->assertDatabaseHas('permissions', ['slug' => 'delete_users', 'module' => 'users_settings']);
        $this->assertDatabaseHas('permissions', ['slug' => 'manage_users', 'module' => 'users_settings']);
        $this->assertDatabaseHas('permissions', ['slug' => 'manage_settings', 'module' => 'users_settings']);
        $this->assertDatabaseHas('permissions', ['slug' => 'manage_roles', 'module' => 'users_settings']);
    }

    /**
     * 2. Test user listing with search and status filters
     */
    public function test_user_listing_with_search_and_filters(): void
    {
        $activeUser = User::factory()->create([
            'name' => 'Karim Bennani',
            'email' => 'karim@agency.test',
            'username' => 'kbennani',
            'status' => 'active',
        ]);
        $activeUser->roles()->attach($this->staffRole);

        $suspendedUser = User::factory()->create([
            'name' => 'Sara Alami',
            'email' => 'sara@agency.test',
            'username' => 'salami',
            'status' => 'suspended',
        ]);

        // General list
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.users.index'));
        $response->assertOk();
        $response->assertSee('Karim Bennani');
        $response->assertSee('Sara Alami');
        $response->assertSee('Actif');
        $response->assertSee('Suspendu');

        // Search filter
        $searchResponse = $this->actingAs($this->admin, 'web')->get(route('admin.users.index', ['search' => 'Bennani']));
        $searchResponse->assertOk();
        $searchResponse->assertSee('Karim Bennani');
        $searchResponse->assertDontSee('Sara Alami');

        // Status filter: suspended
        $statusResponse = $this->actingAs($this->admin, 'web')->get(route('admin.users.index', ['status' => 'suspended']));
        $statusResponse->assertOk();
        $statusResponse->assertSee('Sara Alami');
        $statusResponse->assertDontSee('Karim Bennani');
    }

    /**
     * 3. Test create user view and permission matrix rendering
     */
    public function test_user_create_view_renders_permission_matrix(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.users.create'));
        $response->assertOk();
        $response->assertSee('Clients Management');
        $response->assertSee('Digital Cards');
        $response->assertSee('Dynamic QR Codes');
        $response->assertSee('NFC Tags');
        $response->assertSee('Users &amp; Settings', false);
        $response->assertSee('redirect_manage_qr');
        $response->assertSee('export_digital_cards');
    }

    /**
     * 4. Test storing new user with role and granular permissions matrix
     */
    public function test_user_store_creates_user_and_syncs_permissions(): void
    {
        $perm1 = Permission::where('slug', 'view_clients')->first();
        $perm2 = Permission::where('slug', 'redirect_manage_qr')->first();
        $perm3 = Permission::where('slug', 'export_digital_cards')->first();

        $payload = [
            'name' => 'Youssef Mansouri',
            'email' => 'youssef@studio.test',
            'username' => 'ymansouri',
            'phone' => '+212612345678',
            'status' => 'active',
            'password' => 'SecurePassword123!',
            'password_confirmation' => 'SecurePassword123!',
            'roles' => [$this->staffRole->id],
            'permissions' => [$perm1->id, $perm2->id, $perm3->id],
        ];

        $response = $this->actingAs($this->admin, 'web')->post(route('admin.users.store'), $payload);
        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success');

        $created = User::where('email', 'youssef@studio.test')->first();
        $this->assertNotNull($created);
        $this->assertEquals('Youssef Mansouri', $created->name);
        $this->assertEquals('active', $created->status);
        $this->assertTrue($created->roles->contains('id', $this->staffRole->id));
        $this->assertEquals(3, $created->directPermissions()->count());
        $this->assertTrue($created->hasPermission('redirect_manage_qr'));
        $this->assertTrue($created->hasPermission('export_digital_cards'));
    }

    /**
     * 5. Test validation fails with missing required fields or mismatched password
     */
    public function test_user_store_validation_errors(): void
    {
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.users.store'), [
            'name' => '',
            'email' => 'not-an-email',
            'password' => 'short',
            'password_confirmation' => 'mismatch',
            'status' => 'invalid_status',
        ]);

        $response->assertSessionHasErrors(['email', 'password', 'status']);
    }

    /**
     * 6. Test updating user status and permissions
     */
    public function test_user_update_modifies_status_and_permissions(): void
    {
        $targetUser = User::factory()->create([
            'name' => 'Mehdi Chraibi',
            'email' => 'mehdi@agency.test',
            'username' => 'mchraibi',
            'status' => 'active',
        ]);

        $nfcPerm = Permission::where('slug', 'edit_nfc_tags')->first();

        $updateData = [
            'name' => 'Mehdi Chraibi Updated',
            'email' => 'mehdi@agency.test',
            'username' => 'mchraibi',
            'status' => 'suspended',
            'roles' => [$this->staffRole->id],
            'permissions' => [$nfcPerm->id],
        ];

        $response = $this->actingAs($this->admin, 'web')->put(route('admin.users.update', $targetUser), $updateData);
        $response->assertRedirect(route('admin.users.index'));

        $targetUser->refresh();
        $this->assertEquals('Mehdi Chraibi Updated', $targetUser->name);
        $this->assertEquals('suspended', $targetUser->status);
        $this->assertTrue($targetUser->isSuspended());
        $this->assertTrue($targetUser->hasPermission('edit_nfc_tags'));
    }

    /**
     * 7. Test toggle status action
     */
    public function test_toggle_user_status(): void
    {
        $target = User::factory()->create([
            'email' => 'test-toggle@corp.test',
            'status' => 'active',
        ]);

        // Toggle from active to suspended
        $response = $this->actingAs($this->admin, 'web')->post(route('admin.users.toggle-status', $target));
        $response->assertRedirect();
        $target->refresh();
        $this->assertEquals('suspended', $target->status);

        // Toggle back to active
        $response2 = $this->actingAs($this->admin, 'web')->post(route('admin.users.toggle-status', $target));
        $response2->assertRedirect();
        $target->refresh();
        $this->assertEquals('active', $target->status);

        // Self toggle prevention
        $selfResponse = $this->actingAs($this->admin, 'web')->post(route('admin.users.toggle-status', $this->admin));
        $selfResponse->assertSessionHas('error');
    }

    /**
     * 8. Test user deletion & self-delete prevention
     */
    public function test_user_deletion_and_self_delete_prevention(): void
    {
        $target = User::factory()->create(['email' => 'deleteme@corp.test']);

        $response = $this->actingAs($this->admin, 'web')->delete(route('admin.users.destroy', $target));
        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseMissing('users', ['id' => $target->id]);

        // Cannot delete oneself
        $selfDelete = $this->actingAs($this->admin, 'web')->delete(route('admin.users.destroy', $this->admin));
        $selfDelete->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }

    /**
     * 9. Test unauthorized user cannot access user management module (Middleware & Policy check)
     */
    public function test_unauthorized_user_is_forbidden_from_user_management(): void
    {
        $restrictedUser = User::factory()->create([
            'name' => 'Restricted Operator',
            'email' => 'operator@client.test',
            'username' => 'operator',
            'status' => 'active',
        ]);
        // Give only view_clients permission
        $viewClientPerm = Permission::where('slug', 'view_clients')->first();
        $restrictedUser->directPermissions()->attach($viewClientPerm);

        // Accessing clients is permitted
        $clientResponse = $this->actingAs($restrictedUser, 'web')->get(route('admin.clients.index'));
        $clientResponse->assertOk();

        // Accessing users returns 403 Forbidden
        $userResponse = $this->actingAs($restrictedUser, 'web')->get(route('admin.users.index'));
        $userResponse->assertForbidden();

        // Storing user returns 403 Forbidden
        $createResponse = $this->actingAs($restrictedUser, 'web')->post(route('admin.users.store'), [
            'name' => 'Hacker Account',
            'email' => 'hacker@blackhat.test',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'status' => 'active',
        ]);
        $createResponse->assertForbidden();
    }

    /**
     * 10. Test Blade directives @haspermission and @can
     */
    public function test_blade_haspermission_and_can_directives(): void
    {
        $restrictedUser = User::factory()->create([
            'name' => 'Limited User',
            'email' => 'limited@test.com',
            'username' => 'limited',
            'status' => 'active',
        ]);
        $perm = Permission::where('slug', 'export_digital_cards')->first();
        $restrictedUser->directPermissions()->attach($perm);

        $this->actingAs($restrictedUser, 'web');

        // Test @haspermission for granted permission
        $renderedGranted = Blade::render('@haspermission("export_digital_cards") ALLOWED @else DENIED @endhaspermission');
        $this->assertStringContainsString('ALLOWED', $renderedGranted);

        // Test @haspermission for ungranted permission
        $renderedDenied = Blade::render('@haspermission("delete_users") ALLOWED @else DENIED @endhaspermission');
        $this->assertStringContainsString('DENIED', $renderedDenied);

        // Test Gate/Policy @can
        $this->assertTrue(Gate::allows('view_clients') === false);
        $this->assertTrue(Gate::allows('export_digital_cards') === true);
    }
}
