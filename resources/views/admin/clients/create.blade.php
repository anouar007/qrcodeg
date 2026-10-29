@extends('layouts.app')

@section('title', 'New Client Profile')

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item active" aria-current="page">Create</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <x-card title="Create Client Profile" subtitle="Register a new client partner with contact details, project scope, and portal credentials" icon="bi-person-plus">
            <x-slot:actions>
                <a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Clients
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <x-input
                            name="name"
                            label="Client / Contact Name"
                            :value="old('name', old('contact_name'))"
                            required
                            placeholder="e.g. Sara Mansouri"
                            icon="bi-person"
                        />
                    </div>

                    <!-- Project Name -->
                    <div class="col-md-6">
                        <x-input
                            name="project_name"
                            label="Project Name"
                            :value="old('project_name')"
                            placeholder="e.g. Luxury Resort Rebranding"
                            icon="bi-folder2-open"
                            help="Associated campaign or project workspace."
                        />
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <x-input
                            name="city"
                            label="City"
                            :value="old('city')"
                            placeholder="e.g. Marrakech, Casablanca, Paris"
                            icon="bi-geo-alt"
                        />
                    </div>

                    <!-- Phone (International / Local) -->
                    <div class="col-md-6">
                        <x-input
                            name="phone"
                            label="Phone (International / Local)"
                            :value="old('phone')"
                            placeholder="+212600112233 or 0600112233"
                            icon="bi-telephone"
                            help="International (+212..., +1...) or national (06...) format."
                        />
                    </div>

                    <!-- Status -->
                    <div class="col-md-6">
                        <x-select
                            name="status"
                            label="Status"
                            :options="['active' => 'Active', 'inactive' => 'Inactive']"
                            :selected="old('status', 'active')"
                            required
                        />
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <x-input
                            name="email"
                            label="Email Address"
                            type="email"
                            :value="old('email')"
                            placeholder="contact@company.com"
                            icon="bi-envelope"
                        />
                    </div>

                    <!-- Company / Brand Name (Optional) -->
                    <div class="col-md-6">
                        <x-input
                            name="company_name"
                            label="Company / Brand Name"
                            :value="old('company_name')"
                            placeholder="e.g. Mansour Group SARL"
                            icon="bi-building"
                        />
                    </div>

                    <!-- Access Code -->
                    <div class="col-md-6">
                        <x-input
                            name="access_code"
                            label="Portal Access Code"
                            :value="old('access_code', $suggestedCode)"
                            placeholder="CL-XXXXXX"
                            icon="bi-key"
                            help="Unique access code for client portal login."
                        />
                    </div>

                    <!-- Password (Optional initial setup) -->
                    <div class="col-md-6">
                        <x-input
                            name="password"
                            label="Initial Password"
                            type="password"
                            placeholder="Optional (Client can set on first login)"
                            icon="bi-lock"
                        />
                    </div>

                    <!-- Logo Upload -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="logo" class="form-label fw-semibold text-dark small mb-1">Company Logo</label>
                            <input type="file" name="logo" id="logo" class="form-control form-control-custom" accept="image/*">
                            <div class="form-text small text-muted">PNG, JPG, SVG, or WEBP up to 2MB.</div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <div class="mb-3">
                            <label for="notes" class="form-label fw-semibold text-dark small mb-1">Notes</label>
                            <textarea
                                name="notes"
                                id="notes"
                                rows="3"
                                class="form-control form-control-custom @error('notes') is-invalid @enderror"
                                placeholder="Internal client notes, special requirements, contact preferences..."
                            >{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <x-slot:footer>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-sm btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Create Client Profile
                        </button>
                    </div>
                </x-slot:footer>
            </form>
        </x-card>
    </div>
</div>
@endsection
