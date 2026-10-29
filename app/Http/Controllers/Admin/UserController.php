<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Display a listing of internal users.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $query = User::with(['roles', 'directPermissions']);

        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $role = $request->role;
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('slug', $role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(15)->withQueryString();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'roles'));
    }

    /**
     * Show the form for creating a new internal user.
     */
    public function create(): View
    {
        Gate::authorize('create', User::class);

        $roles = Role::all();
        $permissionGroups = $this->getCategorizedPermissions();

        return view('admin.users.create', compact('roles', 'permissionGroups'));
    }

    /**
     * Store a newly created internal user in storage.
     */
    public function store(UserStoreRequest $request): RedirectResponse
    {
        Gate::authorize('create', User::class);

        $validated = $request->validated();

        $name = ! empty($validated['name'])
            ? $validated['name']
            : trim(($validated['first_name'] ?? '').' '.($validated['last_name'] ?? ''));

        $firstName = $validated['first_name'] ?? explode(' ', $name, 2)[0] ?? '';
        $lastName = $validated['last_name'] ?? explode(' ', $name, 2)[1] ?? '';
        $username = $validated['username'] ?? explode('@', $validated['email'])[0];

        $user = User::create([
            'name' => $name,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'password' => Hash::make($validated['password']),
            'status' => $validated['status'],
        ]);

        if (! empty($validated['roles'])) {
            $user->roles()->sync($validated['roles']);
        }

        $user->directPermissions()->sync($request->input('permissions', []));

        ActivityLog::log(
            action: 'user_created',
            resourceType: 'User',
            resourceId: $user->id,
            details: ['name' => $user->name, 'email' => $user->email, 'status' => $user->status]
        );

        return redirect()->route('admin.users.index')->with('success', "User {$user->name} created successfully.");
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        $roles = Role::all();
        $permissionGroups = $this->getCategorizedPermissions();
        $user->load(['roles', 'directPermissions']);

        return view('admin.users.edit', compact('user', 'roles', 'permissionGroups'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(UserUpdateRequest $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validated();

        $name = ! empty($validated['name'])
            ? $validated['name']
            : trim(($validated['first_name'] ?? $user->first_name).' '.($validated['last_name'] ?? $user->last_name));

        $firstName = $validated['first_name'] ?? explode(' ', $name, 2)[0] ?? '';
        $lastName = $validated['last_name'] ?? explode(' ', $name, 2)[1] ?? '';
        $username = $validated['username'] ?? $user->username;

        $updateData = [
            'name' => $name,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'username' => $username,
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'status' => $validated['status'],
        ];

        if (! empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $user->update($updateData);
        $user->roles()->sync($validated['roles'] ?? []);
        $user->directPermissions()->sync($request->input('permissions', []));

        ActivityLog::log(
            action: 'user_updated',
            resourceType: 'User',
            resourceId: $user->id,
            details: ['name' => $user->name, 'status' => $user->status]
        );

        return redirect()->route('admin.users.index')->with('success', "User {$user->name} updated successfully.");
    }

    /**
     * Toggle status between active and suspended.
     */
    public function toggleStatus(User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        if ($user->id === auth('web')->id()) {
            return back()->with('error', 'You cannot suspend or deactivate your own account.');
        }

        $newStatus = $user->status === 'active' ? 'suspended' : 'active';
        $user->update(['status' => $newStatus]);

        ActivityLog::log(
            action: $newStatus === 'active' ? 'user_activated' : 'user_suspended',
            resourceType: 'User',
            resourceId: $user->id,
            details: ['status' => $newStatus]
        );

        return back()->with('success', "User {$user->name} status changed to {$newStatus}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user): RedirectResponse
    {
        Gate::authorize('delete', $user);

        if ($user->id === auth('web')->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $userName = $user->name;
        $user->delete();

        ActivityLog::log(
            action: 'user_deleted',
            resourceType: 'User',
            resourceId: $user->id,
            details: ['name' => $userName]
        );

        return redirect()->route('admin.users.index')->with('success', "User {$userName} deleted successfully.");
    }

    /**
     * Helper to group permissions into the 5 core modules:
     * Clients, Digital Cards, QR Codes, NFC Tags, and Users & Settings.
     *
     * @return array<string, array{title: string, icon: string, description: string, permissions: Collection}>
     */
    protected function getCategorizedPermissions(): array
    {
        $allPermissions = Permission::all();

        return [
            'clients' => [
                'title' => 'Clients Management',
                'icon' => 'bi-people',
                'description' => 'Granular control over client directories, onboarding, profiles, and histories',
                'permissions' => $allPermissions->filter(fn ($p) => $p->module === 'clients')->values(),
            ],
            'digital_cards' => [
                'title' => 'Digital Cards',
                'icon' => 'bi-person-badge',
                'description' => 'Card generator, template styling, custom blocks, links, and contact export',
                'permissions' => $allPermissions->filter(fn ($p) => $p->module === 'digital_cards')->values(),
            ],
            'qr' => [
                'title' => 'Dynamic QR Codes',
                'icon' => 'bi-qr-code-scan',
                'description' => 'VP- generation, instant destination swap, PDF exports, and telemetry',
                'permissions' => $allPermissions->filter(fn ($p) => in_array($p->module, ['qr', 'qr_documents']))->values(),
            ],
            'nfc_tags' => [
                'title' => 'NFC Tags',
                'icon' => 'bi-broadcast-pin',
                'description' => 'Hardware chip association, rapid target reassignment, and interaction tracking',
                'permissions' => $allPermissions->filter(fn ($p) => in_array($p->module, ['nfc_tags', 'nfc']))->values(),
            ],
            'users_settings' => [
                'title' => 'Users & Settings',
                'icon' => 'bi-shield-lock',
                'description' => 'Full control over internal users, roles, security privileges, and platform settings',
                'permissions' => $allPermissions->filter(fn ($p) => in_array($p->module, ['users_settings', 'users', 'roles', 'settings', 'activity_logs', 'analytics']))->values(),
            ],
        ];
    }
}
