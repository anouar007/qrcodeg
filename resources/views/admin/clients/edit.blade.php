@extends('layouts.admin')

@section('title', 'Edit Client')
@section('page_title', 'Edit Client: ' . $client->company_name)
@section('page_subtitle', 'Update organizational details, status, and company branding')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card card-custom p-4 bg-white">
            <form method="POST" action="{{ route('admin.clients.update', $client) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $client->company_name) }}" required>
                        @error('company_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Contact Person Name <span class="text-danger">*</span></label>
                        <input type="text" name="contact_name" class="form-control @error('contact_name') is-invalid @enderror" value="{{ old('contact_name', $client->contact_name) }}" required>
                        @error('contact_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Email Address <span class="text-danger">*</span></label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $client->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Phone Number</label>
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $client->phone) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Address</label>
                        <input type="text" name="address" class="form-control" value="{{ old('address', $client->address) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">City</label>
                        <input type="text" name="city" class="form-control" value="{{ old('city', $client->city) }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Country</label>
                        <input type="text" name="country" class="form-control" value="{{ old('country', $client->country) }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Update Company Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if($client->logo)
                            <div class="mt-2 d-flex align-items-center gap-2">
                                <img src="{{ asset('storage/' . $client->logo) }}" alt="Logo" class="rounded border p-1" style="height: 36px; object-fit: contain;">
                                <small class="text-muted">Current Logo</small>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Account Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" required>
                            <option value="active" {{ old('status', $client->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $client->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Portal Access Code</label>
                        <input type="text" name="access_code" class="form-control font-mono fw-bold text-uppercase @error('access_code') is-invalid @enderror" value="{{ old('access_code', $client->access_code) }}">
                        @error('access_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-12 mt-3">
                        <label class="form-label small fw-semibold">Internal Admin Notes</label>
                        <textarea name="notes" class="form-control" rows="2">{{ old('notes', $client->notes) }}</textarea>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.clients.show', $client) }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
