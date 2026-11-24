<?php

namespace Tests\Feature;

use App\Models\DigitalCard;
use App\Models\DynamicQrCode;
use App\Models\NfcTag;
use App\Models\Permission;
use App\Models\RestaurantMenu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MenuCardScannerTest extends TestCase
{
    use RefreshDatabase;

    private User $scannerUser;

    protected function setUp(): void
    {
        parent::setUp();

        $permission = Permission::create([
            'name' => 'Scan & Edit Menu / Digital Card',
            'slug' => 'scan_edit_menu_card',
            'module' => 'digital_cards',
            'description' => 'Scan QR codes or NFC tags to edit the linked menu or digital card',
        ]);

        $role = Role::create([
            'name' => 'Menu & Card Scanner',
            'slug' => 'menu_card_scanner',
            'description' => 'Minimalist access: scan QR codes or NFC tags to edit the linked menu or digital card',
            'is_system' => false,
        ]);
        $role->permissions()->sync([$permission->id]);

        $this->scannerUser = User::factory()->create([
            'email' => 'menuscanner@example.com',
            'password' => Hash::make('scanmenu123'),
            'status' => 'active',
        ]);
        $this->scannerUser->roles()->sync([$role->id]);
    }

    public function test_menu_card_scanner_logs_in_to_minimalist_scanner_and_cannot_access_admin(): void
    {
        $loginResponse = $this->post(route('login.post'), [
            'login' => 'menuscanner@example.com',
            'password' => 'scanmenu123',
        ]);

        $loginResponse->assertRedirect(route('menu-card-scanner.index'));

        $this->actingAs($this->scannerUser)
            ->get(route('menu-card-scanner.index'))
            ->assertStatus(200)
            ->assertSee('Scan QR Code')
            ->assertSee('Scan NFC')
            ->assertSee('Logout');

        $this->actingAs($this->scannerUser)
            ->get(route('admin.dashboard'))
            ->assertRedirect(route('menu-card-scanner.index'));

        $this->actingAs($this->scannerUser)
            ->get(route('admin.clients.index'))
            ->assertStatus(403);
    }

    public function test_menu_card_scanner_can_resolve_qr_and_nfc_and_edit_only_scanned_items(): void
    {
        $scannedMenu = RestaurantMenu::create([
            'title' => 'Scanned Menu',
            'slug' => 'scanned-menu',
            'brand_name' => 'Cafe Scan',
            'brand_type' => 'cafe',
            'currency' => 'MAD',
            'status' => 'active',
            'categories' => [],
        ]);

        $unscannedMenu = RestaurantMenu::create([
            'title' => 'Other Menu',
            'slug' => 'other-menu',
            'brand_name' => 'Other Brand',
            'brand_type' => 'cafe',
            'currency' => 'MAD',
            'status' => 'active',
            'categories' => [],
        ]);

        $scannedCard = DigitalCard::create([
            'name' => 'Scanned Card',
            'full_name' => 'Scanned Person',
            'slug' => 'scanned-person',
            'status' => 'active',
        ]);

        $unscannedCard = DigitalCard::create([
            'name' => 'Other Card',
            'full_name' => 'Other Person',
            'slug' => 'other-person',
            'status' => 'active',
        ]);

        $qrToMenu = DynamicQrCode::create([
            'code' => 'VP-MENU01',
            'label' => 'Table 1',
            'target_url' => route('menu.public.show', $scannedMenu->slug),
            'status' => 'active',
        ]);

        $nfcToCard = NfcTag::create([
            'tag_uid' => 'NFC-CARD-01',
            'digital_card_id' => $scannedCard->id,
            'status' => 'active',
        ]);

        // Unscanned items are 403
        $this->actingAs($this->scannerUser)->get(route('admin.menus.edit', $scannedMenu))->assertStatus(403);
        $this->actingAs($this->scannerUser)->get(route('admin.digital-cards.edit', $scannedCard))->assertStatus(403);

        // Lookup via Dynamic QR -> Menu
        $this->actingAs($this->scannerUser)
            ->getJson(route('menu-card-scanner.lookup', ['payload' => route('qr.redirect', $qrToMenu->code)]))
            ->assertStatus(200)
            ->assertJson([
                'found' => true,
                'type' => 'menu',
                'edit_url' => route('admin.menus.edit', $scannedMenu),
            ]);

        // Lookup via NFC UID -> Digital Card
        $this->actingAs($this->scannerUser)
            ->getJson(route('menu-card-scanner.lookup', ['payload' => 'NFC-CARD-01']))
            ->assertStatus(200)
            ->assertJson([
                'found' => true,
                'type' => 'digital_card',
                'edit_url' => route('admin.digital-cards.edit', $scannedCard),
            ]);

        // Now scanned items can be edited, unscanned remain 403
        $this->actingAs($this->scannerUser)->get(route('admin.menus.edit', $scannedMenu))->assertStatus(200);
        $this->actingAs($this->scannerUser)->get(route('admin.digital-cards.edit', $scannedCard))->assertStatus(200);
        $this->actingAs($this->scannerUser)->get(route('admin.menus.edit', $unscannedMenu))->assertStatus(403);
        $this->actingAs($this->scannerUser)->get(route('admin.digital-cards.edit', $unscannedCard))->assertStatus(403);
    }
}
