<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])->get();
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('admin.roles.index', compact('roles', 'permissionsByModule'));
    }

    public function create()
    {
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('admin.roles.create', compact('permissionsByModule'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => 'nullable|string|max:100|alpha_dash|unique:roles,slug',
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $slug = $validated['slug'] ?: Str::slug($validated['name']);

        $role = Role::create([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'is_system' => false,
        ]);

        $role->permissions()->sync($validated['permissions']);

        ActivityLog::log(
            action: 'role_created',
            resourceType: 'Role',
            resourceId: $role->id,
            details: ['name' => $role->name, 'slug' => $role->slug]
        );

        return redirect()->route('admin.roles.index')->with('success', "Role {$role->name} created successfully.");
    }

    public function edit(Role $role)
    {
        $role->load('permissions');
        $permissionsByModule = Permission::all()->groupBy('module');

        return view('admin.roles.edit', compact('role', 'permissionsByModule'));
    }

    public function update(Request $request, Role $role)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'slug' => ['nullable', 'string', 'max:100', 'alpha_dash', Rule::unique('roles')->ignore($role->id)],
            'description' => 'nullable|string|max:500',
            'permissions' => 'required|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $updateData = [
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ];

        if (! $role->is_system && ! empty($validated['slug'])) {
            $updateData['slug'] = $validated['slug'];
        }

        $role->update($updateData);
        $role->permissions()->sync($validated['permissions']);

        ActivityLog::log(
            action: 'role_updated',
            resourceType: 'Role',
            resourceId: $role->id,
            details: ['name' => $role->name]
        );

        return redirect()->route('admin.roles.index')->with('success', "Role {$role->name} updated successfully.");
    }

    public function destroy(Role $role)
    {
        if ($role->is_system) {
            return back()->with('error', 'System roles cannot be deleted.');
        }

        if ($role->users()->count() > 0) {
            return back()->with('error', 'Cannot delete role because it is currently assigned to users. Reassign users first.');
        }

        $roleName = $role->name;
        $role->permissions()->detach();
        $role->delete();

        ActivityLog::log(
            action: 'role_deleted',
            resourceType: 'Role',
            resourceId: $role->id,
            details: ['name' => $roleName]
        );

        return redirect()->route('admin.roles.index')->with('success', "Role {$roleName} deleted.");
    }
}
