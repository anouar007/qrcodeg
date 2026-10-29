@extends('layouts.app')

@section('title', 'Edit Client: ' . ($client->name ?: $client->contact_name))

@section('breadcrumbs')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.index') }}">Clients</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.clients.show', $client) }}">{{ $client->name ?: $client->contact_name }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">Edit</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <x-card title="Edit Client Profile" :subtitle="'Update details for ' . ($client->name ?: $client->contact_name) . ' (' . ($client->project_name ?: 'Standard Project') . ')'" icon="bi-pencil">
            <x-slot:actions>
                <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Back to Profile
                </a>
            </x-slot:actions>

            <form method="POST" action="{{ route('admin.clients.update', $client) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <!-- Name -->
                    <div class="col-md-6">
                        <x-input
                            name="name"
                            label="Client / Contact Name"
                            :value="old('name', $client->name ?: $client->contact_name)"
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
                            :value="old('project_name', $client->project_name)"
                            placeholder="e.g. Luxury Resort Rebranding"
                            icon="bi-folder2-open"
                        />
                    </div>

                    <!-- City -->
                    <div class="col-md-6">
                        <x-input
                            name="city"
                            label="City"
                            :value="old('city', $client->city)"
                            placeholder="e.g. Marrakech, Casablanca, Paris"
                            icon="bi-geo-alt"
                        />
                    </div>

                    <!-- Phone (International / Local) -->
                    <div class="col-md-6">
                        <x-input
                            name="phone"
                            label="Phone (International / Local)"
                            :value="old('phone', $client->phone)"
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
                            :selected="old('status', $client->status)"
                            required
                        />
                    </div>

                    <!-- Email -->
                    <div class="col-md-6">
                        <x-input
                            name="email"
                            label="Email Address"
                            type="email"
                            :value="old('email', $client->email)"
                            required
                            placeholder="contact@company.com"
                            icon="bi-envelope"
                        />
                    </div>

                    <!-- Company / Brand Name -->
                    <div class="col-md-6">
                        <x-input
                            name="company_name"
                            label="Company / Brand Name"
                            :value="old('company_name', $client->company_name)"
                            placeholder="e.g. Mansour Group SARL"
                            icon="bi-building"
                        />
                    </div>

                    <!-- Access Code -->
                    <div class="col-md-6">
                        <x-input
                            name="access_code"
                            label="Portal Access Code"
                            :value="old('access_code', $client->access_code)"
                            placeholder="CL-XXXXXX"
                            icon="bi-key"
                            help="Leave blank to maintain current access code."
                        />
                    </div>

                    <!-- Password (Optional reset) -->
                    <div class="col-md-6">
                        <x-input
                            name="password"
                            label="Set New Password"
                            type="password"
                            placeholder="Leave blank to keep current password"
                            icon="bi-lock"
                        />
                    </div>

                    <!-- Logo Upload -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="logo" class="form-label fw-semibold text-dark small mb-1">Update Logo</label>
                            @if($client->logo)
                                <div class="d-flex align-items-center gap-2 mb-2">
                                    <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded border p-1" style="height: 38px; width: 38px; object-fit: contain;">
                                    <span class="small text-muted font-mono">Current logo uploaded</span>
                                </div>
                            @endif
                            <input type="file" name="logo" id="logo" class="form-control form-control-custom" accept="image/*">
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
                                placeholder="Internal client notes..."
                            >{{ old('notes', $client->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <x-slot:footer>
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-sm btn-light border">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4 shadow-sm">
                            <i class="bi bi-check2-circle me-1"></i> Update Client Profile
                        </button>
                    </div>
                </x-slot:footer>
            </form>
        </x-card>
    </div>
</div>
@endsection
