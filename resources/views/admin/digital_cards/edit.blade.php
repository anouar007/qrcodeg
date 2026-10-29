@extends('layouts.app')

@section('title', 'Edit Digital Business Card: ' . ($digitalCard->title ?: $digitalCard->name))

@section('content')
<div class="container-fluid px-0">
    <!-- Page Header Matching Screenshot Aesthetic -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <h2 class="fw-bold text-dark mb-0" style="font-size: 1.65rem; letter-spacing: -0.02em;">Modifier la Carte Visite</h2>
                <a href="{{ route('digital-cards.public.show', $digitalCard->slug) }}" target="_blank" class="badge text-decoration-none font-mono" style="background: rgba(99, 32, 238, 0.1); color: #6320ee; border: 1px solid rgba(99, 32, 238, 0.2); font-size: 0.75rem;">
                    /c/{{ $digitalCard->slug }} <i class="bi bi-box-arrow-up-right ms-1"></i>
                </a>
            </div>
            <p class="text-muted small mb-0" style="color: #64748b;">
                Mettez à jour les coordonnées, le style visuel et visualisez le rendu smartphone en temps réel.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('admin.digital-cards.index') }}" class="btn btn-outline-secondary fw-medium" style="border-radius: 8px; border-color: #e2e8f0; font-size: 0.9rem; padding: 0.55rem 1.25rem; color: #334155; background: #ffffff;">
                Retour
            </a>
            <a href="{{ route('digital-cards.public.show', $digitalCard->slug) }}" target="_blank" class="btn btn-outline-primary fw-medium" style="border-radius: 8px; font-size: 0.9rem; padding: 0.55rem 1.25rem;">
                <i class="bi bi-eye me-1"></i>Voir en direct
            </a>
            <button type="submit" form="cardBuilderForm" class="btn text-white fw-medium d-flex align-items-center gap-1.5 shadow-sm" style="background-color: #6320ee; border-radius: 8px; padding: 0.55rem 1.35rem; font-size: 0.9rem;">
                <i class="bi bi-check2-circle fs-6"></i>
                <span>Enregistrer les modifications</span>
            </button>
        </div>
    </div>

