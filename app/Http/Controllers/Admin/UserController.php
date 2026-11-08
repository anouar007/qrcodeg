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
use Symfony\Component\HttpFoundation\StreamedResponse;

class UserController extends Controller
{
    /**
     * Display a listing of internal users.
     */
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', User::class);

        $query = User::with(['roles.permissions', 'directPermissions'])
            ->withCount(['digitalCards', 'qrCodes', 'activityLogs']);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $role = (string) $request->role;
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('slug', $role);
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sort = (string) $request->input('sort', 'latest');
        if ($sort === 'name') {
            $query->orderBy('name')->orderBy('first_name');
        } elseif ($sort === 'last_login') {
            $query->orderByDesc('last_login_at');
        } elseif ($sort === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $users = $query->paginate(15)->withQueryString();
        $roles = Role::withCount(['users', 'permissions'])->get();

        $stats = [
            'total' => User::count(),
            'active' => User::where('status', 'active')->count(),
            'suspended' => User::where('status', 'suspended')->count(),
            'inactive' => User::where('status', 'inactive')->count(),
            'admins' => User::whereHas('roles', fn ($q) => $q->where('slug', 'admin'))->count(),
            'total_permissions' => Permission::count(),
        ];

        return view('admin.users.index', compact('users', 'roles', 'stats'));
    }

    /**
     * Show the form for creating a new internal user.
     */
    public function create(): View
    {
        Gate::authorize('create', User::class);

        $roles = Role::with('permissions')->get();
        $permissionGroups = $this->getCategorizedPermissions();
        $rolePermissionsMap = $roles->mapWithKeys(fn (Role $r) => [
            $r->id => $r->permissions->pluck('id')->values()->all(),
        ])->all();

        return view('admin.users.create', compact('roles', 'permissionGroups', 'rolePermissionsMap'));
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
     * Display the specified user dossier, effective permissions, and audit trail.
     */
    public function show(User $user): View
    {
        Gate::authorize('view', $user);

        $user->load(['roles.permissions', 'directPermissions'])
            ->loadCount(['digitalCards', 'qrCodes', 'qrDocuments', 'activityLogs']);

        $permissionGroups = $this->getCategorizedPermissions();
        $rolePermissionIds = $user->roles->flatMap->permissions->pluck('id')->unique()->values()->all();
        $directPermissionIds = $user->directPermissions->pluck('id')->unique()->values()->all();

        $recentLogs = ActivityLog::with('user')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id)
                    ->orWhere(function ($q2) use ($user) {
                        $q2->where('resource_type', 'User')->where('resource_id', $user->id);
                    });
            })
            ->latest()
            ->limit(20)
            ->get();

        return view('admin.users.show', compact(
            'user',
            'permissionGroups',
            'rolePermissionIds',
            'directPermissionIds',
            'recentLogs'
        ));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user): View
    {
        Gate::authorize('update', $user);

        $roles = Role::with('permissions')->get();
        $permissionGroups = $this->getCategorizedPermissions();
        $user->load(['roles.permissions', 'directPermissions'])
            ->loadCount(['digitalCards', 'qrCodes', 'activityLogs']);

        $rolePermissionsMap = $roles->mapWithKeys(fn (Role $r) => [
            $r->id => $r->permissions->pluck('id')->values()->all(),
        ])->all();

        return view('admin.users.edit', compact('user', 'roles', 'permissionGroups', 'rolePermissionsMap'));
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
     * Reset a collaborator's password directly from the quick modal.
     */
    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        Gate::authorize('update', $user);

        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        ActivityLog::log(
            action: 'user_password_reset',
            resourceType: 'User',
            resourceId: $user->id,
            details: ['name' => $user->name, 'email' => $user->email]
        );

        return back()->with('success', "Mot de passe réinitialisé avec succès pour {$user->name}.");
    }

    /**
     * Execute bulk actions on selected user accounts.
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'action' => ['required', 'string', 'in:activate,suspend,inactive,assign_role,delete'],
            'user_ids' => ['required', 'array', 'min:1'],
            'user_ids.*' => ['integer', 'exists:users,id'],
            'role_id' => ['nullable', 'integer', 'exists:roles,id'],
        ]);

        $action = $validated['action'];
        $currentUserId = auth('web')->id();
        $targetUsers = User::whereIn('id', $validated['user_ids'])->get();
        $processed = 0;

        foreach ($targetUsers as $targetUser) {
            if ($action === 'delete') {
                if ($targetUser->id === $currentUserId || ! Gate::allows('delete', $targetUser)) {
                    continue;
                }
                $targetUser->delete();
                $processed++;
            } else {
                if (! Gate::allows('update', $targetUser)) {
                    continue;
                }
                if (in_array($action, ['suspend', 'inactive'], true) && $targetUser->id === $currentUserId) {
                    continue;
                }

                if ($action === 'activate') {
                    $targetUser->update(['status' => 'active']);
                    $processed++;
                } elseif ($action === 'suspend') {
                    $targetUser->update(['status' => 'suspended']);
                    $processed++;
                } elseif ($action === 'inactive') {
                    $targetUser->update(['status' => 'inactive']);
                    $processed++;
                } elseif ($action === 'assign_role' && ! empty($validated['role_id'])) {
                    $targetUser->roles()->syncWithoutDetaching([$validated['role_id']]);
                    $processed++;
                }
            }
        }

        if ($processed > 0) {
            ActivityLog::log(
                action: "users_bulk_{$action}",
                resourceType: 'User',
                resourceId: null,
                details: ['count' => $processed, 'action' => $action]
            );
        }

        return back()->with('success', "Action groupée appliquée sur {$processed} compte(s) utilisateur.");
    }

    /**
     * Export internal users list as UTF-8 CSV.
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        Gate::authorize('viewAny', User::class);

        $query = User::with(['roles', 'directPermissions']);

        if ($request->filled('search')) {
            $search = trim((string) $request->search);
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $role = (string) $request->role;
            $query->whereHas('roles', fn ($q) => $q->where('slug', $role));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->get();
        $filename = 'users-export-'.now()->format('Y-m-d-His').'.csv';

        return response()->streamDownload(function () use ($users) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                return;
            }
            fwrite($handle, "\xEF\xBB\xBF");
            fputcsv($handle, [
                'ID',
                'Nom Complet',
                'Identifiant',
                'Email',
                'Téléphone',
                'Statut',
                'Rôles',
                'Permissions Directes',
                'Dernière Connexion',
                'Date Création',
            ]);

            foreach ($users as $user) {
                fputcsv($handle, [
                    $user->id,
                    $user->name,
                    $user->username,
                    $user->email,
                    $user->phone ?? '',
                    $user->status,
                    $user->roles->pluck('name')->join(', '),
                    $user->directPermissions->pluck('slug')->join(', '),
                    $user->last_login_at ? $user->last_login_at->format('Y-m-d H:i') : 'Jamais',
                    $user->created_at ? $user->created_at->format('Y-m-d H:i') : '',
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
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
