<?php

namespace Tests\Feature;

use App\Models\DynamicQrCode;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class QrLinkEditorTest extends TestCase
{
    use RefreshDatabase;

    private User $editorUser;

    protected function setUp(): void
    {
        parent::setUp();

        $permission = Permission::create([
            'name' => 'Scan & Edit QR Link',
            'slug' => 'scan_edit_qr_link',
            'module' => 'qr',
            'description' => 'Scan dynamic QR codes and update their destination link only',
        ]);

        $role = Role::create([
            'name' => 'QR Link Editor',
            'slug' => 'qr_link_editor',
            'description' => 'Minimalist access: scan dynamic QR codes and edit only their destination link',
            'is_system' => false,
        ]);
        $role->permissions()->sync([$permission->id]);

        $this->editorUser = User::factory()->create([
            'email' => 'scanner@example.com',
            'password' => Hash::make('scanpass123'),
            'status' => 'active',
        ]);
        $this->editorUser->roles()->sync([$role->id]);
    }

    public function test_qr_link_editor_logs_in_directly_to_minimalist_page_and_is_restricted_from_admin(): void
    {
        $loginResponse = $this->post(route('login.post'), [
            'login' => 'scanner@example.com',
            'password' => 'scanpass123',
        ]);

        $loginResponse->assertRedirect(route('qr-link-editor.index'));
        $this->assertAuthenticatedAs($this->editorUser);

        $this->actingAs($this->editorUser)
            ->get(route('qr-link-editor.index'))
            ->assertStatus(200)
            ->assertSee('Scan QR Code')
            ->assertSee('Logout');

        $this->actingAs($this->editorUser)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('qr-link-editor.index'));

        $this->actingAs($this->editorUser)
            ->get(route('admin.clients.index'))
            ->assertStatus(403);
    }

    public function test_qr_link_editor_can_lookup_and_update_only_target_url(): void
    {
        $qr = DynamicQrCode::create([
            'code' => 'VP-SCAN99',
            'label' => 'Lobby Stand',
            'target_url' => 'https://old-link.example.com',
            'status' => 'active',
        ]);

        // Lookup by raw code
        $this->actingAs($this->editorUser)
            ->getJson(route('qr-link-editor.lookup', ['code' => 'VP-SCAN99']))
            ->assertStatus(200)
            ->assertJson([
                'found' => true,
                'qr' => [
                    'id' => $qr->id,
                    'code' => 'VP-SCAN99',
                    'label' => 'Lobby Stand',
                    'target_url' => 'https://old-link.example.com',
                ],
            ]);

        // Lookup by scanned full URL
        $this->actingAs($this->editorUser)
            ->getJson(route('qr-link-editor.lookup', ['code' => 'https://vanaprod-card.com/q/VP-SCAN99']))
            ->assertStatus(200)
            ->assertJsonPath('qr.code', 'VP-SCAN99');

        // Update only target_url (attempting to alter label/status is ignored)
        $this->actingAs($this->editorUser)
            ->putJson(route('qr-link-editor.update', $qr), [
                'target_url' => 'https://new-link.example.com/menu',
                'label' => 'Hacked Label',
                'status' => 'inactive',
            ])
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'target_url' => 'https://new-link.example.com/menu',
            ]);

        $qr->refresh();
        $this->assertEquals('https://new-link.example.com/menu', $qr->target_url);
        $this->assertEquals('Lobby Stand', $qr->label);
        $this->assertEquals('active', $qr->status);
    }
}
