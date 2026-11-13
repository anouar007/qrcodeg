@extends('layouts.client')

@section('title', 'Créer un Menu Digital')
@section('page_title', 'Créer un Menu Digital')
@section('page_subtitle', 'Concevez votre carte interactive de restaurant, fast food ou café')

@section('content')
<div class="container-fluid px-0">
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <div class="d-flex align-items-center gap-2 mb-1">
                <a href="{{ route('client.menus.index') }}" class="btn btn-sm btn-outline-secondary rounded-circle p-1" style="width: 32px; height: 32px;">
                    <i class="bi bi-arrow-left"></i>
                </a>
                <h4 class="fw-bold text-dark mb-0">Créateur de Menu</h4>
            </div>
            <p class="text-muted small mb-0 ms-5">Remplissez vos catégories et vos plats, les prix s'affichent instantanément pour vos clients.</p>
        </div>

        <div class="d-flex gap-2">
            <!-- Preset Dropdown -->
            <div class="dropdown">
                <button class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-1.5" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-magic text-primary"></i>
                    <span>Charger un Modèle</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                    <li><button class="dropdown-item py-2" type="button" onclick="loadPreset('fast_food')">🍔 Fast Food / Tacos &amp; Burgers</button></li>
                    <li><button class="dropdown-item py-2" type="button" onclick="loadPreset('restaurant')">🍽️ Restaurant Traditionnel</button></li>
                    <li><button class="dropdown-item py-2" type="button" onclick="loadPreset('cafe')">☕ Café &amp; Brunch</button></li>
                </ul>
            </div>

            <button type="button" class="btn btn-primary-custom btn-sm px-4 fw-semibold" onclick="document.getElementById('clientMenuForm').submit();">
                <i class="bi bi-check2-circle me-1"></i>Enregistrer le Menu
            </button>
        </div>
    </div>

    <form id="clientMenuForm" method="POST" action="{{ route('client.menus.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="categories_json" id="categoriesJsonInput" value="[]">

        <div class="row g-4">
            <div class="col-12 col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <h5 class="fw-bold text-dark mb-3">
                        <i class="bi bi-shop text-primary me-2"></i>Votre Établissement
                    </h5>

                    <div class="mb-3">
                        <label for="brand_name" class="form-label fw-semibold small text-dark">Nom du Restaurant / Marque <span class="text-danger">*</span></label>
                        <input type="text" name="brand_name" id="brand_name" class="form-control" value="{{ old('brand_name', $client->company_name) }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="title" class="form-label fw-semibold small text-dark">Titre de la Carte <span class="text-danger">*</span></label>
                        <input type="text" name="title" id="title" class="form-control" value="{{ old('title', 'Menu Principal') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="brand_type" class="form-label fw-semibold small text-dark">Type d'Établissement <span class="text-danger">*</span></label>
                        <select name="brand_type" id="brand_type" class="form-select" required>
                            <option value="restaurant">Restaurant Traditionnel</option>
                            <option value="fast_food">Fast Food / Street Food / Snack</option>
                            <option value="cafe">Café / Salon / Brunch</option>
                            <option value="bakery">Pâtisserie / Boulangerie</option>
                            <option value="lounge">Lounge / Cocktails</option>
                            <option value="brand">Marque / Boutique</option>
                            <option value="other">Autre</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="currency" class="form-label fw-semibold small text-dark">Devise</label>
                        <input type="text" name="currency" id="currency" class="form-control" value="{{ old('currency', 'MAD') }}" required>
                    </div>

                    <div class="p-3 border rounded-3 bg-light-subtle mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <label class="form-label fw-bold small text-dark mb-0">
                                <i class="bi bi-palette text-primary me-1"></i>Couleurs des Éléments (Plats &amp; Services Table)
                            </label>
                            <span class="badge text-white px-2 py-0.5 rounded-pill" style="background: #25D366; font-size: 0.65rem;">WhatsApp Vert Officiel</span>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <label for="item_bg_color" class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Fond des Plats</label>
                                <input type="color" name="item_bg_color" id="item_bg_color" class="form-control form-control-color w-100" value="{{ old('item_bg_color', '#ffffff') }}">
                            </div>
                            <div class="col-6">
                                <label for="item_text_color" class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Texte des Plats</label>
                                <input type="color" name="item_text_color" id="item_text_color" class="form-control form-control-color w-100" value="{{ old('item_text_color', '#1e293b') }}">
                            </div>
                            <div class="col-6">
                                <label for="service_bg_color" class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Fond Services Table</label>
                                <input type="color" name="service_bg_color" id="service_bg_color" class="form-control form-control-color w-100" value="{{ old('service_bg_color', '#ffffff') }}">
                            </div>
                            <div class="col-6">
                                <label for="service_text_color" class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Texte Services Table</label>
                                <input type="color" name="service_text_color" id="service_text_color" class="form-control form-control-color w-100" value="{{ old('service_text_color', '#1e293b') }}">
                            </div>
                            <div class="col-6">
                                <label for="theme_color" class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Couleur Accent &amp; Prix</label>
                                <input type="color" name="theme_color" id="theme_color" class="form-control form-control-color w-100" value="{{ old('theme_color', '#5736e9') }}">
                            </div>
                            <div class="col-6">
                                <label for="bg_color" class="form-label small text-muted mb-1" style="font-size: 0.75rem;">Fond de Page</label>
                                <input type="color" name="bg_color" id="bg_color" class="form-control form-control-color w-100" value="{{ old('bg_color', '#f8fafc') }}">
                            </div>
                        </div>
                        <div class="mt-2 text-muted" style="font-size: 0.72rem;">
                            <i class="bi bi-info-circle me-1"></i>Toutes les cartes et boutons de service adoptent ces couleurs, sauf le bouton WhatsApp qui reste vert officiel.
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label fw-semibold small text-dark">Description</label>
                        <textarea name="description" id="description" rows="2" class="form-control" placeholder="Courte description...">{{ old('description') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label for="cover" class="form-label fw-semibold small text-dark"><i class="bi bi-image text-primary me-1"></i>Photo de Couverture</label>
                        <input type="file" name="cover" id="cover" class="form-control form-control-sm" accept="image/*">
                        <span class="form-text small">Bannière en haut de la carte (JPG, PNG, WebP).</span>
                    </div>

                    <div class="mb-3">
                        <label for="logo" class="form-label fw-semibold small text-dark"><i class="bi bi-shield-check text-primary me-1"></i>Logo de la Marque</label>
                        <input type="file" name="logo" id="logo" class="form-control form-control-sm" accept="image/*">
                    </div>

                    <!-- Commande WhatsApp & Services Table -->
                    <div class="p-3 border rounded-3 bg-light-subtle mb-3">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="fw-bold small text-dark"><i class="bi bi-whatsapp text-success me-1"></i>Commande WhatsApp &amp; Services Table</span>
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="table_service_enabled" name="table_service_enabled" value="1" {{ old('table_service_enabled', '1') ? 'checked' : '' }}>
                                <label class="form-check-label small" for="table_service_enabled">Actif</label>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="whatsapp" class="form-label small text-muted mb-1">Numéro WhatsApp</label>
                            <input type="text" name="whatsapp" id="whatsapp" class="form-control form-control-sm" placeholder="+212 600-000000" value="{{ old('whatsapp') }}">
                        </div>

                        <div class="mb-2">
                            <label for="whatsapp_message" class="form-label small text-muted mb-1">Message Commande WhatsApp</label>
                            <input type="text" name="whatsapp_message" id="whatsapp_message" class="form-control form-control-sm" placeholder="Bonjour, commande pour la {table} :" value="{{ old('whatsapp_message') }}">
                        </div>

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label for="wifi_ssid" class="form-label small text-muted mb-1">WiFi SSID</label>
                                <input type="text" name="wifi_ssid" id="wifi_ssid" class="form-control form-control-sm" placeholder="Ex: Restaurant_Guest" value="{{ old('wifi_ssid') }}">
                            </div>
                            <div class="col-6">
                                <label for="wifi_password" class="form-label small text-muted mb-1">Mot de Passe WiFi</label>
                                <input type="text" name="wifi_password" id="wifi_password" class="form-control form-control-sm" placeholder="Mot de passe WiFi..." value="{{ old('wifi_password') }}">
                            </div>
                        </div>

                        <div class="mb-0">
                            <label for="phone" class="form-label small text-muted mb-1">Téléphone / Contact</label>
                            <input type="text" name="phone" id="phone" class="form-control form-control-sm" placeholder="+212 522-000000" value="{{ old('phone') }}">
                        </div>
                    </div>
                </div>

                <!-- Navigation Style Card -->
                <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                    <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                        <div class="d-flex align-items-center gap-2">
                            <i class="bi bi-compass text-primary fs-5"></i>
                            <h6 class="fw-bold text-dark mb-0">Mode de Navigation</h6>
                        </div>
                        <span class="badge bg-light text-muted border">3 Options</span>
                    </div>

                    @php
                        $clientNav = old('nav_style', 'vertical_scroll');
                        if (in_array($clientNav, ['horizontal_scroll', 'grid_drawer'])) $clientNav = 'horizontal_scroll';
                        elseif (in_array($clientNav, ['pages', 'tabs'])) $clientNav = 'pages';
                        else $clientNav = 'vertical_scroll';
                    @endphp
                    <input type="hidden" name="nav_style" id="client_nav_style" value="{{ $clientNav }}">

                    <div class="d-flex flex-column gap-2.5">
                        <div class="p-3 rounded-3 border d-flex align-items-start gap-2.5 cursor-pointer nav-choice {{ $clientNav === 'vertical_scroll' ? 'border-primary bg-primary-subtle' : 'bg-light' }}" onclick="chooseClientNavStyle('vertical_scroll', this)" style="cursor: pointer;">
                            <input type="radio" name="_nav_radio" value="vertical_scroll" class="form-check-input mt-1" {{ $clientNav === 'vertical_scroll' ? 'checked' : '' }}>
                            <div>
                                <div class="fw-bold small text-dark">⬇️ Vertical scroll</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Défilement vertical continu de toutes les rubriques avec index sticky.</div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border d-flex align-items-start gap-2.5 cursor-pointer nav-choice {{ $clientNav === 'horizontal_scroll' ? 'border-primary bg-primary-subtle' : 'bg-light' }}" onclick="chooseClientNavStyle('horizontal_scroll', this)" style="cursor: pointer;">
                            <input type="radio" name="_nav_radio" value="horizontal_scroll" class="form-check-input mt-1" {{ $clientNav === 'horizontal_scroll' ? 'checked' : '' }}>
                            <div>
                                <div class="fw-bold small text-dark">➡️ Horizontal scroll</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Balayage tactile horizontal (swipe / carrousel) d'une catégorie à l'autre.</div>
                            </div>
                        </div>

                        <div class="p-3 rounded-3 border d-flex align-items-start gap-2.5 cursor-pointer nav-choice {{ $clientNav === 'pages' ? 'border-primary bg-primary-subtle' : 'bg-light' }}" onclick="chooseClientNavStyle('pages', this)" style="cursor: pointer;">
                            <input type="radio" name="_nav_radio" value="pages" class="form-check-input mt-1" {{ $clientNav === 'pages' ? 'checked' : '' }}>
                            <div>
                                <div class="fw-bold small text-dark">📄 Pages</div>
                                <div class="text-muted" style="font-size: 0.78rem;">Une catégorie par page avec numérotation et boutons Précédent / Suivant.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-lg-8">
                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <div class="d-flex align-items-center justify-content-between border-bottom pb-3 mb-4">
                        <div>
                            <h5 class="fw-bold text-dark mb-0">Plats &amp; Catégories</h5>
                            <small class="text-muted">Organisez votre carte par catégories et ajoutez vos plats.</small>
                        </div>
                        <button type="button" class="btn btn-sm btn-primary-custom" onclick="addCategory()">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter une Catégorie
                        </button>
                    </div>

                    <div id="categoriesContainer" class="d-flex flex-column gap-4"></div>

                    <div id="emptyMenuState" class="text-center py-5 border rounded-3 bg-light d-none">
                        <i class="bi bi-journal-plus fs-1 text-secondary mb-2 d-block"></i>
                        <h6 class="fw-bold text-dark">Votre carte est vide</h6>
                        <button type="button" class="btn btn-sm btn-primary-custom mt-2" onclick="addCategory()">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter une Catégorie
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
let menuData = [];

function chooseClientNavStyle(val, el) {
    document.getElementById('client_nav_style').value = val;
    document.querySelectorAll('.nav-choice').forEach(c => {
        c.classList.remove('border-primary', 'bg-primary-subtle');
        c.classList.add('bg-light');
        const radio = c.querySelector('input[type="radio"]');
        if (radio) radio.checked = false;
    });
    el.classList.add('border-primary', 'bg-primary-subtle');
    el.classList.remove('bg-light');
    const radio = el.querySelector('input[type="radio"]');
    if (radio) radio.checked = true;
}

const presets = {
    fast_food: [
        {
            id: 'c1',
            name: 'Burgers Signatures',
            description: '100% bœuf frais et frites croustillantes',
            items: [
                { id: 'i1', name: 'Double Bacon Cheese', price: 65, description: 'Steak frais, cheddar affiné, bacon croustillant', badge: 'Populaire', image_url: '', is_available: true },
                { id: 'i2', name: 'Crispy Chicken Burger', price: 55, description: 'Poulet croustillant mariné, sauce maison', badge: 'Chef', image_url: '', is_available: true }
            ]
        },
        {
            id: 'c2',
            name: 'Boissons & Desserts',
            description: 'Fraîcheur & douceurs',
            items: [
                { id: 'i3', name: 'Soda Frais (33cl)', price: 15, description: 'Au choix bien frais', badge: '', image_url: '', is_available: true },
                { id: 'i4', name: 'Milkshake Vanille Caramel', price: 35, description: 'Glace artisanale et coulis caramel', badge: 'Gourmand', image_url: '', is_available: true }
            ]
        }
    ],
    restaurant: [
        {
            id: 'c1',
            name: 'Entrées Fraîcheur',
            description: 'Pour commencer votre repas',
            items: [
                { id: 'i1', name: 'Salade César au Poulet Rôti', price: 60, description: 'Cœurs de romaine, parmesan, croûtons dorés', badge: 'Populaire', image_url: '', is_available: true }
            ]
        },
        {
            id: 'c2',
            name: 'Plats Principaux',
            description: 'Recettes préparées avec amour',
            items: [
                { id: 'i2', name: 'Pavé de Saumon Grillé & Riz Parfumé', price: 120, description: 'Sauce aneth et légumes de saison', badge: 'Chef', image_url: '', is_available: true }
            ]
        }
    ],
    cafe: [
        {
            id: 'c1',
            name: 'Cafés & Thés',
            description: 'Pur arabica et thés d\'exception',
            items: [
                { id: 'i1', name: 'Cappuccino Mousse Crémeuse', price: 25, description: 'Espresso double, cacao amer', badge: 'Populaire', image_url: '', is_available: true }
            ]
        }
    ]
};

function renderCategories() {
    const container = document.getElementById('categoriesContainer');
    const emptyState = document.getElementById('emptyMenuState');
    const hiddenInput = document.getElementById('categoriesJsonInput');

    container.innerHTML = '';

    if (!Array.isArray(menuData) || menuData.length === 0) {
        emptyState.classList.remove('d-none');
        hiddenInput.value = '[]';
        return;
    }

    emptyState.classList.add('d-none');

    menuData.forEach((category, catIndex) => {
        const catCard = document.createElement('div');
        catCard.className = 'card border rounded-3 p-3 bg-white shadow-xs';
        catCard.innerHTML = `
            <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                <input type="text" class="form-control form-control-sm fw-bold border-0 bg-light fs-6 flex-grow-1 me-2" 
                       value="${category.name || ''}" 
                       placeholder="Nom de la catégorie (ex: Burgers, Entrées...)" 
                       onchange="updateCategoryField(${catIndex}, 'name', this.value)">

                <div class="d-flex gap-1">
                    <button type="button" class="btn btn-sm btn-outline-success" onclick="addItem(${catIndex})">
                        <i class="bi bi-plus-lg me-1"></i>Plat
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteCategory(${catIndex})">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>
            </div>

            <div class="d-flex flex-column gap-2.5">
                ${(category.items && category.items.length > 0) ? category.items.map((item, itemIndex) => `
                    <div class="p-3 border rounded-3 bg-light-subtle d-flex flex-wrap align-items-start justify-content-between gap-3">
                        <div class="flex-grow-1">
                            <div class="row g-2 mb-2">
                                <div class="col-8">
                                    <input type="text" class="form-control form-control-sm fw-bold" 
                                           value="${item.name || ''}" 
                                           placeholder="Nom du plat" 
                                           onchange="updateItemField(${catIndex}, ${itemIndex}, 'name', this.value)">
                                </div>
                                <div class="col-4">
                                    <input type="number" step="0.5" class="form-control form-control-sm text-end fw-bold text-primary" 
                                           value="${item.price || ''}" 
                                           placeholder="Prix" 
                                           onchange="updateItemField(${catIndex}, ${itemIndex}, 'price', parseFloat(this.value))">
                                </div>
                            </div>
                            <textarea class="form-control form-control-sm mb-2" rows="1" 
                                      placeholder="Description..." 
                                      onchange="updateItemField(${catIndex}, ${itemIndex}, 'description', this.value)">${item.description || ''}</textarea>
                            
                            <div class="row g-2">
                                <div class="col-6">
                                    <select class="form-select form-select-sm" onchange="updateItemField(${catIndex}, ${itemIndex}, 'badge', this.value)">
                                        <option value="" ${!item.badge ? 'selected' : ''}>Sans badge</option>
                                        <option value="Populaire" ${item.badge === 'Populaire' ? 'selected' : ''}>⭐ Populaire</option>
                                        <option value="Chef" ${item.badge === 'Chef' ? 'selected' : ''}>👨‍🍳 Chef</option>
                                        <option value="Nouveau" ${item.badge === 'Nouveau' ? 'selected' : ''}>✨ Nouveau</option>
                                        <option value="Végétarien" ${item.badge === 'Végétarien' ? 'selected' : ''}>🌱 Végétarien</option>
                                        <option value="Piquant" ${item.badge === 'Piquant' ? 'selected' : ''}>🌶️ Piquant</option>
                                    </select>
                                </div>
                                <div class="col-6">
                                    <input type="text" class="form-control form-control-sm" 
                                           value="${item.image_url || ''}" 
                                           placeholder="Photo URL (optionnel)..." 
                                           onchange="updateItemField(${catIndex}, ${itemIndex}, 'image_url', this.value)">
                                </div>
                            </div>
                        </div>

                        <div class="d-flex align-items-center gap-2">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" 
                                       ${item.is_available !== false ? 'checked' : ''} 
                                       onchange="updateItemField(${catIndex}, ${itemIndex}, 'is_available', this.checked)">
                                <label class="form-check-label small text-muted">Dispo</label>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger border-0 p-1" onclick="deleteItem(${catIndex}, ${itemIndex})">
                                <i class="bi bi-x-circle fs-5"></i>
                            </button>
                        </div>
                    </div>
                `).join('') : `
                    <div class="text-center py-2 text-muted small">Aucun plat dans cette catégorie.</div>
                `}
            </div>
        `;
        container.appendChild(catCard);
    });

    hiddenInput.value = JSON.stringify(menuData);
}

function addCategory() {
    menuData.push({ id: 'c_' + Date.now(), name: 'Nouvelle Catégorie', items: [] });
    renderCategories();
}

function deleteCategory(index) {
    if (confirm('Supprimer cette catégorie ?')) {
        menuData.splice(index, 1);
        renderCategories();
    }
}

function updateCategoryField(index, field, value) {
    if (menuData[index]) {
        menuData[index][field] = value;
        document.getElementById('categoriesJsonInput').value = JSON.stringify(menuData);
    }
}

function addItem(catIndex) {
    if (!menuData[catIndex].items) menuData[catIndex].items = [];
    menuData[catIndex].items.push({ id: 'i_' + Date.now(), name: 'Nouveau Plat', price: 30, description: '', badge: '', image_url: '', is_available: true });
    renderCategories();
}

function deleteItem(catIndex, itemIndex) {
    if (menuData[catIndex] && menuData[catIndex].items) {
        menuData[catIndex].items.splice(itemIndex, 1);
        renderCategories();
    }
}

function updateItemField(catIndex, itemIndex, field, value) {
    if (menuData[catIndex] && menuData[catIndex].items[itemIndex]) {
        menuData[catIndex].items[itemIndex][field] = value;
        document.getElementById('categoriesJsonInput').value = JSON.stringify(menuData);
    }
}

function loadPreset(key) {
    if (presets[key]) {
        menuData = JSON.parse(JSON.stringify(presets[key]));
        renderCategories();
    }
}

document.addEventListener('DOMContentLoaded', function() {
    loadPreset('fast_food');
});
</script>
@endsection

