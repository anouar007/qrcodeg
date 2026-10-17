@extends('layouts.admin')

@section('title', 'Users & Staff Management')
@section('page_title', 'Staff & User Management')
@section('page_subtitle', 'Manage administrative accounts, role allocations, and operational permissions')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <!-- Header with Filters & Search -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Platform Administrators & Staff</h5>
            <small class="text-muted">Total: {{ $users->total() }} accounts registered</small>
        </div>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary-custom rounded-pill">
            <i class="bi bi-person-plus-fill me-1"></i> Add New User
        </a>
    </div>

    <!-- Filter Form -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 mb-4 p-3 bg-light rounded-3 border">
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0" placeholder="Search name, username, or email..." value="{{ request('search') }}">
            </div>
        </div>

        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">All Roles</option>
                @foreach($roles as $role)
                    <option value="{{ $role->slug }}" {{ request('role') == $role->slug ? 'selected' : '' }}>{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="status" class="form-select">
                <option value="">All Statuses</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
        </div>

        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-dark w-100"><i class="bi bi-filter"></i> Filter</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary" title="Reset"><i class="bi bi-arrow-clockwise"></i></a>
        </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th>User</th>
                    <th>Username</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Last Login</th>
                    <th>Created</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle bg-primary-subtle text-primary fw-bold d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                    {{ substr($user->first_name, 0, 1) }}
                                </div>
                                <div>
                                    <span class="fw-bold text-dark d-block">{{ $user->name }}</span>
                                    <small class="text-muted">{{ $user->email }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <code class="text-dark">{{ $user->username }}</code>
                            @if($user->phone)
                                <small class="text-muted d-block">{{ $user->phone }}</small>
                            @endif
                        </td>
                        <td>
                            @foreach($user->roles as $role)
                                <span class="badge {{ $role->slug === 'admin' ? 'bg-primary' : 'bg-secondary' }} me-1">
                                    {{ $role->name }}
                                </span>
                            @endforeach
                        </td>
                        <td>
                            @if($user->status === 'active')
                                <span class="badge bg-success-subtle text-success">Active</span>
                            @else
                                <span class="badge bg-danger-subtle text-danger">Inactive</span>
                            @endif
                        </td>
                        <td class="text-muted small">
                            {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="text-muted small">
                            {{ $user->created_at->format('M d, Y') }}
                        </td>
                        <td class="text-end">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    Actions
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('admin.users.edit', $user) }}"><i class="bi bi-pencil me-2 text-primary"></i> Edit Account</a></li>
                                    <li>
                                        <form action="{{ route('admin.users.toggle-status', $user) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="dropdown-item">
                                                <i class="bi bi-power me-2 {{ $user->status === 'active' ? 'text-warning' : 'text-success' }}"></i>
                                                {{ $user->status === 'active' ? 'Deactivate' : 'Activate' }}
                                            </button>
                                        </form>
                                    </li>
                                    @if($user->id !== auth('web')->id())
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Delete this user account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">
                                                    <i class="bi bi-trash me-2"></i> Delete User
                                                </button>
                                            </form>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-people fs-2 d-block mb-2"></i>
                            No users found matching your filter criteria.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</div>
@endsection
