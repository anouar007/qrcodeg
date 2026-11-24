<?php

namespace Tests\Feature;

use App\Models\DigitalCard;
use App\Models\RestaurantMenu;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserAssignedAssetsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_assign_specific_menus_and_digital_cards_to_a_user_and_scope_their_access(): void
    {
        $adminRole = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'is_system' => true,
        ]);

        $admin = User::factory()->create(['status' => 'active']);
        $admin->roles()->sync([$adminRole->id]);

        $allowedMenu = RestaurantMenu::create([
            'title' => 'Allowed Menu',
            'slug' => 'allowed-menu',
            'brand_name' => 'Allowed Brand',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'status' => 'active',
            'categories' => [],
        ]);

        $forbiddenMenu = RestaurantMenu::create([
            'title' => 'Forbidden Menu',
            'slug' => 'forbidden-menu',
            'brand_name' => 'Forbidden Brand',
            'brand_type' => 'cafe',
            'currency' => 'MAD',
            'status' => 'active',
            'categories' => [],
        ]);

        $allowedCard = DigitalCard::create([
            'name' => 'Allowed Card',
            'full_name' => 'Allowed Person',
            'slug' => 'allowed-card',
            'status' => 'active',
        ]);

        $forbiddenCard = DigitalCard::create([
            'name' => 'Forbidden Card',
            'full_name' => 'Forbidden Person',
            'slug' => 'forbidden-card',
            'status' => 'active',
        ]);

        // 1. Admin creates user with specific assigned menus & digital cards
        $createResponse = $this->actingAs($admin, 'web')->post(route('admin.users.store'), [
            'name' => 'Menu Card Editor',
            'username' => 'editor_assets',
            'email' => 'editor_assets@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'status' => 'active',
            'assigned_menus' => [$allowedMenu->id],
            'assigned_digital_cards' => [$allowedCard->id],
        ]);

        $createResponse->assertRedirect(route('admin.users.index'));
        $editor = User::where('email', 'editor_assets@example.com')->firstOrFail();

        $this->assertTrue($editor->assignedMenus()->where('restaurant_menus.id', $allowedMenu->id)->exists());
        $this->assertTrue($editor->assignedDigitalCards()->where('digital_cards.id', $allowedCard->id)->exists());

        // 2. User logs in and lands on /my-workspace
        $loginResponse = $this->post(route('login.post'), [
            'login' => 'editor_assets@example.com',
            'password' => 'Password123!',
        ]);
        $loginResponse->assertRedirect(route('assigned-workspace.index'));

        $this->actingAs($editor, 'web')
            ->get(route('assigned-workspace.index'))
            ->assertStatus(200)
            ->assertSee('Allowed Menu')
            ->assertSee('Allowed Person')
            ->assertDontSee('Forbidden Menu')
            ->assertDontSee('Forbidden Person');

        // 3. User can edit & update assigned menu, but NOT forbidden menu or create
        $this->actingAs($editor, 'web')->get(route('admin.menus.edit', $allowedMenu))->assertStatus(200);
        $this->actingAs($editor, 'web')->put(route('admin.menus.update', $allowedMenu), [
            'title' => 'Allowed Menu Updated',
            'brand_name' => 'Allowed Brand',
            'brand_type' => 'fast_food',
            'currency' => 'MAD',
            'status' => 'active',
        ])->assertRedirect();

        $this->assertDatabaseHas('restaurant_menus', [
            'id' => $allowedMenu->id,
            'title' => 'Allowed Menu Updated',
        ]);

        $this->actingAs($editor, 'web')->get(route('admin.menus.edit', $forbiddenMenu))->assertStatus(403);
        $this->actingAs($editor, 'web')->get(route('admin.menus.create'))->assertStatus(403);

        // 4. User can edit & update assigned digital card, but NOT forbidden card or create
        $this->actingAs($editor, 'web')->get(route('admin.digital-cards.edit', $allowedCard))->assertStatus(200);
        $this->actingAs($editor, 'web')->get(route('admin.digital-cards.menu.edit', $allowedCard))->assertStatus(200);
        $this->actingAs($editor, 'web')->get(route('admin.digital-cards.edit', $forbiddenCard))->assertStatus(403);
        $this->actingAs($editor, 'web')->get(route('admin.digital-cards.create'))->assertStatus(403);
    }
}
