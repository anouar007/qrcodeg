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
            <a href="{{ route('admin.digital-cards.menu.edit', $digitalCard) }}" class="btn btn-outline-warning text-dark fw-semibold" style="border-radius: 8px; font-size: 0.9rem; padding: 0.55rem 1.25rem;">
                <i class="fa-solid fa-utensils text-warning me-1"></i>Studio Menu & Catalogue
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
    <input type="hidden" name="menu_config" id="menu_config_json">

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
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="menu" data-icon="fa-solid fa-utensils" data-label="Menu / Catalogue" data-placeholder="#menu"><i class="fa-solid fa-utensils me-2 text-warning"></i> Menu Restaurant / Marque</a></li>
                                            <li><a class="dropdown-item add-block-btn" href="javascript:void(0)" data-type="contact" data-icon="bi-geo-alt-fill" data-label="Office Location" data-placeholder="Casablanca Marina"><i class="bi bi-geo-alt me-2 text-warning"></i> Map Location</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div id="blocksContainer" class="d-flex flex-column gap-2 mb-3"></div>
                        </div>
                    </div>
                </div>

                <!-- 6. Interactive Restaurant / Brand Menu Builder (menu_config) -->
                <div class="accordion-item border-0 mb-3 rounded-3 overflow-hidden shadow-xs" id="sec-menu">
                    <h2 class="accordion-header" id="headingMenu">
                        <button class="accordion-button collapsed fw-bold py-3 px-4 bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapseMenu" aria-controls="collapseMenu">
                            <i class="fa-solid fa-utensils me-2 text-warning fs-5"></i> 6. Menu Interactif & Catalogue (Restaurant / Marque)
                        </button>
                    </h2>
                    <div id="collapseMenu" class="accordion-collapse collapse" aria-labelledby="headingMenu">
                        <div class="accordion-body bg-light-subtle p-4 border-top">
                            <div class="d-flex flex-wrap align-items-center justify-content-between gap-2 pb-3 mb-3 border-bottom">
                                <div>
                                    <h6 class="fw-bold mb-0.5 text-dark">Menu / Catalogue Navigable</h6>
                                    <small class="text-muted">Permet au client d'ouvrir votre menu complet depuis la carte digitale (`/c/{{ $digitalCard->slug }}/menu`).</small>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <a href="{{ route('digital-cards.public.menu', $digitalCard->slug) }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill px-3 small">
                                        <i class="bi bi-box-arrow-up-right me-1"></i> Voir `/c/{{ $digitalCard->slug }}/menu`
                                    </a>
                                    <div class="form-check form-switch m-0 d-flex align-items-center gap-2 bg-white px-3 py-1.5 rounded-pill border">
                                        <input class="form-check-input m-0 cursor-pointer" type="checkbox" role="switch" id="menuEnabledToggle" style="width: 2.3rem; height: 1.2rem;" onchange="toggleInteractiveMenu(this.checked)">
                                        <label class="form-check-label small fw-bold text-dark cursor-pointer" for="menuEnabledToggle" id="menuToggleStatusLabel">Activer</label>
                                    </div>
                                </div>
                            </div>

                            <div id="menuBuilderBodyWrap" style="display: none;">
                                <!-- A. General Menu Identity & CTA -->
                                <div class="row g-3 mb-3 p-3 rounded-3 bg-white border">
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold text-dark">Titre du Menu / Catalogue</label>
                                        <input type="text" id="menuTitleInput" class="form-control form-control-sm" value="{{ $currentMenu['title'] ?? 'Notre Menu & Catalogue' }}" oninput="syncMenuSettings()">
                                    </div>
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold text-dark">Texte du Bouton sur la Carte</label>
                                        <input type="text" id="menuButtonLabelInput" class="form-control form-control-sm" value="{{ $currentMenu['button_label'] ?? 'Consulter notre Menu / Catalogue' }}" oninput="syncMenuSettings()">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-dark">Devise</label>
                                        <select id="menuCurrencySelect" class="form-select form-select-sm font-mono fw-bold" onchange="syncMenuSettings()">
                                            @foreach(['MAD', 'EUR', 'USD', 'GBP', 'CAD', 'CHF', 'AED'] as $curr)
                                                <option value="{{ $curr }}" {{ ($currentMenu['currency'] ?? 'MAD') === $curr ? 'selected' : '' }}>{{ $curr }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label small fw-semibold text-dark">Sous-titre d'accueil</label>
                                        <input type="text" id="menuSubtitleInput" class="form-control form-control-sm" value="{{ $currentMenu['subtitle'] ?? '' }}" oninput="syncMenuSettings()">
                                    </div>
                                </div>

                                <!-- B. Mode de Navigation & Design du Menu -->
                                <div class="row g-3 mb-3 p-3 rounded-3 bg-white border">
                                    <div class="col-12">
                                        <label class="form-label small fw-bold text-dark mb-2">
                                            <i class="fa-solid fa-compass text-primary me-1"></i>Mode de Navigation du Client (Vertical / Horizontal / Pages Catalogue)
                                        </label>
                                        @php
                                            $currNavMode = $currentMenu['navigation_mode'] ?? 'vertical';
                                        @endphp
                                        <div class="row g-2" id="menuNavModeCardsWrap">
                                            <div class="col-md-4">
                                                <div class="menu-nav-option-card {{ $currNavMode === 'vertical' ? 'active' : '' }} p-2.5 rounded-3 border cursor-pointer h-100" data-nav-mode="vertical" onclick="selectMenuNavigationMode('vertical', this)">
                                                    <div class="fw-bold text-dark small mb-0.5"><i class="fa-solid fa-arrows-up-down text-primary me-1"></i>1. Défilement Vertical</div>
                                                    <div class="text-muted" style="font-size: 0.7rem;">Catégories empilées de haut en bas avec filtres rapides.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="menu-nav-option-card {{ $currNavMode === 'horizontal' ? 'active' : '' }} p-2.5 rounded-3 border cursor-pointer h-100" data-nav-mode="horizontal" onclick="selectMenuNavigationMode('horizontal', this)">
                                                    <div class="fw-bold text-dark small mb-0.5"><i class="fa-solid fa-arrows-left-right text-warning me-1"></i>2. Onglets Horizontaux</div>
                                                    <div class="text-muted" style="font-size: 0.7rem;">Navigation horizontale catégorie par catégorie.</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="menu-nav-option-card {{ $currNavMode === 'catalog' ? 'active' : '' }} p-2.5 rounded-3 border cursor-pointer h-100" data-nav-mode="catalog" onclick="selectMenuNavigationMode('catalog', this)">
                                                    <div class="fw-bold text-dark small mb-0.5"><i class="fa-solid fa-book-open text-success me-1"></i>3. Pages Type Catalogue</div>
                                                    <div class="text-muted" style="font-size: 0.7rem;">Livre interactif page par page (Précédent / Suivant).</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold text-dark">Disposition des Articles</label>
                                        <select id="menuItemLayoutSelect" class="form-select form-select-sm" onchange="syncMenuSettings()">
                                            @php $currLayout = $currentMenu['item_layout'] ?? 'grid'; @endphp
                                            <option value="grid" {{ $currLayout === 'grid' ? 'selected' : '' }}>Grille de Cartes (2 cartes par ligne - Recommandé)</option>
                                            <option value="list" {{ $currLayout === 'list' ? 'selected' : '' }}>Liste Détaillée (Restaurant)</option>
                                            <option value="compact" {{ $currLayout === 'compact' ? 'selected' : '' }}>Liste Compacte (Tarifs)</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold text-dark">Style & Thème du Menu</label>
                                        <select id="menuDesignPresetSelect" class="form-select form-select-sm" onchange="syncMenuSettings()">
                                            @php $currPreset = $currentMenu['design_preset'] ?? 'card_theme'; @endphp
                                            <option value="card_theme" {{ $currPreset === 'card_theme' ? 'selected' : '' }}>Identique à la Carte Visite</option>
                                            <option value="bistro_dark" {{ $currPreset === 'bistro_dark' ? 'selected' : '' }}>Bistro Gourmet (Noir & Cuivre)</option>
                                            <option value="clean_light" {{ $currPreset === 'clean_light' ? 'selected' : '' }}>Catalogue Clair (Fond Blanc)</option>
                                            <option value="luxury_gold" {{ $currPreset === 'luxury_gold' ? 'selected' : '' }}>Prestige & Luxe (Noir & Or)</option>
                                            <option value="custom" {{ $currPreset === 'custom' ? 'selected' : '' }}>Couleurs Personnalisées</option>
                                        </select>
                                    </div>

                                    <div class="col-md-3" id="menuCustomAccentWrap">
                                        <label class="form-label small fw-semibold text-dark">Couleur d'Accent du Menu</label>
                                        <div class="d-flex align-items-center gap-2">
                                            <input type="color" id="menuAccentColorPicker" class="form-control form-control-color" style="width: 44px; height: 34px; padding: 2px;" value="{{ $currentMenu['accent_color'] ?? '#ea580c' }}" oninput="syncMenuSettings()">
                                            <span class="small font-mono text-muted" style="font-size: 0.72rem;">Boutons, prix & onglets</span>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold text-dark">Options d'Affichage</label>
                                        <div class="d-flex align-items-center gap-3 pt-1">
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input cursor-pointer" type="checkbox" id="menuShowSearchToggle" {{ ($currentMenu['show_search'] ?? true) ? 'checked' : '' }} onchange="syncMenuSettings()">
                                                <label class="form-check-label small" for="menuShowSearchToggle">Recherche</label>
                                            </div>
                                            <div class="form-check form-switch m-0">
                                                <input class="form-check-input cursor-pointer" type="checkbox" id="menuShowImagesToggle" {{ ($currentMenu['show_images'] ?? true) ? 'checked' : '' }} onchange="syncMenuSettings()">
                                                <label class="form-check-label small" for="menuShowImagesToggle">Photos</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6" id="menuCustomBgWrap" style="{{ ($currentMenu['design_preset'] ?? '') === 'custom' ? '' : 'display: none;' }}">
                                        <label class="form-label small fw-semibold text-dark">Couleur de Fond Personnalisée</label>
                                        <input type="color" id="menuBgColorPicker" class="form-control form-control-color w-100" value="{{ $currentMenu['bg_color'] ?? '#0f172a' }}" oninput="syncMenuSettings()">
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="small fw-bold text-dark"><i class="fa-solid fa-layer-group text-primary me-1"></i>Catégories & Articles</span>
                                    <div class="d-flex align-items-center gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="openSimulatorMenuDrawer()">
                                            <i class="fa-solid fa-mobile-screen-button me-1"></i>Tester sur le Simulateur
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-3" onclick="addMenuCategory()">
                                            <i class="fa-solid fa-folder-plus me-1 text-warning"></i> Ajouter une Catégorie
                                        </button>
                                    </div>
                                </div>

                                <div id="menuCategoriesContainer" class="d-flex flex-column gap-3"></div>
                            </div>

                            <div id="menuBuilderDisabledPlaceholder" class="text-center py-4 px-3 rounded-3 border bg-white">
                                <p class="small text-muted mb-2">Activez le commutateur ci-dessus pour afficher un bouton Menu / Catalogue interactif sur votre carte.</p>
                                <button type="button" class="btn btn-sm btn-primary rounded-pill px-3" onclick="document.getElementById('menuEnabledToggle').click()">
                                    <i class="fa-solid fa-power-off me-1"></i> Activer le Menu Interactif
                                </button>
                            </div>
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

                            <button type="button" class="btn preview-save-btn w-100 py-2 mb-2.5 rounded-pill fw-bold small shadow-sm">
                                <i class="bi bi-person-plus-fill me-1"></i> Save to Contacts (.vcf)
                            </button>

                            <!-- Live Restaurant / Brand Menu CTA Button Simulator -->
                            <div id="previewMenuCtaWrap" style="display: none;" class="mb-3 text-start">
                                <button type="button" onclick="openSimulatorMenuDrawer()" class="w-100 border-0 text-white p-2.5 d-flex align-items-center justify-content-between shadow-sm" style="background: linear-gradient(135deg, var(--card-primary) 0%, rgba(255,255,255,0.14) 100%); border-radius: var(--card-radius, 14px); cursor: pointer;">
                                    <div class="d-flex align-items-center gap-2.5 min-w-0">
                                        <div class="d-flex align-items-center justify-content-center rounded-3 flex-shrink-0" style="width: 34px; height: 34px; background: rgba(255,255,255,0.2);">
                                            <i class="fa-solid fa-utensils"></i>
                                        </div>
                                        <div class="min-w-0 text-start">
                                            <div class="fw-bold small text-truncate" id="previewMenuBtnLabel" style="font-size: 0.78rem;">Consulter le Menu / Catalogue</div>
                                            <div class="opacity-80 text-truncate font-mono" id="previewMenuSubtitle" style="font-size: 0.65rem;">Navigation interactive</div>
                                        </div>
                                    </div>
                                    <span class="badge rounded-pill bg-white text-dark fw-bold px-2 py-1" style="font-size: 0.62rem;">Ouvrir <i class="fa-solid fa-arrow-right ms-0.5"></i></span>
                                </button>
                            </div>
                        </div>

                        <div class="preview-links-container px-3 pb-4" id="previewLinksContainer"></div>

                        <div class="preview-footer text-center pb-3 small opacity-50 font-mono" style="font-size: 0.68rem;">
                            POWERED BY VP DIGITAL CARD &bull; CONNECTED NFC
                        </div>

                        <!-- In-Phone Interactive Menu Overlay Drawer -->
                        <div id="simulatorMenuDrawer" class="simulator-menu-drawer" style="display: none;">
                            <div class="sim-menu-topbar d-flex align-items-center justify-content-between px-3 py-2.5">
                                <button type="button" class="btn btn-sm text-white border border-white border-opacity-25 rounded-pill px-2.5 py-0.5 small fw-semibold" style="background: rgba(255,255,255,0.1); font-size: 0.7rem;" onclick="closeSimulatorMenuDrawer()">
                                    <i class="fa-solid fa-arrow-left me-1"></i> Retour
                                </button>
                                <span class="badge rounded-pill px-2.5 py-1 font-mono" style="background: rgba(255,255,255,0.12); font-size: 0.65rem;" id="simMenuCount">0 articles</span>
                            </div>
                            <div class="px-3 pt-2 pb-3">
                                <div class="p-3 rounded-4 mb-2.5 text-center" style="background: linear-gradient(135deg, rgba(255,255,255,0.12), rgba(255,255,255,0.04)); border: 1px solid rgba(255,255,255,0.12);">
                                    <div class="small fw-extrabold text-white mb-0.5" id="simMenuTitle" style="font-size: 0.95rem;">Notre Menu & Catalogue</div>
                                    <div class="text-white opacity-75" id="simMenuSub" style="font-size: 0.7rem;">Sélectionnez une catégorie</div>
                                </div>
                                <div class="d-flex gap-1.5 overflow-x-auto pb-2 mb-2" id="simMenuTabs" style="scrollbar-width: none;"></div>
                                <div class="d-flex flex-column gap-2" id="simMenuItems"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
</div>
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
.simulator-menu-drawer {
    position: absolute;
    inset: 0;
    top: 32px;
    z-index: 90;
    background: var(--card-bg, #0f172a);
    color: #ffffff;
    overflow-y: auto;
    scrollbar-width: none;
}
.simulator-menu-drawer::-webkit-scrollbar { display: none; }
.sim-menu-topbar {
    position: sticky;
    top: 0;
    z-index: 10;
    background: rgba(15, 23, 42, 0.9);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.sim-menu-tab-btn {
    border: 1px solid rgba(255, 255, 255, 0.16);
    background: rgba(255, 255, 255, 0.06);
    color: rgba(255, 255, 255, 0.75);
    border-radius: 999px;
    padding: 0.28rem 0.7rem;
    font-size: 0.68rem;
    font-weight: 700;
    white-space: nowrap;
    cursor: pointer;
}
.sim-menu-tab-btn.active {
    background: var(--card-primary, #4f46e5);
    border-color: var(--card-primary, #4f46e5);
    color: #ffffff;
}
.sim-menu-item-card {
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(255, 255, 255, 0.12);
    border-radius: var(--card-radius, 12px);
    padding: 0.65rem 0.75rem;
}
.menu-nav-option-card {
    background: #ffffff;
    border-color: #e2e8f0 !important;
    transition: all 0.18s ease;
}
.menu-nav-option-card.active {
    border-color: #4f46e5 !important;
    background: #f5f3ff !important;
    box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.2);
}

/* Fractional Spacing & Pro Utility System */
.gap-1\.5 { gap: 0.375rem !important; }
.gap-2\.5 { gap: 0.625rem !important; }
.gap-3\.5 { gap: 0.875rem !important; }
.g-2\.5, .gx-2\.5 { --bs-gutter-x: 0.625rem !important; }
.g-2\.5, .gy-2\.5 { --bs-gutter-y: 0.625rem !important; }
.g-3\.5, .gx-3\.5 { --bs-gutter-x: 0.875rem !important; }
.g-3\.5, .gy-3\.5 { --bs-gutter-y: 0.875rem !important; }
.p-0\.5 { padding: 0.125rem !important; }
.p-1\.5 { padding: 0.375rem !important; }
.p-2\.5 { padding: 0.625rem !important; }
.p-3\.5 { padding: 0.875rem !important; }
.px-1\.5 { padding-left: 0.375rem !important; padding-right: 0.375rem !important; }
.px-2\.5 { padding-left: 0.625rem !important; padding-right: 0.625rem !important; }
.px-3\.5 { padding-left: 0.875rem !important; padding-right: 0.875rem !important; }
.py-0\.5 { padding-top: 0.125rem !important; padding-bottom: 0.125rem !important; }
.py-1\.5 { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
.py-2\.5 { padding-top: 0.625rem !important; padding-bottom: 0.625rem !important; }
.m-1\.5 { margin: 0.375rem !important; }
.mb-0\.5 { margin-bottom: 0.125rem !important; }
.mb-1\.5 { margin-bottom: 0.375rem !important; }
.mb-2\.5 { margin-bottom: 0.625rem !important; }
.mb-3\.5 { margin-bottom: 0.875rem !important; }
.mt-0\.5 { margin-top: 0.125rem !important; }
.mt-1\.5 { margin-top: 0.375rem !important; }
.mt-2\.5 { margin-top: 0.625rem !important; }
.mt-3\.5 { margin-top: 0.875rem !important; }
.me-0\.5 { margin-right: 0.125rem !important; }
.me-1\.5 { margin-right: 0.375rem !important; }
.ms-0\.5 { margin-left: 0.125rem !important; }
.ms-1\.5 { margin-left: 0.375rem !important; }
.ms-2\.5 { margin-left: 0.625rem !important; }
.font-mono { font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace !important; }
.fw-extrabold { font-weight: 800 !important; }
.min-w-0 { min-width: 0 !important; }
.border-dashed { border-style: dashed !important; }
.opacity-40 { opacity: 0.4 !important; }
.opacity-60 { opacity: 0.6 !important; }
.opacity-70 { opacity: 0.7 !important; }
.opacity-80 { opacity: 0.8 !important; }
.opacity-90 { opacity: 0.9 !important; }
.btn-xs {
    padding: 0.22rem 0.55rem;
    font-size: 0.72rem;
    line-height: 1.3;
    border-radius: 6px;
}
.btn-white {
    background-color: #ffffff;
    color: #334155;
}
.btn-white:hover {
    background-color: #f8fafc;
    color: #0f172a;
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
            const isMenuLink = (block.type === 'menu' || block.url === '#menu' || String(block.url).endsWith('/menu'));
            a.addEventListener('click', function(e) {
                e.preventDefault();
                if (isMenuLink) {
                    openSimulatorMenuDrawer();
                }
            });
            a.innerHTML = `
                <div class="preview-link-icon-wrap">
                    ${renderIconElement(block.icon)}
                </div>
                <div class="flex-grow-1 min-w-0">
                    <div class="fw-semibold small text-truncate">${block.label || 'Link'}</div>
                    <div class="small opacity-60 text-truncate font-mono" style="font-size: 0.68rem;">${isMenuLink ? 'Ouvrir le Menu Interactif' : block.url}</div>
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

            if (type === 'menu') {
                window.toggleInteractiveMenu(true);
            }

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

    // Interactive Menu Builder & Simulator Sync
    let currentMenu = @json($currentMenu);
    const menuConfigJsonInput = document.getElementById('menu_config_json');
    const menuCategoriesContainer = document.getElementById('menuCategoriesContainer');
    let activeSimMenuCat = 'all';
    let simCatalogPage = 0;

    // Helper: Upload Image via AJAX to /admin/digital-cards/upload-image
    window.uploadMenuImageAjax = function(file, onDone, onLoading) {
        if (!file) return;
        if (onLoading) onLoading(true);
        const formData = new FormData();
        formData.append('image', file);
        const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value || '{{ csrf_token() }}';

        fetch('{{ route("admin.digital-cards.upload-image") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrf,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(r => r.json())
        .then(res => {
            if (onLoading) onLoading(false);
            if (res.success && res.url) {
                onDone(res.url);
            } else {
                alert(res.message || 'Erreur lors du téléversement de l\'image.');
            }
        })
        .catch(err => {
            if (onLoading) onLoading(false);
            console.error(err);
            alert('Erreur réseau lors du téléversement de l\'image.');
        });
    };

    window.toggleInteractiveMenu = function(isEnabled) {
        currentMenu.enabled = Boolean(isEnabled);
        const toggleEl = document.getElementById('menuEnabledToggle');
        if (toggleEl) toggleEl.checked = currentMenu.enabled;
        const bodyWrap = document.getElementById('menuBuilderBodyWrap');
        const disabledPlaceholder = document.getElementById('menuBuilderDisabledPlaceholder');
        const labelBadge = document.getElementById('menuToggleStatusLabel');
        if (bodyWrap) bodyWrap.style.display = currentMenu.enabled ? 'block' : 'none';
        if (disabledPlaceholder) disabledPlaceholder.style.display = currentMenu.enabled ? 'none' : 'block';
        if (labelBadge) {
            labelBadge.textContent = currentMenu.enabled ? 'Menu Actif' : 'Activer';
            labelBadge.className = 'form-check-label small fw-bold cursor-pointer ' + (currentMenu.enabled ? 'text-success' : 'text-dark');
        }
        syncMenuSerializedData();
    };

    window.selectMenuNavigationMode = function(mode) {
        currentMenu.navigation_mode = mode || 'vertical';
        document.querySelectorAll('#menuNavModeCardsWrap .menu-nav-option-card').forEach(el => {
            el.classList.toggle('active', el.dataset.navMode === currentMenu.navigation_mode);
        });
        if (currentMenu.navigation_mode === 'horizontal') {
            activeSimMenuCat = currentMenu.categories?.[0]?.id || 'all';
        } else if (currentMenu.navigation_mode === 'vertical') {
            activeSimMenuCat = 'all';
        }
        syncMenuSerializedData();
    };

    window.syncMenuSettings = function() {
        const titleInp = document.getElementById('menuTitleInput');
        const btnInp = document.getElementById('menuButtonLabelInput');
        const currSel = document.getElementById('menuCurrencySelect');
        const subInp = document.getElementById('menuSubtitleInput');
        const layoutSel = document.getElementById('menuItemLayoutSelect');
        const presetSel = document.getElementById('menuDesignPresetSelect');
        const searchToggle = document.getElementById('menuShowSearchToggle');
        const imagesToggle = document.getElementById('menuShowImagesToggle');
        const accentPicker = document.getElementById('menuAccentColorPicker');
        const bgPicker = document.getElementById('menuBgColorPicker');

        if (titleInp) currentMenu.title = titleInp.value;
        if (btnInp) currentMenu.button_label = btnInp.value;
        if (currSel) currentMenu.currency = currSel.value;
        if (subInp) currentMenu.subtitle = subInp.value;
        if (layoutSel) currentMenu.item_layout = layoutSel.value;
        if (presetSel) {
            const oldPreset = currentMenu.design_preset;
            currentMenu.design_preset = presetSel.value;
            if (oldPreset !== currentMenu.design_preset) {
                if (currentMenu.design_preset === 'bistro_dark') {
                    if (accentPicker) { accentPicker.value = '#ea580c'; currentMenu.accent_color = '#ea580c'; }
                    if (bgPicker) { bgPicker.value = '#0c0a09'; currentMenu.bg_color = '#0c0a09'; }
                } else if (currentMenu.design_preset === 'luxury_gold') {
                    if (accentPicker) { accentPicker.value = '#d4af37'; currentMenu.accent_color = '#d4af37'; }
                    if (bgPicker) { bgPicker.value = '#090d16'; currentMenu.bg_color = '#090d16'; }
                } else if (currentMenu.design_preset === 'clean_light') {
                    if (accentPicker) { accentPicker.value = '#4f46e5'; currentMenu.accent_color = '#4f46e5'; }
                    if (bgPicker) { bgPicker.value = '#f8fafc'; currentMenu.bg_color = '#f8fafc'; }
                } else if (currentMenu.design_preset === 'card_theme') {
                    const themeCol = (typeof currentTheme !== 'undefined' && currentTheme.primary_color) ? currentTheme.primary_color : '#4f46e5';
                    if (accentPicker) { accentPicker.value = themeCol; currentMenu.accent_color = themeCol; }
                }
            }
        }
        if (searchToggle) currentMenu.show_search = Boolean(searchToggle.checked);
        if (imagesToggle) currentMenu.show_images = Boolean(imagesToggle.checked);
        if (accentPicker) currentMenu.accent_color = accentPicker.value;
        if (bgPicker) currentMenu.bg_color = bgPicker.value;

        const isCustomDesign = (currentMenu.design_preset === 'custom');
        const bgWrap = document.getElementById('menuCustomBgWrap');
        if (bgWrap) bgWrap.style.display = isCustomDesign ? 'block' : 'none';

        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.addMenuCategory = function() {
        if (!Array.isArray(currentMenu.categories)) currentMenu.categories = [];
        currentMenu.categories.push({
            id: 'cat_' + Math.random().toString(36).substring(2, 8),
            name: 'Nouvelle Catégorie',
            icon: 'fa-solid fa-utensils',
            description: '',
            cover_image: '',
            items: [
                {
                    id: 'item_' + Math.random().toString(36).substring(2, 8),
                    name: 'Nouvel Article',
                    description: 'Description détaillée...',
                    price: '50',
                    badge: '',
                    image_url: '',
                    available: true
                }
            ]
        });
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.moveMenuCategory = function(catIndex, delta) {
        const categories = currentMenu.categories || [];
        const newIndex = catIndex + delta;
        if (newIndex < 0 || newIndex >= categories.length) return;
        const temp = categories[catIndex];
        categories[catIndex] = categories[newIndex];
        categories[newIndex] = temp;
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.removeMenuCategory = function(catId) {
        currentMenu.categories = (currentMenu.categories || []).filter(c => c.id !== catId);
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.addMenuItem = function(catId) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat) return;
        if (!Array.isArray(cat.items)) cat.items = [];
        cat.items.push({
            id: 'item_' + Math.random().toString(36).substring(2, 8),
            name: 'Nouvel Article',
            description: '',
            price: '0',
            badge: '',
            image_url: '',
            available: true
        });
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.moveMenuItem = function(catId, itemIndex, delta) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat || !Array.isArray(cat.items)) return;
        const newIndex = itemIndex + delta;
        if (newIndex < 0 || newIndex >= cat.items.length) return;
        const temp = cat.items[itemIndex];
        cat.items[itemIndex] = cat.items[newIndex];
        cat.items[newIndex] = temp;
        renderMenuCategories();
        syncMenuSerializedData();
    };

    window.removeMenuItem = function(catId, itemId) {
        const cat = (currentMenu.categories || []).find(c => c.id === catId);
        if (!cat || !Array.isArray(cat.items)) return;
        cat.items = cat.items.filter(it => it.id !== itemId);
        renderMenuCategories();
        syncMenuSerializedData();
    };

    function renderMenuCategories() {
        if (!menuCategoriesContainer) return;
        menuCategoriesContainer.innerHTML = '';
        const categories = currentMenu.categories || [];
        const currency = currentMenu.currency || 'MAD';

        categories.forEach((cat, catIdx) => {
            const box = document.createElement('div');
            box.className = 'card p-3 bg-white border rounded-3';
            const items = cat.items || [];

            let itemsHtml = '';
            items.forEach((item, itemIdx) => {
                const isAvail = item.available !== false;
                itemsHtml += `
                    <div class="p-3 bg-white rounded-3 border menu-item-row mb-2" data-item-id="${item.id}">
                        <div class="d-flex align-items-start gap-3">
                            <!-- Product Photo Box + Upload -->
                            <div class="flex-shrink-0 text-center">
                                <div class="position-relative rounded-3 border overflow-hidden d-flex align-items-center justify-content-center bg-light mb-1 item-thumb-box" style="width: 68px; height: 68px;">
                                    ${item.image_url ? `<img src="${item.image_url}" class="w-100 h-100 item-thumb-img" style="object-fit:cover;" onerror="this.style.display='none';">` : `<i class="fa-solid fa-camera text-muted fs-5 opacity-40"></i>`}
                                    <label class="position-absolute bottom-0 start-0 end-0 bg-dark bg-opacity-75 text-white text-center py-0.5 cursor-pointer m-0" style="font-size: 0.6rem;" title="Téléverser photo">
                                        <i class="fa-solid fa-cloud-arrow-up"></i>
                                        <input type="file" class="d-none menu-item-file-upload" accept="image/*">
                                    </label>
                                </div>
                                <label class="btn btn-xs btn-outline-primary rounded-pill px-2 py-0.5 cursor-pointer" style="font-size: 0.64rem;" title="Téléverser une image de produit">
                                    <i class="fa-solid fa-upload"></i> Photo
                                    <input type="file" class="d-none menu-item-file-upload-btn" accept="image/*">
                                </label>
                            </div>

                            <!-- Product Details -->
                            <div class="flex-grow-1 min-w-0">
                                <div class="row g-2 align-items-center mb-1.5">
                                    <div class="col-md-5">
                                        <label class="form-label small fw-semibold text-dark mb-0.5" style="font-size: 0.72rem;">Nom du Plat / Produit</label>
                                        <input type="text" class="form-control form-control-sm menu-item-name fw-bold" value="${(item.name || '').replace(/"/g, '&quot;')}" placeholder="Nom du plat / produit">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label small fw-semibold text-dark mb-0.5" style="font-size: 0.72rem;">Prix (${currency})</label>
                                        <input type="text" class="form-control form-control-sm font-mono menu-item-price" value="${(item.price || '').replace(/"/g, '&quot;')}" placeholder="150">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold text-dark mb-0.5" style="font-size: 0.72rem;">Badge</label>
                                        <input type="text" class="form-control form-control-sm menu-item-badge" value="${(item.badge || '').replace(/"/g, '&quot;')}" placeholder="ex: Nouveau, Populaire">
                                    </div>
                                    <div class="col-md-2 d-flex align-items-center justify-content-end gap-1 pt-3">
                                        <div class="form-check form-switch m-0 me-1" title="Disponible">
                                            <input class="form-check-input cursor-pointer menu-item-avail" type="checkbox" ${isAvail ? 'checked' : ''}>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-light border px-1.5 py-0" ${itemIdx === 0 ? 'disabled' : ''} onclick="moveMenuItem('${cat.id}', ${itemIdx}, -1)"><i class="bi bi-arrow-up"></i></button>
                                        <button type="button" class="btn btn-sm btn-light border px-1.5 py-0" ${itemIdx === items.length - 1 ? 'disabled' : ''} onclick="moveMenuItem('${cat.id}', ${itemIdx}, 1)"><i class="bi bi-arrow-down"></i></button>
                                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-0" onclick="removeMenuItem('${cat.id}', '${item.id}')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="row g-2 align-items-center">
                                    <div class="col-md-6">
                                        <input type="text" class="form-control form-control-sm menu-item-desc" value="${(item.description || '').replace(/"/g, '&quot;')}" placeholder="Description...">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <span class="input-group-text bg-white px-2"><i class="fa-solid fa-link text-muted" style="font-size: 0.68rem;"></i></span>
                                            <input type="text" class="form-control form-control-sm font-mono menu-item-img" value="${(item.image_url || '').replace(/"/g, '&quot;')}" placeholder="URL Photo ou téléversez...">
                                            ${item.image_url ? `<button type="button" class="btn btn-sm btn-outline-danger px-2 menu-item-clear-img" title="Effacer l'image"><i class="bi bi-x"></i></button>` : ''}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });

            box.innerHTML = `
                <div class="d-flex align-items-center justify-content-between gap-2 pb-2 mb-2 border-bottom">
                    <div class="d-flex align-items-center gap-2 flex-grow-1">
                        <span class="badge bg-warning-subtle text-warning border font-mono small">Page / Cat. ${catIdx + 1}</span>
                        <input type="text" class="form-control form-control-sm fw-bold menu-cat-name-input" value="${(cat.name || '').replace(/"/g, '&quot;')}" placeholder="Nom de la catégorie">
                        <input type="text" class="form-control form-control-sm font-mono menu-cat-icon-input" style="max-width: 160px;" value="${(cat.icon || 'fa-solid fa-utensils').replace(/"/g, '&quot;')}" placeholder="Icône">
                    </div>
                    <div class="d-flex align-items-center gap-1">
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0.5" ${catIdx === 0 ? 'disabled' : ''} onclick="moveMenuCategory(${catIdx}, -1)"><i class="bi bi-arrow-up"></i></button>
                        <button type="button" class="btn btn-sm btn-light border px-2 py-0.5" ${catIdx === categories.length - 1 ? 'disabled' : ''} onclick="moveMenuCategory(${catIdx}, 1)"><i class="bi bi-arrow-down"></i></button>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-2.5 py-0.5 small" onclick="addMenuItem('${cat.id}')">
                            + Article
                        </button>
                        <button type="button" class="btn btn-sm btn-outline-danger px-2 py-0.5" onclick="removeMenuCategory('${cat.id}')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                <!-- Category Cover Image with Upload -->
                <div class="p-2.5 rounded-3 bg-light bg-opacity-50 border mb-2">
                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                        <label class="form-label small fw-bold text-dark mb-0" style="font-size: 0.74rem;">
                            <i class="fa-solid fa-image text-primary me-1"></i>Image de Couverture pour cette Page / Catégorie
                        </label>
                        <span class="text-muted" style="font-size: 0.68rem;">Bannière d'en-tête (ex: 1200x500)</span>
                    </div>
                    <div class="d-flex align-items-center gap-2.5">
                        <div class="rounded-3 border overflow-hidden d-flex align-items-center justify-content-center bg-white flex-shrink-0 cat-cover-thumb-box" style="width: 100px; height: 50px;">
                            ${cat.cover_image ? `<img src="${cat.cover_image}" class="w-100 h-100" style="object-fit: cover;" onerror="this.style.display='none';">` : `<i class="fa-regular fa-image text-muted fs-4 opacity-40"></i>`}
                        </div>
                        <div class="flex-grow-1">
                            <div class="input-group input-group-sm mb-1">
                                <span class="input-group-text bg-white text-muted px-2"><i class="fa-solid fa-link" style="font-size: 0.7rem;"></i></span>
                                <input type="text" class="form-control form-control-sm font-mono menu-cat-cover-input" value="${(cat.cover_image || '').replace(/"/g, '&quot;')}" placeholder="URL image de couverture ou téléversez...">
                                ${cat.cover_image ? `<button type="button" class="btn btn-sm btn-outline-danger px-2 menu-cat-clear-cover" title="Supprimer"><i class="bi bi-trash"></i></button>` : ''}
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label class="btn btn-xs btn-outline-primary rounded-pill px-3 py-1 cursor-pointer mb-0">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i>Téléverser une Image
                                    <input type="file" class="d-none menu-cat-cover-file" accept="image/*">
                                </label>
                                <span class="text-muted" style="font-size: 0.68rem;">JPEG, PNG, WebP (max 6 Mo)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex flex-column gap-2">${itemsHtml}</div>
            `;

            const catNameInput = box.querySelector('.menu-cat-name-input');
            const catIconInput = box.querySelector('.menu-cat-icon-input');
            const catCoverInput = box.querySelector('.menu-cat-cover-input');
            const catCoverFile = box.querySelector('.menu-cat-cover-file');
            const catCoverClear = box.querySelector('.menu-cat-clear-cover');

            if (catNameInput) {
                catNameInput.addEventListener('input', e => {
                    cat.name = e.target.value;
                    syncMenuSerializedData();
                });
            }
            if (catIconInput) {
                catIconInput.addEventListener('input', e => {
                    cat.icon = e.target.value;
                    syncMenuSerializedData();
                });
            }
            if (catCoverInput) {
                catCoverInput.addEventListener('input', e => {
                    cat.cover_image = e.target.value.trim();
                    syncMenuSerializedData();
                });
            }
            if (catCoverFile) {
                catCoverFile.addEventListener('change', function() {
                    const file = this.files?.[0];
                    if (!file) return;
                    uploadMenuImageAjax(file, function(url) {
                        cat.cover_image = url;
                        renderMenuCategories();
                        syncMenuSerializedData();
                    });
                });
            }
            if (catCoverClear) {
                catCoverClear.addEventListener('click', function() {
                    cat.cover_image = '';
                    renderMenuCategories();
                    syncMenuSerializedData();
                });
            }

            box.querySelectorAll('.menu-item-row').forEach(rowEl => {
                const itemId = rowEl.dataset.itemId;
                const itemObj = (cat.items || []).find(i => i.id === itemId);
                if (!itemObj) return;

                const uploadInputs = rowEl.querySelectorAll('.menu-item-file-upload, .menu-item-file-upload-btn');
                const clearImgBtn = rowEl.querySelector('.menu-item-clear-img');

                rowEl.querySelector('.menu-item-name')?.addEventListener('input', e => { itemObj.name = e.target.value; syncMenuSerializedData(); });
                rowEl.querySelector('.menu-item-price')?.addEventListener('input', e => { itemObj.price = e.target.value; syncMenuSerializedData(); });
                rowEl.querySelector('.menu-item-badge')?.addEventListener('input', e => { itemObj.badge = e.target.value; syncMenuSerializedData(); });
                rowEl.querySelector('.menu-item-desc')?.addEventListener('input', e => { itemObj.description = e.target.value; syncMenuSerializedData(); });
                rowEl.querySelector('.menu-item-img')?.addEventListener('input', e => { itemObj.image_url = e.target.value; syncMenuSerializedData(); });
                rowEl.querySelector('.menu-item-avail')?.addEventListener('change', e => { itemObj.available = Boolean(e.target.checked); syncMenuSerializedData(); });

                if (clearImgBtn) {
                    clearImgBtn.addEventListener('click', () => {
                        itemObj.image_url = '';
                        renderMenuCategories();
                        syncMenuSerializedData();
                    });
                }

                uploadInputs.forEach(uInp => {
                    uInp.addEventListener('change', function() {
                        const file = this.files?.[0];
                        if (!file) return;
                        uploadMenuImageAjax(file, function(url) {
                            itemObj.image_url = url;
                            renderMenuCategories();
                            syncMenuSerializedData();
                        });
                    });
                });
            });

            menuCategoriesContainer.appendChild(box);
        });
    }

    function syncMenuSerializedData() {
        if (menuConfigJsonInput) {
            menuConfigJsonInput.value = JSON.stringify(currentMenu);
        }
        const isMenuVisible = Boolean(currentMenu.enabled && Array.isArray(currentMenu.categories) && currentMenu.categories.length > 0);
        const classicCta = document.getElementById('previewMenuCtaWrap');
        if (classicCta) classicCta.style.display = isMenuVisible ? 'block' : 'none';

        const accentCol = currentMenu.accent_color || (typeof currentTheme !== 'undefined' && currentTheme.primary_color) || '#ea580c';
        const cBtnLbl = document.getElementById('previewMenuBtnLabel');
        const cSubLbl = document.getElementById('previewMenuSubtitle');
        if (cBtnLbl) cBtnLbl.textContent = currentMenu.button_label || 'Consulter notre Menu / Catalogue';
        if (cSubLbl) cSubLbl.textContent = currentMenu.subtitle || `${(currentMenu.categories || []).length} catégories disponibles`;

        if (classicCta) {
            const btn = classicCta.querySelector('button');
            if (btn) {
                btn.style.background = `linear-gradient(135deg, ${accentCol} 0%, ${accentCol}dd 100%)`;
                btn.style.boxShadow = `0 4px 14px ${accentCol}44`;
            }
        }

        renderSimulatorMenuDrawerContent();
    }

    window.openSimulatorMenuDrawer = function() {
        const drawer = document.getElementById('simulatorMenuDrawer');
        if (!drawer) return;
        renderSimulatorMenuDrawerContent();
        drawer.style.display = 'block';
    };

    window.closeSimulatorMenuDrawer = function() {
        const drawer = document.getElementById('simulatorMenuDrawer');
        if (drawer) drawer.style.display = 'none';
    };

    window.filterSimulatorMenuCategory = function(catId) {
        activeSimMenuCat = catId;
        renderSimulatorMenuDrawerContent();
    };

    window.stepSimulatorCatalogPage = function(delta) {
        const categories = currentMenu.categories || [];
        if (categories.length === 0) return;
        simCatalogPage = Math.max(0, Math.min(categories.length - 1, simCatalogPage + delta));
        renderSimulatorMenuDrawerContent();
    };

    function renderSimulatorMenuDrawerContent() {
        const drawer = document.getElementById('simulatorMenuDrawer');
        const titleEl = document.getElementById('simMenuTitle');
        const subEl = document.getElementById('simMenuSub');
        const countEl = document.getElementById('simMenuCount');
        const tabsEl = document.getElementById('simMenuTabs');
        const itemsEl = document.getElementById('simMenuItems');
        if (!tabsEl || !itemsEl) return;

        const categories = currentMenu.categories || [];
        const currency = currentMenu.currency || 'MAD';
        const navMode = currentMenu.navigation_mode || 'vertical';
        const itemLayout = currentMenu.item_layout || 'grid';
        const designPreset = currentMenu.design_preset || 'card_theme';
        const accentCol = currentMenu.accent_color || (typeof currentTheme !== 'undefined' && currentTheme.primary_color) || '#ea580c';

        if (drawer) {
            if (designPreset === 'bistro_dark') {
                drawer.style.background = '#0c0a09';
                drawer.style.color = '#ffffff';
            } else if (designPreset === 'clean_light') {
                drawer.style.background = '#f8fafc';
                drawer.style.color = '#0f172a';
            } else if (designPreset === 'luxury_gold') {
                drawer.style.background = '#090d16';
                drawer.style.color = '#ffffff';
            } else if (designPreset === 'custom') {
                drawer.style.background = currentMenu.bg_color || '#0f172a';
                drawer.style.color = '#ffffff';
            } else {
                drawer.style.background = 'var(--card-bg, #0f172a)';
                drawer.style.color = '#ffffff';
            }
            drawer.style.setProperty('--card-primary', accentCol);
        }

        let totalItems = 0;
        categories.forEach(c => { totalItems += (c.items || []).length; });

        if (titleEl) titleEl.textContent = currentMenu.title || 'Notre Menu & Catalogue';
        if (subEl) subEl.textContent = currentMenu.subtitle || 'Sélectionnez une catégorie';
        if (countEl) {
            const navLabel = navMode === 'catalog' ? 'Catalogue' : (navMode === 'horizontal' ? 'Onglets' : 'Vertical');
            countEl.textContent = `${navLabel} • ${totalItems} art.`;
        }

        if (navMode === 'horizontal' && activeSimMenuCat === 'all' && categories.length > 0) {
            activeSimMenuCat = categories[0].id;
        }

        if (navMode === 'catalog') {
            const currentCat = categories[simCatalogPage] || categories[0];
            tabsEl.innerHTML = currentCat ? `
                <div class="w-100 d-flex align-items-center justify-content-between px-2 py-1 rounded-3" style="background: rgba(255,255,255,0.08); border: 1px solid rgba(255,255,255,0.14);">
                    <span class="small fw-bold" style="font-size: 0.74rem;"><i class="${currentCat.icon || 'fa-solid fa-book-open'} me-1" style="color:${accentCol};"></i>${currentCat.name}</span>
                    <span class="badge rounded-pill font-mono" style="background:${accentCol}; color:#fff; font-size: 0.62rem;">Page ${simCatalogPage + 1} / ${categories.length}</span>
                </div>
            ` : '';
        } else {
            let tabsHtml = '';
            if (navMode === 'vertical') {
                tabsHtml += `<button type="button" class="sim-menu-tab-btn ${activeSimMenuCat === 'all' ? 'active' : ''}" style="${activeSimMenuCat === 'all' ? `background:${accentCol} !important; border-color:${accentCol} !important; color:#ffffff !important; box-shadow: 0 3px 10px ${accentCol}55;` : ''}" onclick="filterSimulatorMenuCategory('all')">Tout</button>`;
            }
            categories.forEach(cat => {
                const isActive = (activeSimMenuCat === cat.id);
                tabsHtml += `<button type="button" class="sim-menu-tab-btn ${isActive ? 'active' : ''}" style="${isActive ? `background:${accentCol} !important; border-color:${accentCol} !important; color:#ffffff !important; box-shadow: 0 3px 10px ${accentCol}55;` : ''}" onclick="filterSimulatorMenuCategory('${cat.id}')">${cat.name || 'Catégorie'}</button>`;
            });
            tabsEl.innerHTML = tabsHtml;
        }

        // Render Items and Category Cover according to navigation_mode
        let itemsHtml = '';

        categories.forEach((cat, idx) => {
            if (navMode === 'catalog' && idx !== simCatalogPage) return;
            if (navMode !== 'catalog' && activeSimMenuCat !== 'all' && activeSimMenuCat !== cat.id) return;

            // Page Cover Image on Preview Simulator
            if (cat.cover_image) {
                itemsHtml += `
                    <div class="rounded-3 overflow-hidden position-relative mb-2.5 border" style="height: 110px; border-color: rgba(255,255,255,0.18) !important; box-shadow: 0 4px 14px rgba(0,0,0,0.25);">
                        <img src="${cat.cover_image}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1544025162-d76694265947?w=800&auto=format&fit=crop&q=80';">
                        <div class="position-absolute top-0 bottom-0 start-0 end-0 p-2 d-flex flex-column justify-content-between" style="background: linear-gradient(to top, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.38) 60%, rgba(0,0,0,0.12) 100%);">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge rounded-pill page-number-pill" style="background: ${accentCol}; color:#fff; font-size: 0.58rem; font-weight:700; box-shadow: 0 2px 6px ${accentCol}66;">Page ${idx + 1} / ${categories.length}</span>
                                <span class="badge rounded-pill bg-dark bg-opacity-60 text-white" style="font-size: 0.56rem;">${(cat.items || []).length} article${(cat.items || []).length > 1 ? 's' : ''}</span>
                            </div>
                            <div>
                                <div class="fw-extrabold text-white" style="font-size: 0.86rem; text-shadow: 0 1px 4px rgba(0,0,0,0.9);"><i class="${cat.icon || 'fa-solid fa-tag'} me-1.5" style="color: ${accentCol};"></i>${cat.name || 'Catégorie'}</div>
                                ${cat.description ? `<div class="text-white text-opacity-85 text-truncate" style="font-size: 0.64rem; text-shadow: 0 1px 3px rgba(0,0,0,0.9);">${cat.description}</div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                itemsHtml += `
                    <div class="rounded-3 overflow-hidden position-relative mb-2.5 border d-flex flex-column justify-content-between p-2" style="height: 72px; background: linear-gradient(135deg, rgba(79, 70, 229, 0.25) 0%, rgba(255,255,255,0.06) 100%); border-color: rgba(255,255,255,0.12) !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge rounded-pill" style="background: ${accentCol}; color:#fff; font-size: 0.56rem; font-weight:700; box-shadow: 0 2px 6px ${accentCol}66;">Page ${idx + 1} / ${categories.length}</span>
                            <span class="badge rounded-pill bg-dark bg-opacity-40 text-white font-mono" style="font-size: 0.54rem;"><i class="fa-solid fa-image me-1 opacity-70"></i>Couverture de Page</span>
                        </div>
                        <div>
                            <div class="fw-bold" style="font-size: 0.8rem;"><i class="${cat.icon || 'fa-solid fa-tag'} me-1.5" style="color: ${accentCol};"></i>${cat.name || 'Catégorie'}</div>
                            ${cat.description ? `<div class="opacity-70 text-truncate" style="font-size: 0.62rem;">${cat.description}</div>` : ''}
                        </div>
                    </div>
                `;
            }

            // Products: 2 cards on each row
            itemsHtml += '<div class="row g-2 mb-2.5">';

            (cat.items || []).forEach(item => {
                const isAvail = item.available !== false;
                const cardInner = `
                    <div class="col-6">
                        <div class="sim-menu-item-card text-start h-100 d-flex flex-column p-2 rounded-3 border" style="background: rgba(255,255,255,0.07); border-color: rgba(255,255,255,0.12) !important; ${!isAvail ? 'opacity:0.5;' : ''} ${designPreset === 'clean_light' ? 'background:#ffffff; color:#0f172a; border-color:#e2e8f0 !important;' : ''}; box-shadow: 0 2px 8px rgba(0,0,0,0.08);">
                            ${currentMenu.show_images !== false ? `
                                <div class="position-relative w-100 rounded-2 overflow-hidden mb-1.5" style="height: 68px; background: rgba(148,163,184,0.15);">
                                    ${item.image_url ? `
                                        <img src="${item.image_url}" class="w-100 h-100" style="object-fit: cover;" onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'w-100 h-100 d-flex align-items-center justify-content-center opacity-40\\'><i class=\\'fa-solid fa-camera small\\'></i></div>';">
                                    ` : `
                                        <div class="w-100 h-100 d-flex align-items-center justify-content-center opacity-40">
                                            <i class="fa-solid fa-utensils small"></i>
                                        </div>
                                    `}
                                    ${item.badge ? `<span class="badge rounded-pill position-absolute top-0 start-0 m-1" style="background: ${accentCol}; color:#fff; font-size: 0.52rem; box-shadow: 0 1px 4px rgba(0,0,0,0.3);">${item.badge}</span>` : ''}
                                    ${!isAvail ? `<span class="badge bg-danger rounded-pill position-absolute top-0 end-0 m-1" style="font-size: 0.52rem;">Épuisé</span>` : ''}
                                </div>
                            ` : (item.badge || !isAvail ? `
                                <div class="d-flex align-items-center gap-1 mb-1">
                                    ${item.badge ? `<span class="badge rounded-pill" style="background: ${accentCol}; color:#fff; font-size: 0.52rem;">${item.badge}</span>` : ''}
                                    ${!isAvail ? `<span class="badge bg-danger rounded-pill" style="font-size: 0.52rem;">Épuisé</span>` : ''}
                                </div>
                            ` : '')}
                            <div class="flex-grow-1 min-w-0">
                                <div class="fw-bold text-truncate" style="font-size: 0.76rem;" title="${(item.name || '').replace(/"/g, '&quot;')}">${item.name || 'Article'}</div>
                                ${item.description ? `<div class="opacity-70 mt-0.5" style="font-size: 0.62rem; line-height: 1.25; display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">${item.description}</div>` : ''}
                            </div>
                            ${item.price !== '' && item.price !== null ? `
                                <div class="mt-2 pt-1 border-top text-center" style="border-color: rgba(255,255,255,0.1) !important;">
                                    <span class="badge rounded-pill font-mono w-100" style="background: ${accentCol}22; color: ${accentCol}; font-size: 0.64rem; font-weight: 700; border: 1px solid ${accentCol}66; box-shadow: 0 1px 4px ${accentCol}20;">${item.price} ${currency}</span>
                                </div>
                            ` : ''}
                        </div>
                    </div>
                `;
                itemsHtml += cardInner;
            });

            itemsHtml += '</div>';
        });

        if (navMode === 'catalog' && categories.length > 0) {
            itemsHtml += `
                <div class="d-flex align-items-center justify-content-between mt-2 pt-2 border-top" style="border-color: rgba(255,255,255,0.12) !important;">
                    <button type="button" class="btn btn-sm text-white rounded-pill px-2.5 py-0.5 font-mono" style="background: rgba(255,255,255,0.12); font-size: 0.65rem;" ${simCatalogPage <= 0 ? 'disabled' : ''} onclick="stepSimulatorCatalogPage(-1)">Précédent</button>
                    <span class="font-mono small opacity-75" style="font-size: 0.65rem;">Page ${simCatalogPage + 1}/${categories.length}</span>
                    <button type="button" class="btn btn-sm text-white rounded-pill px-2.5 py-0.5 font-mono" style="background: ${accentCol}; font-size: 0.65rem; box-shadow: 0 2px 8px ${accentCol}55;" ${simCatalogPage >= categories.length - 1 ? 'disabled' : ''} onclick="stepSimulatorCatalogPage(1)">Suivant</button>
                </div>
            `;
        }

        itemsEl.innerHTML = itemsHtml || '<div class="text-center py-3 small opacity-60">Aucun article</div>';
    }

    syncLivePreviewStyles();
    syncLivePreviewText();
    renderRepeaterAndPreview();
    renderMenuCategories();
    window.toggleInteractiveMenu(Boolean(currentMenu.enabled));
});
</script>
@endpush
