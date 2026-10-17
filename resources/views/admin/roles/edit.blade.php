@extends('layouts.admin')

@section('title', 'Edit Role')
@section('page_title', 'Edit Role: ' . $role->name)
@section('page_subtitle', 'Update role permissions and configuration')

@section('content')
<div class="card card-custom p-4 bg-white">
    <form method="POST" action="{{ route('admin.roles.update', $role) }}">
        @csrf
        @method('PUT')

        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <label class="form-label small fw-semibold">Role Name <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $role->name) }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="col-md-6">
                <label class="form-label small fw-semibold">Slug</label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $role->slug) }}" {{ $role->is_system ? 'readonly' : '' }}>
                @if($role->is_system)
                    <small class="text-muted">Slug is locked for core system roles.</small>
                @endif
            </div>

            <div class="col-12">
                <label class="form-label small fw-semibold">Description</label>
                <textarea name="description" class="form-control" rows="2">{{ old('description', $role->description) }}</textarea>
            </div>
        </div>

        <div class="border-top pt-4 mb-4">
            <h6 class="fw-bold mb-3">Assigned Permissions <span class="text-danger">*</span></h6>
            @error('permissions') <div class="alert alert-danger py-2">{{ $message }}</div> @enderror

            @php
                $rolePermIds = old('permissions', $role->permissions->pluck('id')->toArray());
            @endphp

            <div class="row g-3">
                @foreach($permissionsByModule as $module => $perms)
                    <div class="col-md-6 col-lg-4">
                        <div class="card p-3 border rounded-3 bg-light h-100">
                            <h6 class="fw-bold text-capitalize text-primary mb-2 border-bottom pb-2">
                                <i class="bi bi-folder me-1"></i> {{ $module }}
                            </h6>
                            <div class="d-flex flex-column gap-2">
                                @foreach($perms as $p)
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $p->id }}" id="p_{{ $p->id }}"
                                            {{ in_array($p->id, $rolePermIds) ? 'checked' : '' }}>
                                        <label class="form-check-label small" for="p_{{ $p->id }}">
                                            <strong class="d-block text-dark">{{ $p->name }}</strong>
                                            <span class="text-muted" style="font-size: 0.72rem;">{{ $p->description }}</span>
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="d-flex justify-content-end gap-2 border-top pt-3">
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary-custom px-4">Update Role Permissions</button>
        </div>
    </form>
</div>
@endsection
