@extends('layouts.admin')

@section('title', 'Roles & Permissions')
@section('page_title', 'Roles & RBAC Permissions')
@section('page_subtitle', 'Define organizational roles and assign fine-grained system capabilities')

@section('content')
<div class="row g-4">
    <!-- Roles List -->
    <div class="col-lg-7">
        <div class="card card-custom p-4 bg-white">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-bold mb-0">System & Custom Roles</h5>
                    <small class="text-muted">Dynamic permission groups for staff members</small>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="btn btn-primary-custom btn-sm rounded-pill">
                    <i class="bi bi-shield-plus me-1"></i> Add Custom Role
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light small text-uppercase">
                        <tr>
                            <th>Role</th>
                            <th>Slug</th>
                            <th>Assigned Users</th>
                            <th>Permissions</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>
                                    <div class="fw-bold text-dark">{{ $role->name }}</div>
                                    <small class="text-muted">{{ $role->description }}</small>
                                    @if($role->is_system)
                                        <span class="badge bg-light text-secondary border mt-1">System Role</span>
                                    @endif
                                </td>
                                <td><code class="text-primary">{{ $role->slug }}</code></td>
                                <td>
                                    <span class="badge bg-info-subtle text-info">{{ $role->users_count }} user(s)</span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary-subtle text-secondary">{{ $role->permissions_count }} granted</span>
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary me-1" title="Edit Permissions">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    @if(!$role->is_system && $role->users_count === 0)
                                        <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete this role?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Permissions Directory by Module -->
    <div class="col-lg-5">
        <div class="card card-custom p-4 bg-white">
            <h5 class="fw-bold mb-1">Available System Permissions</h5>
            <small class="text-muted d-block mb-3">Grouped by application domain</small>

            <div class="accordion" id="permAccordion">
                @foreach($permissionsByModule as $module => $perms)
                    <div class="accordion-item border mb-2 rounded-3 overflow-hidden">
                        <h2 class="accordion-header" id="heading_{{ $loop->index }}">
                            <button class="accordion-button collapsed py-2 px-3 bg-light fw-bold text-capitalize text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse_{{ $loop->index }}">
                                <i class="bi bi-folder2-open me-2 text-primary"></i> {{ $module }} Module
                                <span class="badge bg-secondary ms-auto me-2">{{ $perms->count() }}</span>
                            </button>
                        </h2>
                        <div id="collapse_{{ $loop->index }}" class="accordion-collapse collapse" data-bs-parent="#permAccordion">
                            <div class="accordion-body p-3">
                                <ul class="list-unstyled mb-0 small d-flex flex-column gap-2">
                                    @foreach($perms as $p)
                                        <li class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <strong class="d-block text-dark">{{ $p->name }}</strong>
                                                <span class="text-muted" style="font-size: 0.75rem;">{{ $p->description }}</span>
                                            </div>
                                            <code class="text-muted ms-2" style="font-size: 0.72rem;">{{ $p->slug }}</code>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
