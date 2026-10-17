@extends('layouts.admin')

@section('title', 'Edit Template: ' . $template->name)
@section('page_title', 'Edit QR Template')
@section('page_subtitle', 'Update color palette and default content presets')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white">
            <form method="POST" action="{{ route('admin.templates.update', $template) }}">
                @csrf
                @method('PUT')

                @php
                    $colors = $template->default_colors ?? [];
                    $preset = $template->content_preset ?? [];
                @endphp

                <div class="row g-3">
                    <div class="col-md-8">
                        <label class="form-label small fw-semibold">Template Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $template->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Template Type <span class="text-danger">*</span></label>
                        <select name="type" class="form-select" required>
                            <option value="custom_landing" {{ old('type', $template->type) === 'custom_landing' ? 'selected' : '' }}>Custom Landing Page</option>
                            <option value="contact" {{ old('type', $template->type) === 'contact' ? 'selected' : '' }}>Contact & vCard</option>
                            <option value="wifi" {{ old('type', $template->type) === 'wifi' ? 'selected' : '' }}>WiFi Network</option>
                            <option value="business" {{ old('type', $template->type) === 'business' ? 'selected' : '' }}>Business Profile</option>
                            <option value="product" {{ old('type', $template->type) === 'product' ? 'selected' : '' }}>Product Showcase</option>
                            <option value="social" {{ old('type', $template->type) === 'social' ? 'selected' : '' }}>Social Media Hub</option>
                            <option value="website" {{ old('type', $template->type) === 'website' ? 'selected' : '' }}>Website Link</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $template->description) }}</textarea>
                    </div>

                    <!-- Colors -->
                    <div class="col-12 mt-3 pt-3 border-top">
                        <h6 class="fw-bold mb-2">Palette Colors</h6>
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Primary Brand Color</label>
                        <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ old('primary_color', $colors['primary'] ?? '#4f46e5') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Background Color</label>
                        <input type="color" name="background_color" class="form-control form-control-color w-100" value="{{ old('background_color', $colors['background'] ?? '#ffffff') }}">
                    </div>

                    <div class="col-md-4">
                        <label class="form-label small fw-semibold">Text Color</label>
                        <input type="color" name="text_color" class="form-control form-control-color w-100" value="{{ old('text_color', $colors['text'] ?? '#1e293b') }}">
                    </div>

                    <!-- Preset Content -->
                    <div class="col-12 mt-3 pt-3 border-top">
                        <h6 class="fw-bold mb-2">Preset Content Defaults</h6>
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Preset Heading</label>
                        <input type="text" name="preset_heading" class="form-control" value="{{ old('preset_heading', $preset['heading'] ?? '') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Preset Paragraph</label>
                        <textarea name="preset_paragraph" class="form-control" rows="3">{{ old('preset_paragraph', $preset['paragraph'] ?? '') }}</textarea>
                    </div>

                    <div class="col-12 mt-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="swActive" {{ $template->is_active ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="swActive">Active & Available in Creator Selector</label>
                        </div>
                    </div>

                    <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary-custom px-4">Save Changes</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
