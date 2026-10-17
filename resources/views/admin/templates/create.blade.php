@extends('layouts.admin')

@section('title', 'Create Template')
@section('page_title', 'Build QR Template')
@section('page_subtitle', 'Save design palettes and content configurations as reusable templates')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white">
            <form method="POST" action="{{ route('admin.templates.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Template Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" required placeholder="e.g. VIP Concierge Guide">
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Template Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="custom_landing">Custom Landing Page</option>
                            <option value="contact">Contact & vCard</option>
                            <option value="wifi">WiFi Network</option>
                            <option value="business">Business Profile</option>
                            <option value="product">Product Showcase</option>
                            <option value="social">Social Media Hub</option>
                            <option value="website">Website Link</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2" placeholder="Brief notes on what this template includes...">{{ old('description') }}</textarea>
                    </div>

                    <!-- Colors -->
                    <div class="col-12 mt-3 pt-3 border-top">
                        <h6 class="fw-bold mb-2">Palette Colors</h6>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Primary Brand Color</label>
                        <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ old('primary_color', '#4f46e5') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Background Color</label>
                        <input type="color" name="background_color" class="form-control form-control-color w-100" value="{{ old('background_color', '#ffffff') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Text Color</label>
                        <input type="color" name="text_color" class="form-control form-control-color w-100" value="{{ old('text_color', '#1e293b') }}">
                    </div>

                    <!-- Preset Content -->
                    <div class="col-12 mt-3 pt-3 border-top">
                        <h6 class="fw-bold mb-2">Preset Content Defaults</h6>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Preset Heading</label>
                        <input type="text" name="preset_heading" class="form-control" value="{{ old('preset_heading') }}" placeholder="e.g. Welcome to Our Venue">
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Preset Paragraph</label>
                        <textarea name="preset_paragraph" class="form-control" rows="3" placeholder="Default introductory copy...">{{ old('preset_paragraph') }}</textarea>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="swActive" checked>
                            <label class="form-check-label small fw-semibold" for="swActive">Active & Available in Creator Selector</label>
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4">Save Template</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
