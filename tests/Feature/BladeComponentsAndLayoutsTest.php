<?php

namespace Tests\Feature;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
use Tests\TestCase;

class BladeComponentsAndLayoutsTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create([
            'name' => 'Administrator',
            'slug' => 'admin',
            'is_system' => true,
        ]);

        $this->admin = User::create([
            'name' => 'System Admin',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);
        $this->admin->roles()->sync([$role->id]);
    }

    public function test_master_app_layout_renders_correctly(): void
    {
        $response = $this->actingAs($this->admin, 'web')->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('dashboard-wrapper', false);
        $response->assertSee('appSidebar', false);
        $response->assertSee('top-navbar', false);
        $response->assertSee('System Admin', false);
    }

    public function test_guest_layout_renders_for_login(): void
    {
        $response = $this->get(route('login'));
        $response->assertStatus(200);
        $response->assertSee('guest-wrapper', false);
        $response->assertSee('guest-card', false);
        $response->assertSee('Staff &amp; Admin Login', false);
    }

    public function test_card_component_renders_header_actions_and_body(): void
    {
        $view = Blade::render('
            <x-card title="Client Details" subtitle="Active hospitality account" icon="bi-building">
                <x-slot:actions>
                    <button class="btn btn-sm btn-primary">Edit</button>
                </x-slot:actions>
                <p>Card body content</p>
                <x-slot:footer>
                    <span>Last updated today</span>
                </x-slot:footer>
            </x-card>
        ');

        $this->assertStringContainsString('Client Details', $view);
        $this->assertStringContainsString('Active hospitality account', $view);
        $this->assertStringContainsString('bi-building', $view);
        $this->assertStringContainsString('Card body content', $view);
        $this->assertStringContainsString('Last updated today', $view);
        $this->assertStringContainsString('btn-primary', $view);
    }

    public function test_stat_card_component_renders_value_and_trend(): void
    {
        $view = Blade::render('
            <x-stat-card
                title="Total Scans"
                value="14,820"
                icon="bi-qr-code"
                trend="+18.5%"
                color="success"
            />
        ');

        $this->assertStringContainsString('Total Scans', $view);
        $this->assertStringContainsString('14,820', $view);
        $this->assertStringContainsString('bi-qr-code', $view);
        $this->assertStringContainsString('+18.5%', $view);
        $this->assertStringContainsString('stat-success', $view);
        $this->assertStringContainsString('stat-trend-up', $view);
    }

    public function test_table_component_renders_headers_and_empty_state(): void
    {
        // 1. With data
        $viewWithData = Blade::render('
            <x-table :headers="[\'ID\', \'Name\', \'Status\']">
                <tr>
                    <td>1</td>
                    <td>Alpha</td>
                    <td>Active</td>
                </tr>
            </x-table>
        ');

        $this->assertStringContainsString('ID', $viewWithData);
        $this->assertStringContainsString('Name', $viewWithData);
        $this->assertStringContainsString('Alpha', $viewWithData);

        // 2. Empty state
        $viewEmpty = Blade::render('
            <x-table :headers="[\'ID\', \'Name\']" :isEmpty="true" empty="No dynamic QR codes found." />
        ');

        $this->assertStringContainsString('No dynamic QR codes found.', $viewEmpty);
        $this->assertStringContainsString('table-empty-state', $viewEmpty);
    }

    public function test_badge_component_auto_maps_colors(): void
    {
        $activeBadge = Blade::render('<x-badge status="active" />');
        $this->assertStringContainsString('badge-status-success', $activeBadge);
        $this->assertStringContainsString('Active', $activeBadge);

        $pendingBadge = Blade::render('<x-badge status="pending" />');
        $this->assertStringContainsString('badge-status-warning', $pendingBadge);
        $this->assertStringContainsString('Pending', $pendingBadge);

        $inactiveBadge = Blade::render('<x-badge status="inactive" />');
        $this->assertStringContainsString('badge-status-secondary', $inactiveBadge);
        $this->assertStringContainsString('Inactive', $inactiveBadge);
    }

    public function test_modal_component_renders_dialog(): void
    {
        $view = Blade::render('
            <x-modal id="testModal" title="Confirm Deletion" icon="bi-trash" size="lg">
                <p>Are you sure you want to delete this resource?</p>
                <x-slot:footer>
                    <button class="btn btn-danger">Confirm</button>
                </x-slot:footer>
            </x-modal>
        ');

        $this->assertStringContainsString('id="testModal"', $view);
        $this->assertStringContainsString('Confirm Deletion', $view);
        $this->assertStringContainsString('modal-lg', $view);
        $this->assertStringContainsString('Are you sure you want to delete this resource?', $view);
        $this->assertStringContainsString('Confirm', $view);
    }

    public function test_input_and_select_components_render(): void
    {
        $viewInput = Blade::render('
            <x-input name="project_name" label="Project Name" value="Grand Mirage" :required="true" icon="bi-folder" />
        ');
        $this->assertStringContainsString('name="project_name"', $viewInput);
        $this->assertStringContainsString('value="Grand Mirage"', $viewInput);
        $this->assertStringContainsString('Project Name', $viewInput);
        $this->assertStringContainsString('bi-folder', $viewInput);

        $viewSelect = Blade::render('
            <x-select
                name="template_type"
                label="Template Type"
                :options="[\'google_review\' => \'Google Review\', \'menu\' => \'Restaurant Menu\']"
                selected="menu"
            />
        ');
        $this->assertStringContainsString('name="template_type"', $viewSelect);
        $this->assertStringContainsString('Template Type', $viewSelect);
        $this->assertStringContainsString('value="menu" selected', $viewSelect);
    }
}
