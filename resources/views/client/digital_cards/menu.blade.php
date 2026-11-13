@extends('layouts.client')

@section('title', 'Gestion du Menu & Catalogue — ' . $digitalCard->title)
@section('page_title', 'Gestion du Menu & Catalogue Interactif')
@section('page_subtitle', 'Ajoutez vos plats, produits, descriptions et tarifs en temps réel')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <div class="mb-3 d-flex justify-content-between align-items-center">
        <a href="{{ route('client.digital-cards.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Retour à mes cartes
        </a>
        <a href="{{ route('digital-cards.public.menu', $digitalCard->slug) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3">
            <i class="bi bi-eye me-1"></i>Voir le Menu en Ligne
        </a>
    </div>

    <form method="POST" action="{{ route('client.digital-cards.update-menu', $digitalCard) }}" id="clientMenuForm">
        @csrf
        @method('PUT')

        <input type="hidden" name="menu_config" id="menuConfigInput">

        <div class="row g-4">
            <div class="col-lg-8">
                <!-- General Menu Settings -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <h5 class="fw-bold text-dark pb-2 border-bottom mb-3">Configuration Générale du Menu</h5>

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Titre Principal</label>
                            <input type="text" id="menuTitle" class="form-control" value="{{ $currentMenu['title'] ?? 'Notre Menu & Catalogue' }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-semibold text-dark small">Devise Affichée</label>
                            <input type="text" id="menuCurrency" class="form-control font-mono" value="{{ $currentMenu['currency'] ?? 'MAD' }}" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label fw-semibold text-dark small">Sous-titre / Message d'accueil</label>
                            <input type="text" id="menuSubtitle" class="form-control" value="{{ $currentMenu['subtitle'] ?? 'Découvrez notre sélection préparée avec passion.' }}">
                        </div>
                    </div>
                </div>

                <!-- Categories & Items Editor -->
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
                    <div class="d-flex justify-content-between align-items-center pb-2 border-bottom mb-3">
                        <h5 class="fw-bold text-dark mb-0">Catégories &amp; Plats / Produits</h5>
                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3" onclick="addCategory()">
                            <i class="bi bi-plus-lg me-1"></i>Ajouter une Catégorie
                        </button>
                    </div>

                    <div id="categoriesContainer">
                        <!-- Rendered by JS -->
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2">
                    <a href="{{ route('client.digital-cards.index') }}" class="btn btn-sm btn-light border rounded-pill px-4">Annuler</a>
                    <button type="button" class="btn btn-sm btn-primary-custom rounded-pill px-4 py-2 fw-bold shadow-xs" onclick="saveMenu()">
                        <i class="bi bi-check-circle-fill me-1"></i>Enregistrer et Publier le Menu
                    </button>
                </div>
            </div>

            <!-- Side Info Card -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 p-4 bg-white sticky-top" style="top: 20px;">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center text-white" style="width: 36px; height: 36px; background: #5736e9;">
                            <i class="fa-solid fa-utensils"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-dark mb-0">Menu Digital Live</h6>
                            <small class="text-muted">Synchronisation instantanée</small>
                        </div>
                    </div>

                    <p class="text-muted small">
                        Toute modification apportée à vos catégories ou tarifs prend effet <strong>immédiatement</strong> sur le smartphone de vos clients sans réimpression de QR code.
                    </p>

                    <div class="p-2.5 rounded-3 bg-light border font-mono small text-truncate mb-3">
                        <span class="text-muted d-block" style="font-size: 0.65rem;">Lien direct Menu :</span>
                        <a href="{{ route('digital-cards.public.menu', $digitalCard->slug) }}" target="_blank" class="text-primary text-decoration-none">
                            {{ url('/c/'.$digitalCard->slug.'/menu') }}
                        </a>
                    </div>

                    <button type="button" class="btn btn-primary-custom w-100 rounded-pill py-2 fw-semibold" onclick="saveMenu()">
                        <i class="bi bi-cloud-arrow-up-fill me-1"></i>Publier le Menu
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script>
let menuData = @json($currentMenu);
if (!menuData.categories || !Array.isArray(menuData.categories)) {
    menuData.categories = [
        {
            id: 'cat_1',
            name: 'Plats Signatures',
            description: 'Nos spécialités de la maison',
            items: [
                { id: 'item_1', name: 'Filet de Bœuf Rossini', description: 'Truffes fraîches & foie gras poêlé', price: '240', badge: 'Chef' },
                { id: 'item_2', name: 'Saumon Grillé & Asperges', description: 'Beurre blanc au citron confit', price: '190', badge: '' }
            ]
        }
    ];
}

