@extends('layouts.admin')

@section('title', 'Register Client')
@section('page_title', 'Create Client Organization')
@section('page_subtitle', 'Onboard a new client company and generate their unique access credential')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-custom p-4 bg-white">
            <form method="POST" action="{{ route('admin.clients.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name') }}" required placeholder="e.g. ABC Hotel & Resorts">
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Contact Person Name <span class="text-danger">*</span></label>
                        <input type="text" name="contact_name" class="form-control @error('contact_name') is-invalid @enderror" value="{{ old('contact_name') }}" required placeholder="e.g. John Doe">
                        @error('contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required placeholder="client@company.com">
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="+1 (555) 000-0000">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Street address">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city') }}" placeholder="City">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country') }}" placeholder="Country">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Company Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        <small class="text-muted">PNG, JPG, SVG or WEBP up to 2MB</small>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Client Access Code Configuration -->
                    <div class="col-12 mt-4 pt-3 border-top">
                        <div class="p-3 bg-light rounded-3 border">
                            <h6 class="fw-bold mb-1"><i class="bi bi-key-fill text-primary me-1"></i> Client Portal Access Code</h6>
                            <small class="text-muted d-block mb-3">This code is used on <code>/client/access</code> for the client to enter their portal.</small>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Access Code</label>
                                    <input type="text" name="access_code" class="form-control font-mono fw-bold fs-6 text-uppercase @error('access_code') is-invalid @enderror" value="{{ old('access_code', $suggestedCode) }}">
                                    <small class="text-muted">Pre-filled with a cryptographically unique code, or customize as needed.</small>
                                    @error('access_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold">Initial Password (Optional)</label>
                                    <input type="password" name="password" class="form-control" placeholder="Leave empty for client to set on first login">
                                    <small class="text-muted">If left blank, the client will be prompted to create their password on first login.</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label small fw-semibold">Internal Admin Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Private internal notes regarding this account...">{{ old('notes') }}</textarea>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.clients.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4">Save & Register Client</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
