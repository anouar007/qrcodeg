@extends('layouts.admin')

@section('title', 'Platform Settings')
@section('page_title', 'Application Settings')
@section('page_subtitle', 'Configure system branding, defaults, analytics parameters, and document defaults')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card card-custom p-4 bg-white">
            <form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                @csrf

                <!-- Brand Settings -->
                <h5 class="fw-bold mb-3 border-bottom pb-2">Branding & Platform Identity</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Application Name <span class="text-danger">*</span></label>
                        <input type="text" name="app_name" class="form-control" value="{{ old('app_name', $settings['app_name']->value ?? 'QR Studio') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Support / Contact Email <span class="text-danger">*</span></label>
                        <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email']->value ?? 'support@qrstudio.io') }}" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Hero Tagline</label>
                        <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $settings['tagline']->value ?? 'Modern Dynamic QR Code Management Platform') }}">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Primary Theme Color</label>
                        <input type="color" name="primary_color" class="form-control form-control-color w-100" value="{{ old('primary_color', $settings['primary_color']->value ?? '#4f46e5') }}">
                    </div>

                    <div class="col-12">
                        <label class="form-label small fw-semibold">Application Logo</label>
                        <input type="file" name="logo" class="form-control" accept="image/*">
                        @if(isset($settings['site_logo']) && $settings['site_logo']->value)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $settings['site_logo']->value) }}" alt="Logo" class="rounded border p-1" style="height: 36px; object-fit: contain;">
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Defaults & Document Engine -->
                <h5 class="fw-bold mb-3 border-bottom pb-2">QR Generation & Print Defaults</h5>
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Default QR Image Size (px)</label>
                        <input type="number" name="default_qr_size" class="form-control" value="{{ old('default_qr_size', $settings['default_qr_size']->value ?? 300) }}" min="150" max="1000">
                    </div>

                    <div class="col-md-6">
                        <label class="form-label small fw-semibold">Default Document Paper Size</label>
                        <select name="default_paper_size" class="form-select">
                            <option value="a4" {{ ($settings['default_paper_size']->value ?? '') === 'a4' ? 'selected' : '' }}>A4 Standard (Recommended)</option>
                            <option value="a5" {{ ($settings['default_paper_size']->value ?? '') === 'a5' ? 'selected' : '' }}>A5</option>
                            <option value="letter" {{ ($settings['default_paper_size']->value ?? '') === 'letter' ? 'selected' : '' }}>US Letter</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" name="enable_geo_tracking" value="1" id="swGeo"
                                {{ ($settings['enable_geo_tracking']->value ?? '1') == '1' ? 'checked' : '' }}>
                            <label class="form-check-label small fw-semibold" for="swGeo">Enable Country & Device Analytics Detection on Scans</label>
                        </div>
                    </div>
                </div>

                <div class="pt-3 border-top d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary-custom px-4">Save Platform Settings</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