function renderCategories() {
    const container = document.getElementById('categoriesContainer');
    if (!container) return;

    if (menuData.categories.length === 0) {
        container.innerHTML = `
            <div class="text-center py-4 text-muted border rounded-3 bg-light">
                <i class="bi bi-journal-plus fs-2 d-block mb-1"></i>
                <p class="mb-0 small">Aucune catégorie pour l'instant. Cliquez sur "Ajouter une Catégorie".</p>
            </div>
        `;
        return;
    }

    let html = '';
    menuData.categories.forEach((cat, catIdx) => {
        html += `
            <div class="border rounded-3 p-3 mb-3 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div class="d-flex align-items-center gap-2 flex-grow-1 me-2">
                        <span class="badge rounded-pill text-white" style="background: #5736e9;">Catégorie ${catIdx + 1}</span>
                        <input type="text" class="form-control form-control-sm fw-bold bg-white" value="${escapeHtml(cat.name)}" placeholder="Nom de catégorie (ex: Entrées, Desserts)" onchange="updateCatName(${catIdx}, this.value)">
                    </div>
                    <button type="button" class="btn btn-xs btn-outline-danger rounded-pill" onclick="removeCategory(${catIdx})" title="Supprimer la catégorie">
                        <i class="bi bi-trash"></i>
                    </button>
                </div>

                <div class="ps-2 pt-2 border-top">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <small class="fw-bold text-muted text-uppercase" style="font-size: 0.7rem;">Produits / Plats</small>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="addItem(${catIdx})">
                            <i class="bi bi-plus me-0.5"></i>Ajouter un Plat
                        </button>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        ${(cat.items || []).map((item, itemIdx) => `
                            <div class="p-2 rounded-2 bg-white border d-flex align-items-center gap-2 flex-wrap">
                                <div class="flex-grow-1" style="min-width: 150px;">
                                    <input type="text" class="form-control form-control-sm fw-semibold" value="${escapeHtml(item.name)}" placeholder="Nom du plat" onchange="updateItemField(${catIdx}, ${itemIdx}, 'name', this.value)">
                                </div>
                                <div style="width: 110px;">
                                    <div class="input-group input-group-sm">
                                        <input type="text" class="form-control font-mono fw-bold" value="${escapeHtml(item.price)}" placeholder="Prix" onchange="updateItemField(${catIdx}, ${itemIdx}, 'price', this.value)">
                                    </div>
                                </div>
                                <div style="width: 90px;">
                                    <input type="text" class="form-control form-control-sm" value="${escapeHtml(item.badge || '')}" placeholder="Badge" onchange="updateItemField(${catIdx}, ${itemIdx}, 'badge', this.value)">
                                </div>
                                <button type="button" class="btn btn-xs btn-light border text-danger rounded-circle p-1" onclick="removeItem(${catIdx}, ${itemIdx})" title="Supprimer">
                                    <i class="bi bi-x"></i>
                                </button>
                                <div class="w-100">
                                    <input type="text" class="form-control form-control-sm text-muted" value="${escapeHtml(item.description || '')}" placeholder="Description / Ingrédients..." onchange="updateItemField(${catIdx}, ${itemIdx}, 'description', this.value)" style="font-size: 0.75rem;">
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            </div>
        `;
    });

    container.innerHTML = html;
}

function addCategory() {
    menuData.categories.push({
        id: 'cat_' + Date.now(),
        name: 'Nouvelle Catégorie',
        description: '',
        items: []
    });
    renderCategories();
}

function removeCategory(idx) {
    if (confirm('Supprimer cette catégorie et tous ses produits ?')) {
        menuData.categories.splice(idx, 1);
        renderCategories();
    }
}

function updateCatName(idx, val) {
    menuData.categories[idx].name = val;
}

function addItem(catIdx) {
    if (!menuData.categories[catIdx].items) {
        menuData.categories[catIdx].items = [];
    }
    menuData.categories[catIdx].items.push({
        id: 'item_' + Date.now(),
        name: 'Nouveau Plat',
        price: '0',
        badge: '',
        description: ''
    });
    renderCategories();
}

function removeItem(catIdx, itemIdx) {
    menuData.categories[catIdx].items.splice(itemIdx, 1);
    renderCategories();
}

function updateItemField(catIdx, itemIdx, field, val) {
    menuData.categories[catIdx].items[itemIdx][field] = val;
}

function saveMenu() {
    menuData.title = document.getElementById('menuTitle').value;
    menuData.currency = document.getElementById('menuCurrency').value;
    menuData.subtitle = document.getElementById('menuSubtitle').value;

    document.getElementById('menuConfigInput').value = JSON.stringify(menuData);
    document.getElementById('clientMenuForm').submit();
}

function escapeHtml(text) {
    if (!text) return '';
    return text.toString()
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

document.addEventListener('DOMContentLoaded', renderCategories);
</script>
@endpush
@endsection