<form id="cardBuilderForm" method="POST" action="{{ route('admin.digital-cards.update', $digitalCard) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <input type="hidden" name="theme_config" id="theme_config_json">
    <input type="hidden" name="content_blocks" id="content_blocks_json">

    <div class="row g-4">
        <!-- =================================================================== -->
        <!-- LEFT PANE: CUSTOMIZER ACCORDION -->
        <!-- =================================================================== -->
        <div class="col-xl-7 col-lg-6">
            <div class="accordion custom-accordion shadow-sm" id="builderAccordion">

                <!-- 1. Template Presets Picker -->
                <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                    <h2 class="accordion-header" id="headingPreset">
                        <button class="accordion-button fw-bold py-3 px-4 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapsePreset" aria-expanded="true" aria-controls="collapsePreset">
                            <i class="bi bi-palette2 me-2 text-primary fs-5"></i> 1. Template Presets
                        </button>
                    </h2>
                    <div id="collapsePreset" class="accordion-collapse collapse show" aria-labelledby="headingPreset">
                        <div class="accordion-body bg-light-subtle p-4 border-top">
                            <div class="row g-3">
                                @foreach($presets as $key => $preset)
                                    <div class="col-md-4">
                                        <div class="template-preset-card p-3 rounded-3 border bg-white cursor-pointer {{ ($digitalCard->template_id === $key || $digitalCard->template === $key) ? 'active' : '' }} position-relative" data-preset="{{ $key }}">
                                            <div class="form-check position-absolute top-0 end-0 m-2">
                                                <input class="form-check-input" type="radio" name="template_id" id="preset_{{ $key }}" value="{{ $key }}" {{ ($digitalCard->template_id === $key || $digitalCard->template === $key) ? 'checked' : '' }}>
                                            </div>
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <span class="preset-color-dot" style="background: {{ $preset['primary_color'] }};"></span>
                                                <span class="preset-color-dot" style="background: {{ $preset['background_color'] }};"></span>
                                            </div>
                                            <h6 class="fw-bold mb-1 text-dark">{{ $preset['name'] }}</h6>
                                            <p class="text-muted small mb-0 font-mono" style="font-size: 0.72rem;">{{ $preset['font_family'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. Profile & Identity Information -->
                <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                    <h2 class="accordion-header" id="headingIdentity">
                        <button class="accordion-button collapsed fw-bold py-3 px-4 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseIdentity" aria-expanded="false" aria-controls="collapseIdentity">
                            <i class="bi bi-person-badge me-2 text-primary fs-5"></i> 2. Profile & Identity Details
                        </button>
                    </h2>
                    <div id="collapseIdentity" class="accordion-collapse collapse" aria-labelledby="headingIdentity">
                        <div class="accordion-body bg-light-subtle p-4 border-top">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Full Name <span class="text-danger">*</span></label>
                                    <div class="input-icon-group">
                                        <i class="bi bi-person input-icon text-muted"></i>
                                        <input type="text" name="full_name" id="input_full_name" class="form-control form-control-custom" value="{{ old('full_name', $digitalCard->full_name ?: $digitalCard->name) }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Card Title</label>
                                    <div class="input-icon-group">
                                        <i class="bi bi-tag input-icon text-muted"></i>
                                        <input type="text" name="title" id="input_title" class="form-control form-control-custom" value="{{ old('title', $digitalCard->title ?: $digitalCard->name) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Job Title / Role</label>
                                    <div class="input-icon-group">
                                        <i class="bi bi-briefcase input-icon text-muted"></i>
                                        <input type="text" name="job_title" id="input_job_title" class="form-control form-control-custom" value="{{ old('job_title', $digitalCard->job_title) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Company / Organization</label>
                                    <div class="input-icon-group">
                                        <i class="bi bi-building input-icon text-muted"></i>
                                        <input type="text" name="company" id="input_company" class="form-control form-control-custom" value="{{ old('company', $digitalCard->company) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Verified Badge Text</label>
                                    <div class="input-icon-group">
                                        <i class="bi bi-patch-check input-icon text-muted"></i>
                                        <input type="text" name="badge_text" id="input_badge_text" class="form-control form-control-custom" value="{{ old('badge_text', $digitalCard->badge_text) }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Assigned Client</label>
                                    <select name="client_id" class="form-select form-control-custom">
                                        <option value="">-- No specific client assigned --</option>
                                        @foreach($clients as $c)
                                            <option value="{{ $c->id }}" {{ $digitalCard->client_id == $c->id ? 'selected' : '' }}>
                                                {{ $c->name }} ({{ $c->project_name ?: 'General' }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-12">
                                    <label class="form-label small fw-semibold text-dark">Bio / Summary</label>
                                    <textarea name="bio" id="input_bio" rows="2" class="form-control form-control-custom">{{ old('bio', $digitalCard->bio) }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 3. Media Uploads with Live Client-Side Preview -->
                <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                    <h2 class="accordion-header" id="headingMedia">
                        <button class="accordion-button collapsed fw-bold py-3 px-4 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMedia" aria-expanded="false" aria-controls="collapseMedia">
                            <i class="bi bi-images me-2 text-primary fs-5"></i> 3. Media Uploads & Assets
                        </button>
                    </h2>
                    <div id="collapseMedia" class="accordion-collapse collapse" aria-labelledby="headingMedia">
                        <div class="accordion-body bg-light-subtle p-4 border-top">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Profile Picture</label>
                                    <div class="d-flex align-items-center gap-3 p-3 bg-white rounded-3 border">
                                        @php
                                            $profileSrc = ($digitalCard->profile_photo_path ?: $digitalCard->profile_image)
                                                ? asset('storage/' . ($digitalCard->profile_photo_path ?: $digitalCard->profile_image))
                                                : "https://ui-avatars.com/api/?name=" . urlencode($digitalCard->full_name ?: $digitalCard->name) . "&background=4f46e5&color=fff&size=120";
                                        @endphp
                                        <img id="profileThumbnail" src="{{ $profileSrc }}" alt="Avatar" class="rounded-circle border" style="width: 60px; height: 60px; object-fit: cover;">
                                        <div class="flex-grow-1">
                                            <input type="file" name="profile_photo" id="profilePhotoInput" class="form-control form-control-sm mb-1" accept="image/*">
                                            <small class="text-muted d-block" style="font-size: 0.72rem;">Leave empty to keep current picture.</small>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Cover Banner Image</label>
                                    <div class="p-3 bg-white rounded-3 border">
                                        @php
                                            $coverPath = $digitalCard->cover_photo_path ?: $digitalCard->cover_image;
                                        @endphp
                                        <div id="coverThumbnail" class="rounded-2 border mb-2" style="height: 60px; {{ $coverPath ? "background-image: url('" . asset('storage/' . $coverPath) . "');" : 'background: linear-gradient(135deg, #4f46e5 0%, #1e1b4b 100%);' }} background-size: cover; background-position: center;"></div>
                                        <input type="file" name="cover_photo" id="coverPhotoInput" class="form-control form-control-sm" accept="image/*">
                                        <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">Leave empty to keep current banner.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 4. Styling Controls (Theme Config) -->
                <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                    <h2 class="accordion-header" id="headingStyling">
                        <button class="accordion-button collapsed fw-bold py-3 px-4 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseStyling" aria-controls="collapseStyling">
                            <i class="bi bi-sliders me-2 text-primary fs-5"></i> 4. Styling Controls & Theme Config
                        </button>
                    </h2>
                    <div id="collapseStyling" class="accordion-collapse collapse" aria-labelledby="headingStyling">
                        <div class="accordion-body bg-light-subtle p-4 border-top">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Primary Brand Accent</label>
                                    <div class="input-group">
                                        <input type="color" id="primaryColorPicker" class="form-control form-control-color" value="{{ $currentTheme['primary_color'] ?? '#4f46e5' }}">
                                        <input type="text" name="primary_color" id="primaryColorText" class="form-control form-control-custom font-mono" value="{{ $currentTheme['primary_color'] ?? '#4f46e5' }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Card Background Color</label>
                                    <div class="input-group">
                                        <input type="color" id="bgColorPicker" class="form-control form-control-color" value="{{ $currentTheme['background_color'] ?? '#0f172a' }}">
                                        <input type="text" name="background_color" id="bgColorText" class="form-control form-control-custom font-mono" value="{{ $currentTheme['background_color'] ?? '#0f172a' }}">
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Typography / Font Family</label>
                                    <select name="font_family" id="fontFamilySelect" class="form-select form-control-custom">
                                        @foreach(['Outfit', 'Inter', 'Plus Jakarta Sans', 'Space Grotesk', 'Playfair Display', 'Roboto'] as $font)
                                            <option value="{{ $font }}" {{ ($currentTheme['font_family'] ?? '') === $font ? 'selected' : '' }}>{{ $font }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Shadow Style</label>
                                    <select name="shadow_style" id="shadowStyleSelect" class="form-select form-control-custom">
                                        @foreach(['soft' => 'Soft Diffuse', 'deep' => 'Deep Contrast', 'glow' => 'Neon Ambient Glow', 'flat' => 'Minimal Flat'] as $sKey => $sLabel)
                                            <option value="{{ $sKey }}" {{ ($currentTheme['shadow_style'] ?? '') === $sKey ? 'selected' : '' }}>{{ $sLabel }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold text-dark mb-0">Border Radius</label>
                                        <span class="badge bg-primary-subtle text-primary font-mono" id="borderRadiusBadge">{{ $currentTheme['border_radius'] ?? '16px' }}</span>
                                    </div>
                                    <input type="range" name="border_radius" id="borderRadiusSlider" class="form-range" min="0" max="32" step="2" value="{{ (int) ($currentTheme['border_radius'] ?? 16) }}">
                                </div>

                                <div class="col-md-6">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="form-label small fw-semibold text-dark mb-0">Element Spacing</label>
                                        <span class="badge bg-primary-subtle text-primary font-mono" id="elementSpacingBadge">{{ $currentTheme['element_spacing'] ?? '14px' }}</span>
                                    </div>
                                    <input type="range" name="element_spacing" id="elementSpacingSlider" class="form-range" min="8" max="24" step="2" value="{{ (int) ($currentTheme['element_spacing'] ?? 14) }}">
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label small fw-semibold text-dark">Card Publishing Status</label>
                                    <select name="status" class="form-select form-control-custom">
                                        <option value="active" {{ $digitalCard->status === 'active' ? 'selected' : '' }}>Active (Publicly Viewable)</option>
                                        <option value="inactive" {{ $digitalCard->status === 'inactive' ? 'selected' : '' }}>Inactive (Draft / Suspended)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 5. Dynamic Link Repeater (content_blocks) -->
                <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs">
                    <h2 class="accordion-header" id="headingLinks">
                        <button class="accordion-button collapsed fw-bold py-3 px-4 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseLinks" aria-controls="collapseLinks">
                            <i class="bi bi-link-45deg me-2 text-primary fs-5"></i> 5. Dynamic Link Repeater & Content Blocks
                        </button>
                    </h2>
                    <div id="collapseLinks" class="accordion-collapse collapse" aria-labelledby="headingLinks">
                        <div class="accordion-body bg-light-subtle p-4 border-top">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h6 class="fw-bold mb-0 text-dark">Actionable Links & Blocks</h6>
                                    <small class="text-muted">Reorder, configure icons, labels, and target URLs.</small>
                                </div>
                                <div class="d-flex align-items-center gap-1.5">
                                    <button type="button" class="btn btn-sm btn-primary-custom rounded-pill px-3 fw-medium d-flex align-items-center gap-1.5" id="btnDirectAddBlock" onclick="addDefaultBlock()">
                                        <i class="bi bi-plus-circle-fill"></i> Add Block
                                    </button>
                                    <div class="dropdown">
                                        <button class="btn btn-sm btn-light border rounded-pill px-2.5 dropdown-toggle" type="button" id="btnAddBlockDropdown" data-bs-toggle="dropdown" aria-expanded="false" title="Choose template">
                                            <i class="bi bi-caret-down-fill" style="font-size: 0.72rem;"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end shadow border rounded-3 mt-1 py-1" id="addBlockDropdownMenu" style="min-width: 230px; position: absolute; right: 0; z-index: 1060;">
                                            <li><h6 class="dropdown-header small font-mono text-uppercase py-1 text-muted" style="font-size: 0.7rem;">Block Presets</h6></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="bi-telephone-fill" data-label="Phone Number" data-placeholder="+212600112233"><i class="bi bi-telephone me-2 text-success"></i> Phone Call</a></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="bi-whatsapp" data-label="WhatsApp" data-placeholder="+212600112233"><i class="bi bi-whatsapp me-2 text-success"></i> WhatsApp Direct</a></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="bi-envelope-fill" data-label="Email Address" data-placeholder="hello@example.com"><i class="bi bi-envelope me-2 text-danger"></i> Email</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="social" data-icon="bi-linkedin" data-label="LinkedIn" data-placeholder="https://linkedin.com/in/..."><i class="bi bi-linkedin me-2 text-primary"></i> LinkedIn</a></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="social" data-icon="bi-instagram" data-label="Instagram" data-placeholder="https://instagram.com/..."><i class="bi bi-instagram me-2 text-danger"></i> Instagram</a></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="social" data-icon="bi-twitter-x" data-label="Twitter / X" data-placeholder="https://x.com/..."><i class="bi bi-twitter-x me-2 text-dark"></i> Twitter / X</a></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="social" data-icon="bi-youtube" data-label="YouTube" data-placeholder="https://youtube.com/@..."><i class="bi bi-youtube me-2 text-danger"></i> YouTube</a></li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="custom_link" data-icon="bi-globe" data-label="Website Link" data-placeholder="https://yoursite.com"><i class="bi bi-globe me-2 text-info"></i> Custom Website URL</a></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="bi-geo-alt-fill" data-label="Office Location" data-placeholder="Casablanca Marina"><i class="bi bi-geo-alt me-2 text-warning"></i> Map Location</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div id="blocksContainer" class="d-flex flex-column gap-2 mb-3"></div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- =================================================================== -->
        <!-- RIGHT PANE: STICKY REALISTIC MOBILE DEVICE LIVE PREVIEW -->
        <!-- =================================================================== -->
        <div class="col-xl-5 col-lg-6" style="align-self: stretch;">
            <div class="sticky-preview-wrapper position-sticky" style="top: 85px; z-index: 40;">
                <div class="d-flex justify-content-between align-items-center mb-2 px-2">
                    <div class="small fw-bold text-muted text-uppercase tracking-wider">
                        <i class="bi bi-phone me-1 text-primary"></i> Live Device Simulator
                    </div>
                    <span class="badge bg-success-subtle text-success small font-mono">
                        <span class="status-dot status-dot-active me-1"></span> Live Sync
                    </span>
                </div>

                <div class="phone-frame-mockup mx-auto shadow-2xl">
                    <div class="phone-dynamic-island">
                        <div class="island-camera"></div>
                        <div class="island-speaker"></div>
                    </div>

                    <div class="phone-screen-content" id="phoneScreenPreview">
                        <div class="phone-status-bar d-flex justify-content-between align-items-center px-4 pt-2">
                            <span class="font-mono small fw-bold">09:41</span>
                            <div class="d-flex align-items-center gap-1 small">
                                <i class="bi bi-reception-4"></i>
                                <i class="bi bi-wifi"></i>
                                <i class="bi bi-battery-full fs-6"></i>
                            </div>
                        </div>

                        <div class="preview-cover-banner" id="previewCoverBanner" style="{{ $coverPath ? "background-image: url('" . asset('storage/' . $coverPath) . "');" : '' }}">
                            <div class="cover-gradient-overlay"></div>
                        </div>

                        <div class="preview-profile-wrap text-center px-4">
                            <div class="preview-avatar-container mb-2">
                                <img id="previewAvatarImg" src="{{ $profileSrc }}" alt="Avatar" class="preview-avatar-img">
                            </div>

                            <div id="previewBadgeWrap" class="mb-1" style="{{ $digitalCard->badge_text ? '' : 'display:none;' }}">
                                <span class="preview-badge-pill" id="previewBadgeText">{{ $digitalCard->badge_text }}</span>
                            </div>

                            <h5 class="fw-extrabold mb-1 preview-name-node" id="previewName">{{ $digitalCard->full_name ?: $digitalCard->name }}</h5>
                            <div class="preview-job-node small mb-0 fw-medium opacity-80" id="previewJob">{{ $digitalCard->job_title }}</div>
                            <div class="preview-company-node small mb-2 fw-bold" id="previewCompany">{{ $digitalCard->company }}</div>

                            <p class="preview-bio-node small mb-3 opacity-75" id="previewBio">{{ $digitalCard->bio }}</p>

                            <button type="button" class="btn preview-save-btn w-100 py-2 mb-3 rounded-pill fw-bold small shadow-sm">
                                <i class="bi bi-person-plus-fill me-1"></i> Save to Contacts (.vcf)
                            </button>
                        </div>

                        <div class="preview-links-container px-3 pb-4" id="previewLinksContainer"></div>

                        <div class="preview-footer text-center pb-3 small opacity-50 font-mono" style="font-size: 0.68rem;">
                            POWERED BY VP DIGITAL CARD &bull; CONNECTED NFC
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<style>
.cursor-pointer { cursor: pointer; }
.shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.06); }
.shadow-2xl { box-shadow: 0 25px 60px -15px rgba(15, 23, 42, 0.35); }

.template-preset-card { transition: all 0.2s ease; }
.template-preset-card:hover { border-color: #4f46e5 !important; transform: translateY(-2px); }
.template-preset-card.active {
    border-color: #4f46e5 !important;
    background-color: rgba(79, 70, 229, 0.04) !important;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
}
.preset-color-dot { width: 14px; height: 14px; border-radius: 50%; display: inline-block; border: 1px solid rgba(255,255,255,0.4); }

/* Sticky Simulator Configuration */
.sticky-preview-wrapper {
    position: -webkit-sticky !important;
    position: sticky !important;
    top: 85px !important;
    z-index: 40;
}
@media (max-height: 820px) {
    .phone-frame-mockup {
        height: 520px;
        width: 295px;
    }
    .sticky-preview-wrapper {
        top: 75px !important;
    }
}
.phone-frame-mockup {
    width: 360px;
    height: 720px;
    background: #0f172a;
    border-radius: 46px;
    border: 11px solid #1e293b;
    box-shadow: 0 0 0 2px #334155, 0 25px 60px -12px rgba(0, 0, 0, 0.5);
    position: relative;
    overflow: hidden;
    user-select: none;
}
.phone-dynamic-island {
    position: absolute;
    top: 9px;
    left: 50%;
    transform: translateX(-50%);
    width: 105px;
    height: 22px;
    background: #000000;
    border-radius: 20px;
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 12px;
}
.island-camera { width: 9px; height: 9px; border-radius: 50%; background: #111e38; border: 1px solid #1a2a4c; }
.island-speaker { width: 32px; height: 3px; border-radius: 3px; background: #1e293b; }

.phone-screen-content {
    --card-primary: #4f46e5;
    --card-bg: #0f172a;
    --card-radius: 16px;
    --card-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.25);
    --card-font: 'Outfit', sans-serif;
    --card-spacing: 14px;

    width: 100%;
    height: 100%;
    overflow-y: auto;
    overflow-x: hidden;
    background-color: var(--card-bg);
    color: #ffffff;
    font-family: var(--card-font);
    display: flex;
    flex-direction: column;
    scrollbar-width: none;
}
.phone-screen-content::-webkit-scrollbar { display: none; }
.phone-status-bar { position: relative; z-index: 10; color: rgba(255, 255, 255, 0.85); }

.preview-cover-banner {
    width: 100%;
    height: 120px;
    background: linear-gradient(135deg, var(--card-primary) 0%, #1e1b4b 100%);
    background-size: cover;
    background-position: center;
    position: relative;
    flex-shrink: 0;
}
.cover-gradient-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, transparent 30%, rgba(0,0,0,0.4) 100%); }
.preview-profile-wrap { margin-top: -46px; position: relative; z-index: 5; }

.preview-avatar-img {
    width: 86px;
    height: 86px;
    border-radius: var(--card-radius);
    object-fit: cover;
    border: 3.5px solid var(--card-bg);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.35);
    background: var(--card-bg);
}

.preview-badge-pill {
    display: inline-block;
    font-size: 0.65rem;
    font-weight: 700;
    letter-spacing: 0.5px;
    padding: 0.2rem 0.65rem;
    border-radius: 50px;
    background: rgba(255, 255, 255, 0.1);
    color: var(--card-primary);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.preview-company-node { color: var(--card-primary); }
.preview-save-btn { background-color: var(--card-primary); border-color: var(--card-primary); color: #ffffff !important; }

.preview-link-card {
    background: rgba(255, 255, 255, 0.06);
    border-radius: var(--card-radius);
    border: 1px solid rgba(255, 255, 255, 0.08);
    box-shadow: var(--card-shadow);
    padding: 0.7rem 0.9rem;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    color: #ffffff;
    text-decoration: none;
    margin-bottom: var(--card-spacing);
    transition: transform 0.15s ease;
}
.preview-link-icon-wrap {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.1);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.1rem;
    color: var(--card-primary);
    flex-shrink: 0;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const presets = @json($presets);
    let currentBlocks = @json($currentBlocks);

    const screenEl = document.getElementById('phoneScreenPreview');
    const nameInput = document.getElementById('input_full_name');
    const jobInput = document.getElementById('input_job_title');
    const companyInput = document.getElementById('input_company');
    const bioInput = document.getElementById('input_bio');
    const badgeInput = document.getElementById('input_badge_text');

    const primaryColorPicker = document.getElementById('primaryColorPicker');
    const primaryColorText = document.getElementById('primaryColorText');
    const bgColorPicker = document.getElementById('bgColorPicker');
    const bgColorText = document.getElementById('bgColorText');
    const fontSelect = document.getElementById('fontFamilySelect');
    const shadowSelect = document.getElementById('shadowStyleSelect');
    const radiusSlider = document.getElementById('borderRadiusSlider');
    const radiusBadge = document.getElementById('borderRadiusBadge');
    const spacingSlider = document.getElementById('elementSpacingSlider');
    const spacingBadge = document.getElementById('elementSpacingBadge');

    const previewCover = document.getElementById('previewCoverBanner');
    const previewAvatar = document.getElementById('previewAvatarImg');
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const coverPhotoInput = document.getElementById('coverPhotoInput');
    const profileThumbnail = document.getElementById('profileThumbnail');
    const coverThumbnail = document.getElementById('coverThumbnail');

    const blocksContainer = document.getElementById('blocksContainer');
    const previewLinksContainer = document.getElementById('previewLinksContainer');
    const themeJsonInput = document.getElementById('theme_config_json');
    const contentBlocksJsonInput = document.getElementById('content_blocks_json');

    function syncLivePreviewStyles() {
        const primary = primaryColorPicker.value;
        const bg = bgColorPicker.value;
        const font = fontSelect.value;
        const radius = radiusSlider.value + 'px';
        const spacing = spacingSlider.value + 'px';
        const shadowStyle = shadowSelect.value;

        let shadowCss = '0 10px 25px -5px rgba(0, 0, 0, 0.25)';
        if (shadowStyle === 'deep') shadowCss = '0 25px 50px -12px rgba(0, 0, 0, 0.5)';
        if (shadowStyle === 'glow') shadowCss = `0 0 20px ${primary}55`;
        if (shadowStyle === 'flat') shadowCss = 'none';

        screenEl.style.setProperty('--card-primary', primary);
        screenEl.style.setProperty('--card-bg', bg);
        screenEl.style.setProperty('--card-font', `'${font}', sans-serif`);
        screenEl.style.setProperty('--card-radius', radius);
        screenEl.style.setProperty('--card-spacing', spacing);
        screenEl.style.setProperty('--card-shadow', shadowCss);

        radiusBadge.textContent = radius;
        spacingBadge.textContent = spacing;

        const themeConfig = {
            primary_color: primary,
            background_color: bg,
            font_family: font,
            border_radius: radius,
            shadow_style: shadowStyle,
            element_spacing: spacing
        };
        themeJsonInput.value = JSON.stringify(themeConfig);
    }

    function syncLivePreviewText() {
        const nameVal = nameInput.value.trim() || 'Your Full Name';
        document.getElementById('previewName').textContent = nameVal;
        document.getElementById('previewJob').textContent = jobInput.value.trim() || '';
        document.getElementById('previewCompany').textContent = companyInput.value.trim() || '';
        document.getElementById('previewBio').textContent = bioInput.value.trim() || '';

        const badgeVal = badgeInput.value.trim();
        const badgeWrap = document.getElementById('previewBadgeWrap');
        if (badgeVal) {
            badgeWrap.style.display = 'block';
            document.getElementById('previewBadgeText').textContent = badgeVal;
        } else {
            badgeWrap.style.display = 'none';
        }
    }

    profilePhotoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (evt) {
                previewAvatar.src = evt.target.result;
                profileThumbnail.src = evt.target.result;
            };
            reader.readAsDataURL(file);
        }
    });

    coverPhotoInput.addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function (evt) {
                previewCover.style.backgroundImage = `url('${evt.target.result}')`;
                coverThumbnail.style.backgroundImage = `url('${evt.target.result}')`;
            };
            reader.readAsDataURL(file);
        }
    });

    primaryColorPicker.addEventListener('input', function () {
        primaryColorText.value = primaryColorPicker.value;
        syncLivePreviewStyles();
    });
    primaryColorText.addEventListener('input', function () {
        if (/^#[0-9A-Fa-f]{6}$/.test(primaryColorText.value)) {
            primaryColorPicker.value = primaryColorText.value;
            syncLivePreviewStyles();
        }
    });

    bgColorPicker.addEventListener('input', function () {
        bgColorText.value = bgColorPicker.value;
        syncLivePreviewStyles();
    });
    bgColorText.addEventListener('input', function () {
        if (/^#[0-9A-Fa-f]{6}$/.test(bgColorText.value)) {
            bgColorPicker.value = bgColorText.value;
            syncLivePreviewStyles();
        }
    });

    fontSelect.addEventListener('change', syncLivePreviewStyles);
    shadowSelect.addEventListener('change', syncLivePreviewStyles);
    radiusSlider.addEventListener('input', syncLivePreviewStyles);
    spacingSlider.addEventListener('input', syncLivePreviewStyles);

    [nameInput, jobInput, companyInput, bioInput, badgeInput].forEach(inp => {
        inp.addEventListener('input', syncLivePreviewText);
    });

    document.querySelectorAll('.template-preset-card').forEach(card => {
        card.addEventListener('click', function () {
            const presetKey = this.dataset.preset;
            const config = presets[presetKey];
            if (!config) return;

            document.querySelectorAll('.template-preset-card').forEach(c => c.classList.remove('active'));
            this.classList.add('active');
            const radio = this.querySelector('input[type="radio"]');
            if (radio) radio.checked = true;

            primaryColorPicker.value = config.primary_color;
            primaryColorText.value = config.primary_color;
            bgColorPicker.value = config.background_color;
            bgColorText.value = config.background_color;
            fontSelect.value = config.font_family;
            radiusSlider.value = parseInt(config.border_radius, 10);
            shadowSelect.value = config.shadow_style;
            spacingSlider.value = parseInt(config.element_spacing, 10);

            syncLivePreviewStyles();
            syncLivePreviewText();
        });
    });

    function renderIconElement(iconClass) {
        if (!iconClass) return '<i class="bi bi-link-45deg"></i>';
        const trimmed = iconClass.trim();
        if (trimmed.includes('fa-') || trimmed.startsWith('fas ') || trimmed.startsWith('fab ') || trimmed.startsWith('far ')) {
            return `<i class="${trimmed}"></i>`;
        }
        const clean = trimmed.replace(/^bi\s+/, '');
        return `<i class="bi ${clean}"></i>`;
    }

    function renderRepeaterAndPreview() {
        blocksContainer.innerHTML = '';
        previewLinksContainer.innerHTML = '';

        currentBlocks.forEach((block, index) => {
            block.order = index + 1;

            const row = document.createElement('div');
            row.className = 'card card-custom p-3 bg-white border rounded-3';
            row.dataset.id = block.id;

            row.innerHTML = `
                <div class="d-flex align-items-center justify-content-between gap-2 mb-2">
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge bg-light text-muted border font-mono small">${index + 1}</span>
                        <span class="text-primary fs-6 d-inline-flex align-items-center">${renderIconElement(block.icon)}</span>
                        <span class="small fw-bold text-dark">${block.label || 'Block'}</span>
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0 move-up-btn" ${index === 0 ? 'disabled' : ''} title="Move Up">
                            <i class="bi bi-arrow-up"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0 move-down-btn" ${index === currentBlocks.length - 1 ? 'disabled' : ''} title="Move Down">
                            <i class="bi bi-arrow-down"></i>
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-0 remove-block-btn" title="Remove">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
                <div class="row g-2">
                    <div class="col-sm-5">
                        <input type="text" class="form-control form-control-sm block-label-input" value="${block.label || ''}" placeholder="Label">
                    </div>
                    <div class="col-sm-7">
                        <input type="text" class="form-control form-control-sm font-mono block-url-input" value="${block.url || ''}" placeholder="URL or Value">
                    </div>
                </div>
            `;

            row.querySelector('.block-label-input').addEventListener('input', function (e) {
                block.label = e.target.value;
                syncSerializedData();
                renderPreviewLinksOnly();
            });

            row.querySelector('.block-url-input').addEventListener('input', function (e) {
                block.url = e.target.value;
                syncSerializedData();
                renderPreviewLinksOnly();
            });

            row.querySelector('.move-up-btn').addEventListener('click', function () {
                if (index > 0) {
                    const temp = currentBlocks[index];
                    currentBlocks[index] = currentBlocks[index - 1];
                    currentBlocks[index - 1] = temp;
                    renderRepeaterAndPreview();
                }
            });

            row.querySelector('.move-down-btn').addEventListener('click', function () {
                if (index < currentBlocks.length - 1) {
                    const temp = currentBlocks[index];
                    currentBlocks[index] = currentBlocks[index + 1];
                    currentBlocks[index + 1] = temp;
                    renderRepeaterAndPreview();
                }
            });

            row.querySelector('.remove-block-btn').addEventListener('click', function () {
                currentBlocks = currentBlocks.filter(b => b.id !== block.id);
                renderRepeaterAndPreview();
            });

            blocksContainer.appendChild(row);
        });

        renderPreviewLinksOnly();
        syncSerializedData();
    }

    function renderPreviewLinksOnly() {
        previewLinksContainer.innerHTML = '';
        currentBlocks.forEach(block => {
            if (!block.url) return;
            const a = document.createElement('a');
            a.className = 'preview-link-card';
            a.href = '#';
            a.innerHTML = `
                <div class="preview-link-icon-wrap">
                    ${renderIconElement(block.icon)}
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold small text-truncate">${block.label || 'Link'}</div>
                    <div class="small opacity-60 text-truncate font-mono" style="font-size: 0.68rem;">${block.url}</div>
                </div>
                <i class="bi bi-chevron-right opacity-40 small"></i>
            `;
            previewLinksContainer.appendChild(a);
        });
    }

    function syncSerializedData() {
        contentBlocksJsonInput.value = JSON.stringify(currentBlocks);
    }

    window.addDefaultBlock = function() {
        const newBlockId = 'blk_' + Math.random().toString(36).substring(2, 9);
        currentBlocks.push({
            id: newBlockId,
            type: 'custom_link',
            icon: 'bi-link-45deg',
            label: 'New Link',
            url: '',
            order: currentBlocks.length + 1
        });
        renderRepeaterAndPreview();

        setTimeout(() => {
            const row = document.querySelector(`[data-id="${newBlockId}"]`);
            if (row) {
                row.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                row.style.outline = '2px solid #6320ee';
                setTimeout(() => { row.style.outline = 'none'; }, 1000);
                const inp = row.querySelector('.block-label-input');
                if (inp) {
                    inp.focus();
                    inp.select();
                }
            }
        }, 60);
    };

    const addBlockBtn = document.getElementById('btnAddBlockDropdown');
    const addBlockMenu = document.getElementById('addBlockDropdownMenu');
    if (addBlockBtn && addBlockMenu) {
        addBlockBtn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            addBlockMenu.classList.toggle('show');
        });
        document.addEventListener('click', function(e) {
            if (!addBlockBtn.contains(e.target) && !addBlockMenu.contains(e.target)) {
                addBlockMenu.classList.remove('show');
            }
        });
    }

    document.querySelectorAll('.add-block-btn').forEach(btn => {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (addBlockMenu) addBlockMenu.classList.remove('show');

            const type = this.dataset.type;
            const icon = this.dataset.icon;
            const label = this.dataset.label;
            const placeholder = this.dataset.placeholder;

            currentBlocks.push({
                id: 'blk_' + Math.random().toString(36).substring(2, 9),
                type: type,
                icon: icon,
                label: label,
                url: placeholder,
                order: currentBlocks.length + 1
            });

            renderRepeaterAndPreview();
        });
    });

    syncLivePreviewStyles();
    syncLivePreviewText();
    renderRepeaterAndPreview();
});
</script>
@endpush
