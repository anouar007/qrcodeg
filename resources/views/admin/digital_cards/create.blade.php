@extends('layouts.admin')

@section('title', 'Créer une Carte Visite Digitale')

@push('styles')
<style>
/* Phone Simulator Styling */
.phone-mockup {
    width: 340px;
    height: 680px;
    background: #000000;
    border-radius: 44px;
    padding: 12px;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.4), 0 0 0 1px rgba(255, 255, 255, 0.1);
    position: relative;
    margin: 0 auto;
}
.phone-notch {
    position: absolute;
    top: 18px;
    left: 50%;
    transform: translateX(-50%);
    width: 120px;
    height: 24px;
    background: #000000;
    border-radius: 20px;
    z-index: 100;
}
.phone-screen {
    width: 100%;
    height: 100%;
    background: #0f172a;
    border-radius: 36px;
    overflow-y: auto;
    overflow-x: hidden;
    position: relative;
    scrollbar-width: none;
}
.phone-screen::-webkit-scrollbar { display: none; }

/* Template Selector Cards */
.template-card {
    cursor: pointer;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    padding: 12px;
    transition: all 0.2s ease;
    text-align: center;
}
.template-card:hover {
    border-color: #6366f1;
    transform: translateY(-2px);
}
.template-card.active {
    border-color: #6366f1;
    background-color: #f5f3ff;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
}
</style>
@endpush

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-1">
                <li class="breadcrumb-item"><a href="{{ route('admin.digital-cards.index') }}" class="text-decoration-none">Cartes Visite</a></li>
                <li class="breadcrumb-item active">Nouvelle Carte</li>
            </ol>
        </nav>
        <h1 class="h3 fw-bold mb-0">Créer une Carte Visite Digitale</h1>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.digital-cards.index') }}" class="btn btn-outline-secondary">Annuler</a>
        <button type="submit" form="cardForm" class="btn btn-primary d-flex align-items-center gap-2">
            <i class="bi bi-check2-circle"></i>
            <span>Enregistrer la Carte</span>
        </button>
    </div>
</div>

<form action="{{ route('admin.digital-cards.store') }}" method="POST" id="cardForm" enctype="multipart/form-data">
    @csrf

    <div class="row g-4">
        {{-- LEFT COLUMN: Customization Controls --}}
        <div class="col-lg-7">
            <div class="accordion d-flex flex-column gap-3" id="cardAccordion">

                {{-- 1. Template Selection --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-circle p-1" style="width: 24px; height: 24px;">1</span>
                            <h5 class="fw-bold mb-0">Choisir un Template</h5>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <input type="hidden" name="template" id="templateInput" value="modern">
                        <div class="row g-3">
                            <div class="col-4">
                                <div class="template-card active" onclick="selectTemplate('modern', this)">
                                    <div class="rounded-3 mb-2" style="height: 60px; background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);"></div>
                                    <div class="fw-bold small">Modern</div>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">Minimal & Clean</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="template-card" onclick="selectTemplate('executive', this)">
                                    <div class="rounded-3 mb-2" style="height: 60px; background: linear-gradient(135deg, #09090b 0%, #27272a 100%); border: 1px solid #d4af37;"></div>
                                    <div class="fw-bold small">Executive</div>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">Luxury & Gold</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="template-card" onclick="selectTemplate('creative', this)">
                                    <div class="rounded-3 mb-2" style="height: 60px; background: linear-gradient(135deg, #4f46e5 0%, #ec4899 100%);"></div>
                                    <div class="fw-bold small">Creative</div>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">Vibrant Pop</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="template-card" onclick="selectTemplate('cyberpunk', this)">
                                    <div class="rounded-3 mb-2" style="height: 60px; background: linear-gradient(135deg, #050505 0%, #172554 100%); border: 1px solid #06b6d4;"></div>
                                    <div class="fw-bold small">Cyberpunk</div>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">Neon Glow</span>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="template-card" onclick="selectTemplate('glassmorphism', this)">
                                    <div class="rounded-3 mb-2" style="height: 60px; background: radial-gradient(circle at 50% 50%, #3b82f6 0%, #1e1b4b 100%);"></div>
                                    <div class="fw-bold small">Glass</div>
                                    <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">Frost Glass</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 2. Basic Information --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-circle p-1" style="width: 24px; height: 24px;">2</span>
                            <h5 class="fw-bold mb-0">Informations du Titulaire</h5>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-7">
                                <label class="form-label small fw-semibold">Nom de Référence de la Carte <span class="text-danger">*</span></label>
                                <input type="text" name="name" id="inputName" class="form-control" placeholder="ex. Carte Commerciale Youssef" value="{{ old('name') }}" required>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label small fw-semibold">Client Associé</label>
                                <select name="client_id" class="form-select">
                                    <option value="">Sélectionner un client (optionnel)</option>
                                    @foreach($clients as $c)
                                        <option value="{{ $c->id }}" {{ old('client_id') == $c->id ? 'selected' : '' }}>
                                            {{ $c->company_name }} ({{ $c->contact_name }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Prénom & Nom complet</label>
                                <input type="text" name="full_name" id="inputFullName" class="form-control" placeholder="ex. Karim Benani" value="{{ old('full_name') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Titre / Poste</label>
                                <input type="text" name="job_title" id="inputJobTitle" class="form-control" placeholder="ex. Directeur Général" value="{{ old('job_title') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Entreprise / Projet</label>
                                <input type="text" name="company" id="inputCompany" class="form-control" placeholder="ex. Alpha Holding" value="{{ old('company') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Badge / Tagline</label>
                                <input type="text" name="badge_text" id="inputBadgeText" class="form-control" placeholder="ex. ★ Membre Vérifié" value="{{ old('badge_text') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-semibold">Bio / Présentation</label>
                                <textarea name="bio" id="inputBio" rows="3" class="form-control" placeholder="Quelques mots sur vos activités ou expertises...">{{ old('bio') }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 3. Images: Profile & Cover Banner --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-circle p-1" style="width: 24px; height: 24px;">3</span>
                            <h5 class="fw-bold mb-0">Images (Photo de Profil & Bannière)</h5>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Photo de Profil (Avatar)</label>
                                <input type="file" name="profile_image" id="inputProfileImg" class="form-control" accept="image/*" onchange="previewImage(this, 'previewProfileAvatar')">
                                <div class="form-text">Format carré recommandé (PNG, JPG, WebP max 4Mo)</div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Image de Couverture (Bannière)</label>
                                <input type="file" name="cover_image" id="inputCoverImg" class="form-control" accept="image/*" onchange="previewImage(this, 'previewCoverBanner')">
                                <div class="form-text">Bannière supérieure (PNG, JPG max 6Mo)</div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 4. Links & Contact Buttons --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom-0 d-flex align-items-center justify-content-between">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-circle p-1" style="width: 24px; height: 24px;">4</span>
                            <h5 class="fw-bold mb-0">Liens & Coordonnées Connectées</h5>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="addLinkRow()">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter un Lien
                        </button>
                    </div>
                    <div class="card-body pt-0">
                        <p class="small text-muted mb-3">Ajoutez des boutons d'action instantanés (Appel, WhatsApp, Email, Réseaux sociaux, Site web, etc.).</p>
                        <div id="linksContainer" class="d-flex flex-column gap-2">
                            {{-- Sample default links --}}
                            <div class="link-item card p-2 bg-light border">
                                <div class="row g-2 align-items-center">
                                    <div class="col-md-3">
                                        <select name="links[0][type]" class="form-select form-select-sm link-type-select" onchange="handleTypeChange(this)">
                                            <option value="phone">📞 Téléphone</option>
                                            <option value="whatsapp" selected>💬 WhatsApp</option>
                                            <option value="email">✉️ Email</option>
                                            <option value="website">🌐 Site Web</option>
                                            <option value="linkedin">💼 LinkedIn</option>
                                            <option value="instagram">📸 Instagram</option>
                                            <option value="facebook">👍 Facebook</option>
                                            <option value="tiktok">🎵 TikTok</option>
                                            <option value="address">📍 Adresse</option>
                                            <option value="custom">🔗 Autre Lien</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="links[0][label]" class="form-control form-control-sm link-label-input" placeholder="Libellé" value="WhatsApp Direct" oninput="updateLivePreview()">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="links[0][value]" class="form-control form-control-sm link-val-input" placeholder="+212 600-000000 ou https://..." value="+212600000000" oninput="updateLivePreview()">
                                    </div>
                                    <div class="col-md-1 text-end">
                                        <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeLinkRow(this)"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. Layout & Position Settings --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-circle p-1" style="width: 24px; height: 24px;">5</span>
                            <h5 class="fw-bold mb-0">Mise en Page & Positions</h5>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Alignement Photo</label>
                                <select name="layout_settings[avatar_position]" id="avatarPosSelect" class="form-select" onchange="updateLivePreview()">
                                    <option value="center" selected>Centre</option>
                                    <option value="left">Gauche</option>
                                    <option value="right">Droite</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Forme Avatar</label>
                                <select name="layout_settings[avatar_style]" id="avatarStyleSelect" class="form-select" onchange="updateLivePreview()">
                                    <option value="circle" selected>Cercle (Rond)</option>
                                    <option value="rounded">Coins arrondis</option>
                                    <option value="square">Carré</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Disposition Boutons</label>
                                <select name="layout_settings[buttons_layout]" id="btnLayoutSelect" class="form-select" onchange="updateLivePreview()">
                                    <option value="list" selected>Liste pleine largeur</option>
                                    <option value="grid">Grille 2 colonnes</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="layout_settings[show_vcard_button]" value="1" id="vcardBtnCheck" checked onchange="updateLivePreview()">
                                    <label class="form-check-label fw-semibold small" for="vcardBtnCheck">
                                        Afficher le bouton "Enregistrer dans mes contacts (vCard / .vcf)"
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 6. Colors, Fonts & Styles --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-header bg-white py-3 border-bottom-0">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white rounded-circle p-1" style="width: 24px; height: 24px;">6</span>
                            <h5 class="fw-bold mb-0">Couleurs, Typographie & Styles</h5>
                        </div>
                    </div>
                    <div class="card-body pt-0">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Couleur d'Arrière-Plan</label>
                                <input type="color" name="style_settings[background_color]" id="bgColorInput" class="form-control form-control-color w-100" value="#0f172a" oninput="updateLivePreview()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Couleur Primaire / Accent</label>
                                <input type="color" name="style_settings[primary_color]" id="primaryColorInput" class="form-control form-control-color w-100" value="#6366f1" oninput="updateLivePreview()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Fond de Carte</label>
                                <input type="color" name="style_settings[card_background]" id="cardBgInput" class="form-control form-control-color w-100" value="#ffffff" oninput="updateLivePreview()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Couleur Texte Principal</label>
                                <input type="color" name="style_settings[text_primary_color]" id="textPrimaryColorInput" class="form-control form-control-color w-100" value="#0f172a" oninput="updateLivePreview()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Couleur Texte Secondaire</label>
                                <input type="color" name="style_settings[text_secondary_color]" id="textSecondaryColorInput" class="form-control form-control-color w-100" value="#64748b" oninput="updateLivePreview()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small fw-semibold">Police de Caractères</label>
                                <select name="style_settings[font_family]" id="fontFamilySelect" class="form-select" onchange="updateLivePreview()">
                                    <option value="Outfit" selected>Outfit (Moderne & Premium)</option>
                                    <option value="Inter">Inter (Clean & Tech)</option>
                                    <option value="Roboto">Roboto (Classique)</option>
                                    <option value="Montserrat">Montserrat (Élégant)</option>
                                    <option value="Space Grotesk">Space Grotesk (Futuriste)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Rayon des Bordures</label>
                                <select name="style_settings[border_radius]" id="borderRadiusSelect" class="form-select" onchange="updateLivePreview()">
                                    <option value="8px">8px (Discret)</option>
                                    <option value="16px" selected>16px (Arrondi doux)</option>
                                    <option value="24px">24px (Très arrondi)</option>
                                    <option value="32px">32px (Pilule)</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Style d'Ombre (Shadow)</label>
                                <select name="style_settings[shadow_style]" id="shadowSelect" class="form-select" onchange="updateLivePreview()">
                                    <option value="soft" selected>Ombre Douce (Soft)</option>
                                    <option value="deep">Ombre Profonde (Deep)</option>
                                    <option value="glow">Effet Lueur (Glow)</option>
                                    <option value="flat">Plat sans ombre (Flat)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status --}}
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h6 class="fw-bold mb-1">Statut de la Carte Visite</h6>
                                <p class="small text-muted mb-0">Activez ou désactivez l'accès en direct au lien unique.</p>
                            </div>
                            <select name="status" class="form-select w-auto">
                                <option value="active" selected>Actif (Accessible)</option>
                                <option value="inactive">Inactif (Désactivé)</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        {{-- RIGHT COLUMN: Sticky Smartphone Simulator Live Preview --}}
        <div class="col-lg-5">
            <div class="sticky-top" style="top: 20px; z-index: 10;">
                <div class="text-center mb-2">
                    <span class="badge bg-dark px-3 py-1 text-uppercase tracking-wider" style="font-size: 0.7rem;">
                        <i class="bi bi-phone me-1"></i>Live Preview Smartphone
                    </span>
                </div>

                <div class="phone-mockup">
                    <div class="phone-notch"></div>
                    <div class="phone-screen" id="previewScreen">
                        {{-- Cover Banner --}}
                        <div id="previewCover" style="height: 120px; background: linear-gradient(135deg, #4f46e5 0%, #ec4899 100%); background-size: cover; background-position: center;"></div>

                        <div class="p-3" style="margin-top: -45px;">
                            {{-- Profile Avatar & Badge --}}
                            <div id="previewAvatarWrap" class="text-center mb-2">
                                <img id="previewProfileAvatar" src="https://ui-avatars.com/api/?name=Karim+Benani&background=6366f1&color=fff&size=128" alt="Profile" class="border border-3 border-white shadow" style="width: 80px; height: 80px; object-fit: cover; border-radius: 50%;">
                            </div>

                            <div class="text-center mb-2">
                                <span id="previewBadge" class="badge bg-primary-subtle text-primary mb-1" style="font-size: 0.65rem;">★ Membre Vérifié</span>
                                <h5 id="previewFullName" class="fw-bold mb-0 text-white" style="font-size: 1.15rem;">Karim Benani</h5>
                                <div id="previewJobTitle" class="small text-white-50">Directeur Général</div>
                                <div id="previewCompany" class="small fw-semibold" style="color: #6366f1;">Alpha Holding</div>
                                <p id="previewBio" class="small text-light opacity-75 mt-2 mb-3" style="font-size: 0.8rem; line-height: 1.4;">
                                    Quelques mots sur vos activités ou expertises...
                                </p>
                            </div>

                            {{-- Save vCard Button --}}
                            <div id="previewVCardWrap" class="mb-3">
                                <button type="button" class="btn btn-primary w-100 py-2 rounded-pill fw-bold d-flex align-items-center justify-content-center gap-2 shadow" id="previewVCardBtn">
                                    <i class="bi bi-person-plus-fill"></i>
                                    <span>Enregistrer le Contact</span>
                                </button>
                            </div>

                            {{-- Links List --}}
                            <div id="previewLinksList" class="d-flex flex-column gap-2">
                                <a href="#" class="btn btn-light w-100 py-2 px-3 rounded-3 d-flex align-items-center justify-content-between shadow-sm text-decoration-none">
                                    <span class="d-flex align-items-center gap-2">
                                        <i class="bi bi-whatsapp text-success fs-5"></i>
                                        <span class="fw-semibold small text-dark">WhatsApp Direct</span>
                                    </span>
                                    <i class="bi bi-chevron-right text-muted small"></i>
                                </a>
                            </div>

                            <div class="text-center mt-4 pt-3 border-top border-white-10">
                                <div class="text-white-50" style="font-size: 0.65rem;">Propulsé par QR Studio &bull; Carte Connectée</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
let linkIndex = 1;

function selectTemplate(templateName, cardEl) {
    document.querySelectorAll('.template-card').forEach(c => c.classList.remove('active'));
    cardEl.classList.add('active');
    document.getElementById('templateInput').value = templateName;

    // Apply template presets
    if (templateName === 'modern') {
        document.getElementById('bgColorInput').value = '#0f172a';
        document.getElementById('primaryColorInput').value = '#6366f1';
        document.getElementById('fontFamilySelect').value = 'Outfit';
    } else if (templateName === 'executive') {
        document.getElementById('bgColorInput').value = '#121212';
        document.getElementById('primaryColorInput').value = '#d4af37';
        document.getElementById('fontFamilySelect').value = 'Montserrat';
    } else if (templateName === 'creative') {
        document.getElementById('bgColorInput').value = '#18181b';
        document.getElementById('primaryColorInput').value = '#ec4899';
        document.getElementById('fontFamilySelect').value = 'Outfit';
    } else if (templateName === 'cyberpunk') {
        document.getElementById('bgColorInput').value = '#050505';
        document.getElementById('primaryColorInput').value = '#06b6d4';
        document.getElementById('fontFamilySelect').value = 'Space Grotesk';
    } else if (templateName === 'glassmorphism') {
        document.getElementById('bgColorInput').value = '#1e1b4b';
        document.getElementById('primaryColorInput').value = '#38bdf8';
        document.getElementById('fontFamilySelect').value = 'Inter';
    }

    updateLivePreview();
}

function handleTypeChange(selectEl) {
    const row = selectEl.closest('.link-item');
    const labelInput = row.querySelector('.link-label-input');
    const valInput = row.querySelector('.link-val-input');
    const type = selectEl.value;

    const defaults = {
        'phone': { label: 'Appeler', placeholder: '+212 600-000000' },
        'whatsapp': { label: 'WhatsApp Direct', placeholder: '+212 600-000000' },
        'email': { label: 'Envoyer un Email', placeholder: 'contact@exemple.com' },
        'website': { label: 'Site Web Officiel', placeholder: 'https://votre-site.com' },
        'linkedin': { label: 'Profil LinkedIn', placeholder: 'https://linkedin.com/in/...' },
        'instagram': { label: 'Instagram', placeholder: 'https://instagram.com/...' },
        'facebook': { label: 'Facebook', placeholder: 'https://facebook.com/...' },
        'tiktok': { label: 'TikTok', placeholder: 'https://tiktok.com/@...' },
        'address': { label: 'Localisation Google Maps', placeholder: 'Casablanca, Maroc' },
        'custom': { label: 'Mon Lien', placeholder: 'https://...' }
    };

    if (defaults[type]) {
        labelInput.value = defaults[type].label;
        valInput.placeholder = defaults[type].placeholder;
    }
    updateLivePreview();
}

function addLinkRow() {
    const container = document.getElementById('linksContainer');
    const row = document.createElement('div');
    row.className = 'link-item card p-2 bg-light border';
    row.innerHTML = `
        <div class="row g-2 align-items-center">
            <div class="col-md-3">
                <select name="links[${linkIndex}][type]" class="form-select form-select-sm link-type-select" onchange="handleTypeChange(this)">
                    <option value="phone">📞 Téléphone</option>
                    <option value="whatsapp">💬 WhatsApp</option>
                    <option value="email">✉️ Email</option>
                    <option value="website" selected>🌐 Site Web</option>
                    <option value="linkedin">💼 LinkedIn</option>
                    <option value="instagram">📸 Instagram</option>
                    <option value="facebook">👍 Facebook</option>
                    <option value="tiktok">🎵 TikTok</option>
                    <option value="address">📍 Adresse</option>
                    <option value="custom">🔗 Autre Lien</option>
                </select>
            </div>
            <div class="col-md-3">
                <input type="text" name="links[${linkIndex}][label]" class="form-control form-control-sm link-label-input" placeholder="Libellé" value="Site Web" oninput="updateLivePreview()">
            </div>
            <div class="col-md-5">
                <input type="text" name="links[${linkIndex}][value]" class="form-control form-control-sm link-val-input" placeholder="https://..." value="https://exemple.com" oninput="updateLivePreview()">
            </div>
            <div class="col-md-1 text-end">
                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="removeLinkRow(this)"><i class="bi bi-trash"></i></button>
            </div>
        </div>
    `;
    container.appendChild(row);
    linkIndex++;
    updateLivePreview();
}

function removeLinkRow(btn) {
    btn.closest('.link-item').remove();
    updateLivePreview();
}

function previewImage(input, targetImgId) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            if (targetImgId === 'previewCoverBanner') {
                document.getElementById('previewCover').style.backgroundImage = `url('${e.target.result}')`;
            } else {
                document.getElementById(targetImgId).src = e.target.result;
            }
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function updateLivePreview() {
    // 1. Text values
    const fullName = document.getElementById('inputFullName').value.trim() || 'Karim Benani';
    const jobTitle = document.getElementById('inputJobTitle').value.trim() || 'Directeur Général';
    const company = document.getElementById('inputCompany').value.trim() || 'Alpha Holding';
    const badge = document.getElementById('inputBadgeText').value.trim();
    const bio = document.getElementById('inputBio').value.trim() || 'Quelques mots sur vos activités ou expertises...';

    document.getElementById('previewFullName').textContent = fullName;
    document.getElementById('previewJobTitle').textContent = jobTitle;
    document.getElementById('previewCompany').textContent = company;
    document.getElementById('previewBio').textContent = bio;

    const badgeEl = document.getElementById('previewBadge');
    if (badge) {
        badgeEl.textContent = badge;
        badgeEl.style.display = 'inline-block';
    } else {
        badgeEl.style.display = 'none';
    }

    // 2. Colors & Styles
    const bgColor = document.getElementById('bgColorInput').value;
    const primaryColor = document.getElementById('primaryColorInput').value;
    const font = document.getElementById('fontFamilySelect').value;
    const borderRadius = document.getElementById('borderRadiusSelect').value;

    const screen = document.getElementById('previewScreen');
    screen.style.backgroundColor = bgColor;
    screen.style.fontFamily = font;

    document.getElementById('previewCompany').style.color = primaryColor;
    document.getElementById('previewVCardBtn').style.backgroundColor = primaryColor;
    document.getElementById('previewVCardBtn').style.borderColor = primaryColor;

    // 3. Avatar positioning & shape
    const avatarPos = document.getElementById('avatarPosSelect').value;
    const avatarStyle = document.getElementById('avatarStyleSelect').value;
    const avatarWrap = document.getElementById('previewAvatarWrap');
    const avatarImg = document.getElementById('previewProfileAvatar');

    avatarWrap.className = avatarPos === 'left' ? 'text-start mb-2' : (avatarPos === 'right' ? 'text-end mb-2' : 'text-center mb-2');

    if (avatarStyle === 'circle') {
        avatarImg.style.borderRadius = '50%';
    } else if (avatarStyle === 'rounded') {
        avatarImg.style.borderRadius = '16px';
    } else {
        avatarImg.style.borderRadius = '4px';
    }

    // 4. vCard Button visibility
    const showVCard = document.getElementById('vcardBtnCheck').checked;
    document.getElementById('previewVCardWrap').style.display = showVCard ? 'block' : 'none';

    // 5. Links rendering
    const linksList = document.getElementById('previewLinksList');
    const btnLayout = document.getElementById('btnLayoutSelect').value;
    linksList.innerHTML = '';

    if (btnLayout === 'grid') {
        linksList.className = 'row g-2';
    } else {
        linksList.className = 'd-flex flex-column gap-2';
    }

    const rows = document.querySelectorAll('.link-item');
    rows.forEach(r => {
        const type = r.querySelector('.link-type-select').value;
        const label = r.querySelector('.link-label-input').value.trim() || 'Lien';
        const val = r.querySelector('.link-val-input').value.trim();

        if (!val) return;

        let iconClass = 'bi-link-45deg text-primary';
        if (type === 'phone') iconClass = 'bi-telephone-fill text-primary';
        else if (type === 'whatsapp') iconClass = 'bi-whatsapp text-success';
        else if (type === 'email') iconClass = 'bi-envelope-fill text-danger';
        else if (type === 'website') iconClass = 'bi-globe text-info';
        else if (type === 'linkedin') iconClass = 'bi-linkedin text-primary';
        else if (type === 'instagram') iconClass = 'bi-instagram text-danger';
        else if (type === 'facebook') iconClass = 'bi-facebook text-primary';
        else if (type === 'tiktok') iconClass = 'bi-tiktok text-dark';
        else if (type === 'address') iconClass = 'bi-geo-alt-fill text-warning';

        const linkEl = document.createElement('div');
        if (btnLayout === 'grid') {
            linkEl.className = 'col-6';
            linkEl.innerHTML = `
                <div class="card p-2 text-center bg-white shadow-sm border-0" style="border-radius: ${borderRadius};">
                    <i class="bi ${iconClass} fs-4 mb-1"></i>
                    <div class="fw-bold small text-dark text-truncate">${label}</div>
                </div>
            `;
        } else {
            linkEl.innerHTML = `
                <div class="card p-2 px-3 bg-white shadow-sm border-0 d-flex flex-row align-items-center justify-content-between" style="border-radius: ${borderRadius};">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi ${iconClass} fs-5"></i>
                        <span class="fw-bold small text-dark">${label}</span>
                    </div>
                    <i class="bi bi-chevron-right text-muted small"></i>
                </div>
            `;
        }
        linksList.appendChild(linkEl);
    });
}

// Initial setup
document.addEventListener('DOMContentLoaded', function() {
    updateLivePreview();
});
</script>
@endpush
@endsection
