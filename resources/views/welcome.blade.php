@extends('layouts.public')

@section('title', 'YNS NFC & QR Studio — Cartes de Visite Digitales, Menus Interactifs & QR Dynamiques')
@section('meta_description', 'Create, manage and customize your QR codes, digital business cards, interactive menus, and smart NFC tags in real time.')

@section('content')
<!-- ======================================================================= -->
<!-- 1. MOBILE-FIRST PRODUCTION HERO WITH LIVE INTERACTIVE PHONE SIMULATOR   -->
<!-- ======================================================================= -->
<section class="hero-prod-section py-4 py-lg-5 position-relative overflow-hidden">
    <div class="container py-2 py-lg-4 position-relative z-1">
        <div class="row align-items-center g-4 g-lg-5">
            <!-- Left Hero Copy (Mobile-First Stack) -->
            <div class="col-lg-7 text-center text-lg-start">
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white text-primary small fw-bold mb-3 border shadow-2xs">
                    <span class="status-dot-radar"></span>
                    <span class="font-mono text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.06em;">YNS NFC &amp; QR Studio • Production Live</span>
                </div>

                <h1 class="hero-prod-title fw-extrabold text-dark mb-3">
                    Vos <span class="text-gradient-primary">Cartes Digitales</span>, Menus Interactifs &amp; QR Dynamiques en Un Seul Studio.
                </h1>

                <p class="text-muted mb-2 fw-semibold small font-mono">
                    Create, manage and customize your QR codes, NFC cards &amp; digital menus effortlessly.
                </p>

                <p class="lead text-secondary mb-4 fs-6 pe-lg-4 lh-base">
                    Connectez vos supports physiques (cartes NFC, chevalets de table, stickers QR) à des expériences mobiles ultra-rapides. Mettez à jour vos coordonnées, votre <strong>menu restaurant / catalogue</strong> ou vos liens de redirection <strong>en temps réel</strong>, sans jamais réimprimer.
                </p>

                <!-- Mobile-First Touch CTAs -->
                <div class="d-flex flex-column flex-sm-row gap-2.5 justify-content-center justify-content-lg-start mb-4">
                    <a href="{{ route('client.access') }}" class="btn btn-primary-custom px-4 py-3 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow-md">
                        <i class="bi bi-key-fill"></i>
                        <span>Espace Client (Code d'Accès)</span>
                    </a>
                    <a href="{{ auth('web')->check() ? route('admin.dashboard') : route('login') }}" class="btn btn-white border px-4 py-3 rounded-pill fw-bold text-dark d-inline-flex align-items-center justify-content-center gap-2 shadow-2xs">
                        <i class="bi bi-sliders2-vertical text-primary"></i>
                        <span>{{ auth('web')->check() ? 'Ouvrir le Dashboard' : 'Administration Studio' }}</span>
                    </a>
                </div>

                <!-- Mobile-First Feature Pills -->
                <div class="row g-2 pt-2 text-start">
                    <div class="col-6 col-sm-4">
                        <div class="hero-trust-pill">
                            <i class="bi bi-phone-vibrate-fill text-primary"></i>
                            <span>100% Sans Application</span>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="hero-trust-pill">
                            <i class="bi bi-lightning-charge-fill text-warning"></i>
                            <span>Modifications Live</span>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="hero-trust-pill">
                            <i class="bi bi-shield-check text-success"></i>
                            <span>iOS &amp; Android Natif</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Interactive Mobile Simulator Showcase -->
            <div class="col-lg-5" id="interactive-demo">
                <div class="demo-stage-wrapper mx-auto">
                    <!-- Interactive Mode Switcher Tabs -->
                    <div class="demo-switcher-bar d-flex p-1 rounded-pill bg-white border shadow-sm mb-3 mx-auto" style="max-width: 350px;">
                        <button type="button" class="demo-tab-btn active flex-fill" onclick="switchHeroDemo('card', this)">
                            <i class="bi bi-person-badge me-1"></i>Carte NFC
                        </button>
                        <button type="button" class="demo-tab-btn flex-fill" onclick="switchHeroDemo('menu', this)">
                            <i class="fa-solid fa-utensils me-1"></i>Menu
                        </button>
                        <button type="button" class="demo-tab-btn flex-fill" onclick="switchHeroDemo('qr', this)">
                            <i class="bi bi-qr-code-scan me-1"></i>QR Live
                        </button>
                    </div>

                    <!-- Mobile Phone Frame -->
                    <div class="hero-phone-mockup mx-auto shadow-xl">
                        <div class="hero-phone-notch"></div>

                        <!-- Status Bar -->
                        <div class="d-flex justify-content-between align-items-center px-4 pt-2 pb-1 text-white" style="font-size: 0.7rem;">
                            <span class="font-mono fw-bold">09:41</span>
                            <div class="d-flex align-items-center gap-1">
                                <i class="bi bi-reception-4"></i>
                                <i class="bi bi-wifi"></i>
                                <i class="bi bi-battery-full"></i>
                            </div>
                        </div>

                        <!-- TAB 1: DIGITAL BUSINESS CARD PREVIEW -->
                        <div class="hero-phone-pane active" id="heroPaneCard">
                            <div class="position-relative" style="height: 92px; background: linear-gradient(135deg, #6320ee 0%, #0ea5e9 100%);">
                                <span class="badge bg-dark bg-opacity-50 text-white position-absolute top-0 end-0 m-2 font-mono" style="font-size: 0.62rem;">NFC / QR ACTIF</span>
                            </div>
                            <div class="px-3 pb-3 text-center position-relative" style="margin-top: -36px;">
                                <div class="rounded-circle border border-3 border-dark d-inline-flex align-items-center justify-content-center text-white fw-extrabold shadow" style="width: 72px; height: 72px; background: linear-gradient(135deg, #4f46e5, #7c3aed); font-size: 1.35rem;">
                                    YN
                                </div>
                                <h6 class="fw-extrabold text-white mt-2 mb-0" style="font-size: 1rem;">YNS Digital Executive</h6>
                                <div class="small text-info fw-semibold mb-1" style="font-size: 0.74rem;">Directeur Général • YNS Studio</div>
                                <p class="text-white-50 mb-2.5" style="font-size: 0.7rem; line-height: 1.35;">
                                    Solutions digitales sans contact NFC, Cartes de visite &amp; Menus interactifs.
                                </p>

                                <!-- Primary CTA inside phone -->
                                <div class="d-grid gap-1.5 mb-2.5">
                                    <div class="btn btn-sm rounded-pill text-white fw-bold py-1.5 shadow-sm" style="background: #6320ee; font-size: 0.75rem;">
                                        <i class="bi bi-person-plus-fill me-1"></i>Enregistrer le Contact (.vcf)
                                    </div>
                                    <button type="button" class="btn btn-sm rounded-pill text-white fw-bold py-1.5" style="background: linear-gradient(135deg, #ea580c, #d97706); font-size: 0.74rem;" onclick="switchHeroDemoByName('menu')">
                                        <i class="fa-solid fa-utensils me-1"></i>Consulter notre Menu &amp; Catalogue
                                    </button>
                                </div>

                                <!-- Contact Links Grid -->
                                <div class="d-flex flex-column gap-1.5 text-start">
                                    <div class="p-2 rounded-3 d-flex align-items-center gap-2.5" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);">
                                        <span class="rounded-2 d-inline-flex align-items-center justify-content-center text-success" style="width: 28px; height: 28px; background: rgba(34,197,94,0.15);"><i class="bi bi-whatsapp"></i></span>
                                        <div class="flex-grow-1" style="font-size: 0.72rem;">
                                            <div class="fw-bold text-white">WhatsApp Direct</div>
                                            <div class="text-white-50" style="font-size: 0.64rem;">Réponse instantanée</div>
                                        </div>
                                        <i class="bi bi-chevron-right text-white-50 small"></i>
                                    </div>
                                    <div class="p-2 rounded-3 d-flex align-items-center gap-2.5" style="background: rgba(255,255,255,0.07); border: 1px solid rgba(255,255,255,0.1);">
                                        <span class="rounded-2 d-inline-flex align-items-center justify-content-center text-info" style="width: 28px; height: 28px; background: rgba(14,165,233,0.15);"><i class="bi bi-globe2"></i></span>
                                        <div class="flex-grow-1" style="font-size: 0.72rem;">
                                            <div class="fw-bold text-white">Site Officiel &amp; Portfolio</div>
                                            <div class="text-white-50" style="font-size: 0.64rem;">www.yns-nfc.com</div>
                                        </div>
                                        <i class="bi bi-chevron-right text-white-50 small"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: INTERACTIVE MENU & CATALOGUE PREVIEW -->
                        <div class="hero-phone-pane" id="heroPaneMenu">
                            <div class="p-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <button type="button" class="badge rounded-pill border-0 text-white px-2.5 py-1" style="background: rgba(255,255,255,0.14); font-size: 0.65rem;" onclick="switchHeroDemoByName('card')">
                                        <i class="fa-solid fa-arrow-left me-1"></i>Carte
                                    </button>
                                    <span class="badge rounded-pill font-mono" style="background: rgba(234,88,12,0.2); color: #fb923c; font-size: 0.64rem;">Mode Catalogue</span>
                                </div>

                                <!-- Category Cover Banner -->
                                <div class="rounded-3 overflow-hidden position-relative mb-2.5 border border-secondary border-opacity-25" style="height: 86px; background: linear-gradient(135deg, #7c2d12 0%, #ea580c 100%);">
                                    <div class="position-absolute inset-0 p-2.5 d-flex flex-column justify-content-end" style="inset: 0; background: linear-gradient(to top, rgba(15,23,42,0.88), rgba(15,23,42,0.15));">
                                        <span class="badge align-self-start mb-1 font-mono" style="background: #ea580c; font-size: 0.58rem;">PAGE 1 / 3</span>
                                        <div class="fw-extrabold text-white lh-1" style="font-size: 0.88rem;"><i class="fa-solid fa-star text-warning me-1"></i>Sélection Signature</div>
                                        <div class="text-white-50" style="font-size: 0.64rem;">Nos incontournables préparés minute</div>
                                    </div>
                                </div>

                                <!-- 2-Column Product Cards -->
                                <div class="row g-2">
                                    <div class="col-6">
                                        <div class="rounded-3 p-2 h-100 d-flex flex-column justify-content-between" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);">
                                            <div>
                                                <div class="rounded-2 mb-1.5 d-flex align-items-center justify-content-center position-relative" style="height: 64px; background: rgba(234,88,12,0.18);">
                                                    <i class="fa-solid fa-burger fs-3 text-warning"></i>
                                                    <span class="badge position-absolute top-0 start-0 m-1" style="background: #ea580c; font-size: 0.55rem;">Chef</span>
                                                </div>
                                                <div class="fw-bold text-white" style="font-size: 0.72rem; line-height: 1.2;">Formule Signature</div>
                                                <div class="text-white-50 mt-0.5" style="font-size: 0.6rem; line-height: 1.2;">Truffes &amp; accompagnement</div>
                                            </div>
                                            <div class="mt-2 pt-1 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                                                <span class="fw-extrabold font-mono" style="color: #fb923c; font-size: 0.72rem;">190 MAD</span>
                                                <span class="badge bg-success-subtle text-success" style="font-size: 0.55rem;">En stock</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="rounded-3 p-2 h-100 d-flex flex-column justify-content-between" style="background: rgba(255,255,255,0.06); border: 1px solid rgba(255,255,255,0.12);">
                                            <div>
                                                <div class="rounded-2 mb-1.5 d-flex align-items-center justify-content-center position-relative" style="height: 64px; background: rgba(99,32,238,0.22);">
                                                    <i class="fa-solid fa-mug-hot fs-3 text-info"></i>
                                                    <span class="badge position-absolute top-0 start-0 m-1 bg-primary" style="font-size: 0.55rem;">Populaire</span>
                                                </div>
                                                <div class="fw-bold text-white" style="font-size: 0.72rem; line-height: 1.2;">Latte Pistache</div>
                                                <div class="text-white-50 mt-0.5" style="font-size: 0.6rem; line-height: 1.2;">Double shot arabica bio</div>
                                            </div>
                                            <div class="mt-2 pt-1 border-top border-secondary border-opacity-25 d-flex justify-content-between align-items-center">
                                                <span class="fw-extrabold font-mono" style="color: #fb923c; font-size: 0.72rem;">45 MAD</span>
                                                <span class="badge bg-success-subtle text-success" style="font-size: 0.55rem;">En stock</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: DYNAMIC QR & TELEMETRY PREVIEW -->
                        <div class="hero-phone-pane" id="heroPaneQr">
                            <div class="p-3 text-center">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary-subtle text-primary font-mono" style="font-size: 0.65rem;">VP-8F29A1</span>
                                    <span class="badge bg-success-subtle text-success font-mono" style="font-size: 0.65rem;">● ACTIF</span>
                                </div>
                                <div class="p-3 bg-white rounded-4 d-inline-block shadow-sm my-2">
                                    <i class="bi bi-qr-code text-dark" style="font-size: 108px; line-height: 1;"></i>
                                </div>
                                <div class="small text-white-50 font-mono mt-1" style="font-size: 0.64rem;">DESTINATION DYNAMIQUE ACTIVE :</div>
                                <div class="p-2 rounded-3 mt-1 font-mono text-info fw-bold text-truncate" style="background: rgba(255,255,255,0.08); font-size: 0.7rem;">
                                    https://g.page/r/votre-etablissement/review
                                </div>
                                <div class="row g-2 mt-2.5 pt-2.5 border-top border-secondary border-opacity-25">
                                    <div class="col-4">
                                        <div class="text-white-50 font-mono" style="font-size: 0.6rem;">SCANS</div>
                                        <div class="fw-bold text-white font-mono" style="font-size: 0.85rem;">2,490</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-white-50 font-mono" style="font-size: 0.6rem;">SUPPORT</div>
                                        <div class="fw-bold text-warning font-mono" style="font-size: 0.75rem;">NFC + QR</div>
                                    </div>
                                    <div class="col-4">
                                        <div class="text-white-50 font-mono" style="font-size: 0.6rem;">LATENCE</div>
                                        <div class="fw-bold text-success font-mono" style="font-size: 0.8rem;">14 ms</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 2. THREE-PILLAR ECOSYSTEM SECTION (#solutions)                          -->
<!-- ======================================================================= -->
<section class="py-5 bg-white border-top border-bottom" id="solutions">
    <div class="container py-2 py-lg-4">
        <div class="text-center mx-auto mb-4 mb-lg-5" style="max-width: 680px;">
            <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill mb-2 font-mono" style="font-size: 0.72rem; letter-spacing: 0.06em;">ÉCOSYSTÈME COMPLET</span>
            <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.55rem, 3vw, 2.25rem);">Tout votre univers sans contact sur une seule plateforme</h2>
            <p class="text-muted mb-0">
                Gérez vos cartes de visite professionnelles, vos menus digitaux et vos QR codes dynamiques depuis une interface mobile-first intuitive.
            </p>
        </div>

        <div class="row g-3 g-lg-4">
            <!-- Solution 1: Cartes de Visite Digitales & NFC -->
            <div class="col-md-6 col-lg-4">
                <div class="prod-feature-card h-100 p-4 rounded-4 border bg-white">
                    <div class="prod-icon-box mb-3" style="background: #f3f0ff; color: #6320ee;">
                        <i class="bi bi-person-vcard-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Cartes de Visite Digitales &amp; NFC</h5>
                    <p class="text-muted small mb-3 lh-base">
                        Partagez votre profil professionnel d'un simple geste sur smartphone (NFC) ou par scan QR. Téléchargement direct dans le répertoire (<code>.vcf</code>), réseaux sociaux, WhatsApp et thèmes sur mesure.
                    </p>
                    <div class="d-flex flex-wrap gap-1.5 mt-auto">
                        <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.66rem;">Ajout Contact 1-Clic</span>
                        <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.66rem;">Design Personnalisé</span>
                    </div>
                </div>
            </div>

            <!-- Solution 2: Menus & Catalogues Interactifs -->
            <div class="col-md-6 col-lg-4">
                <div class="prod-feature-card h-100 p-4 rounded-4 border bg-white">
                    <div class="prod-icon-box mb-3" style="background: #fff7ed; color: #ea580c;">
                        <i class="fa-solid fa-utensils fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Menus &amp; Catalogues Interactifs</h5>
                    <p class="text-muted small mb-3 lh-base">
                        Créez un menu digital ou un catalogue produits relié à votre carte : images de couverture par page, grille 2 cartes par ligne, gestion des ruptures de stock en 1 clic et recherche instantanée.
                    </p>
                    <div class="d-flex flex-wrap gap-1.5 mt-auto">
                        <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.66rem;">3 Modes de Navigation</span>
                        <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.66rem;">Couvertures HD</span>
                    </div>
                </div>
            </div>

            <!-- Solution 3: QR Codes Dynamiques (VP Engine) -->
            <div class="col-md-6 col-lg-4">
                <div class="prod-feature-card h-100 p-4 rounded-4 border bg-white">
                    <div class="prod-icon-box mb-3" style="background: #ecfeff; color: #0891b2;">
                        <i class="bi bi-qr-code-scan fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">QR Codes Dynamiques (VP Engine)</h5>
                    <p class="text-muted small mb-3 lh-base">
                        Modifiez l'URL de destination de vos QR codes à tout moment (Avis Google, Instagram, PDF, Wi-Fi, WhatsApp) sans jamais réimprimer vos chevalets, stickers ou cartes.
                    </p>
                    <div class="d-flex flex-wrap gap-1.5 mt-auto">
                        <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.66rem;">Zéro Réimpression</span>
                        <span class="badge bg-light text-secondary border font-mono" style="font-size: 0.66rem;">Redirection Instantanée</span>
                    </div>
                </div>
            </div>

            <!-- Solution 4: Portail Client Dédié -->
            <div class="col-md-6 col-lg-4">
                <div class="prod-feature-card h-100 p-4 rounded-4 border bg-white">
                    <div class="prod-icon-box mb-3" style="background: #f0fdf4; color: #16a34a;">
                        <i class="bi bi-key-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Portail Client par Clé d'Accès</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Chaque client dispose d'une clé d'accès sécurisée lui permettant de mettre à jour ses liens et de suivre ses statistiques en toute autonomie depuis son smartphone.
                    </p>
                </div>
            </div>

            <!-- Solution 5: Statistiques & Télémétrie -->
            <div class="col-md-6 col-lg-4">
                <div class="prod-feature-card h-100 p-4 rounded-4 border bg-white">
                    <div class="prod-icon-box mb-3" style="background: #fef2f2; color: #e11d48;">
                        <i class="bi bi-graph-up-arrow fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Statistiques &amp; Scans en Direct</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Mesurez l'impact de vos supports physiques : nombre total de scans QR, touches NFC, appareils utilisés (iOS / Android) et historique d'activité détaillé.
                    </p>
                </div>
            </div>

            <!-- Solution 6: Studio d'Impression PDF -->
            <div class="col-md-6 col-lg-4">
                <div class="prod-feature-card h-100 p-4 rounded-4 border bg-white">
                    <div class="prod-icon-box mb-3" style="background: #eef2ff; color: #4f46e5;">
                        <i class="bi bi-printer-fill fs-4"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Export Haute Définition &amp; PDF</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Générez vos planches d'impression PDF et visuels QR personnalisés avec logo, couleurs de marque et cadre d'appel à l'action prêts pour l'imprimerie.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 3. HOW IT WORKS IN 3 SIMPLE STEPS (#how-it-works)                       -->
<!-- ======================================================================= -->
<section class="py-5 bg-light border-bottom" id="how-it-works">
    <div class="container py-2 py-lg-4">
        <div class="text-center mx-auto mb-4 mb-lg-5" style="max-width: 640px;">
            <span class="badge bg-white text-primary border px-3 py-1 rounded-pill mb-2 font-mono" style="font-size: 0.72rem; letter-spacing: 0.06em;">MISE EN SERVICE RAPIDE</span>
            <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.5rem, 3vw, 2.15rem);">Comment ça fonctionne ?</h2>
            <p class="text-muted mb-0">Déployez vos supports connectés en 3 étapes simples, directement depuis votre téléphone ou ordinateur.</p>
        </div>

        <div class="row g-3 g-lg-4">
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-white border h-100 position-relative">
                    <span class="step-num-badge mb-3">01</span>
                    <h5 class="fw-bold text-dark mb-2">Activez votre Support</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Utilisez votre carte NFC, plaque d'avis Google ou chevalet de table muni de son code unique (<code>VP-XXXXXX</code>) ou créez votre profil digital dans le Studio.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-white border h-100 position-relative">
                    <span class="step-num-badge mb-3">02</span>
                    <h5 class="fw-bold text-dark mb-2">Personnalisez en Direct</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Ajoutez votre logo, vos réseaux sociaux, votre fiche contact vCard ou configurez vos pages de menu avec photos et tarifs en quelques clics.
                    </p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-4 rounded-4 bg-white border h-100 position-relative">
                    <span class="step-num-badge mb-3">03</span>
                    <h5 class="fw-bold text-dark mb-2">Partagez &amp; Modifiez à Volonté</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Vos clients accèdent instantanément à votre page par simple approche NFC ou scan appareil photo. Changez vos informations quand vous le souhaitez.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 4. INDUSTRY USE CASES (#use-cases)                                      -->
<!-- ======================================================================= -->
<section class="py-5 bg-white border-bottom" id="use-cases">
    <div class="container py-2 py-lg-4">
        <div class="text-center mx-auto mb-4 mb-lg-5" style="max-width: 640px;">
            <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill mb-2 font-mono" style="font-size: 0.72rem; letter-spacing: 0.06em;">SECTEURS D'ACTIVITÉ</span>
            <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.5rem, 3vw, 2.15rem);">Conçu pour chaque professionnel</h2>
            <p class="text-muted mb-0">Des solutions adaptées aux exigences du terrain, optimisées pour tous les écrans mobiles.</p>
        </div>

        <div class="row g-3 g-lg-4">
            <div class="col-lg-4">
                <div class="p-4 rounded-4 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2.5 mb-3">
                            <span class="rounded-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 42px; height: 42px; background: #ea580c;">
                                <i class="fa-solid fa-utensils"></i>
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Restaurants, Cafés &amp; Hôtels</h5>
                        </div>
                        <p class="text-muted small mb-3 lh-base">
                            Présentez vos cartes gastronomiques, boissons et services en chambre avec des photos appétissantes, couvertures de pages et mise à jour immédiate des plats épuisés.
                        </p>
                    </div>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-1.5 text-secondary">
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Menu QR &amp; NFC sur table</li>
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Collecte d'avis Google en 1 scan</li>
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Partage Wi-Fi client instantané</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="p-4 rounded-4 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2.5 mb-3">
                            <span class="rounded-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 42px; height: 42px; background: #6320ee;">
                                <i class="bi bi-briefcase-fill"></i>
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Dirigeants &amp; Équipes Commerciales</h5>
                        </div>
                        <p class="text-muted small mb-3 lh-base">
                            Remplacez les cartes papier par une carte NFC intelligente aux couleurs de votre entreprise. Vos interlocuteurs enregistrent votre contact complet en une seconde.
                        </p>
                    </div>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-1.5 text-secondary">
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Fiche contact vCard (.vcf) universelle</li>
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Liens LinkedIn, WhatsApp, Calendly &amp; Maps</li>
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Gestion centralisée multi-collaborateurs</li>
                    </ul>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="p-4 rounded-4 border bg-light bg-opacity-50 h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center gap-2.5 mb-3">
                            <span class="rounded-3 d-inline-flex align-items-center justify-content-center text-white" style="width: 42px; height: 42px; background: #0891b2;">
                                <i class="bi bi-bag-check-fill"></i>
                            </span>
                            <h5 class="fw-bold text-dark mb-0">Boutiques, Salons &amp; Services</h5>
                        </div>
                        <p class="text-muted small mb-3 lh-base">
                            Affichez votre catalogue de produits, lookbook ou grille de prestations avec tarifs clairs et bouton de réservation ou commande directe sur WhatsApp.
                        </p>
                    </div>
                    <ul class="list-unstyled small mb-0 d-flex flex-column gap-1.5 text-secondary">
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Catalogue feuilletable page par page</li>
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Redirection Instagram, TikTok &amp; Boutique</li>
                        <li><i class="bi bi-check2-circle text-success me-1.5"></i>Suivi des scans en temps réel</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 5. FREQUENTLY ASKED QUESTIONS (#faq)                                    -->
<!-- ======================================================================= -->
<section class="py-5 bg-light border-bottom" id="faq">
    <div class="container py-2 py-lg-4" style="max-width: 820px;">
        <div class="text-center mb-4">
            <span class="badge bg-white text-primary border px-3 py-1 rounded-pill mb-2 font-mono" style="font-size: 0.72rem; letter-spacing: 0.06em;">QUESTIONS FRÉQUENTES</span>
            <h2 class="fw-extrabold text-dark mb-1" style="font-size: clamp(1.45rem, 3vw, 2rem);">Tout savoir sur YNS NFC &amp; QR</h2>
        </div>

        <div class="accordion accordion-flush rounded-4 border overflow-hidden shadow-2xs bg-white" id="homeFaqAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold text-dark py-3.5" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Mes clients doivent-ils installer une application pour lire la carte NFC ou le QR Code ?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#homeFaqAccordion">
                    <div class="accordion-body text-muted small lh-base pt-0">
                        <strong>Aucune application n'est nécessaire.</strong> Il suffit d'approcher la carte NFC du téléphone (iPhone ou Android) ou de scanner le QR code avec l'appareil photo standard pour ouvrir instantanément votre carte digitale, votre menu ou votre lien.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold text-dark py-3.5" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Puis-je modifier mon menu, mes prix ou mes coordonnées après impression ?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#homeFaqAccordion">
                    <div class="accordion-body text-muted small lh-base pt-0">
                        Oui, à 100%. Tous nos QR codes et tags NFC sont <strong>dynamiques</strong>. Toute modification effectuée dans le Studio (ajout d'un plat, changement de numéro, nouvelle couverture de page ou changement d'URL de redirection) est appliquée immédiatement en ligne.
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed fw-bold text-dark py-3.5" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        Comment fonctionne l'Espace Client avec Code d'Accès ?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#homeFaqAccordion">
                    <div class="accordion-body text-muted small lh-base pt-0">
                        Si vous avez reçu une carte ou un support doté d'un code d'accès client, cliquez sur <strong>Espace Client</strong> en haut de page, saisissez votre clé unique et gérez directement la destination de votre QR code ainsi que vos statistiques de scan.
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 6. SINGLE-PAGE CONTACT & QUICK ACCESS SECTION (#contact)                -->
<!-- ======================================================================= -->
<section class="py-5 bg-white" id="contact">
    <div class="container py-2 py-lg-4">
        <div class="row g-4 g-lg-5 align-items-start">
            <!-- Left Column: Direct Portals & Contact Info -->
            <div class="col-lg-5">
                <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill mb-2 font-mono" style="font-size: 0.72rem; letter-spacing: 0.06em;">CONTACT &amp; ACCÈS</span>
                <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.5rem, 3vw, 2.1rem);">Besoin d'accompagnement ou d'un déploiement sur mesure ?</h2>
                <p class="text-muted small mb-4 lh-base">
                    Notre équipe vous accompagne dans la création de vos cartes de visite NFC, menus interactifs et supports QR personnalisés pour votre établissement ou votre entreprise.
                </p>

                <div class="d-flex flex-column gap-3">
                    <div class="p-3.5 rounded-4 border bg-light d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="rounded-3 d-inline-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 42px; height: 42px; background: #6320ee;">
                                <i class="bi bi-key-fill"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-dark small">Portail Client (Code d'Accès)</div>
                                <div class="text-muted" style="font-size: 0.76rem;">Activez ou modifiez votre support existant</div>
                            </div>
                        </div>
                        <a href="{{ route('client.access') }}" class="btn btn-sm btn-primary rounded-pill px-3 fw-semibold flex-shrink-0">Ouvrir</a>
                    </div>

                    <div class="p-3.5 rounded-4 border bg-light d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <span class="rounded-3 d-inline-flex align-items-center justify-content-center text-white flex-shrink-0" style="width: 42px; height: 42px; background: #0f172a;">
                                <i class="bi bi-sliders2-vertical"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-dark small">Studio Administration</div>
                                <div class="text-muted" style="font-size: 0.76rem;">Gestion complète des cartes, menus &amp; NFC</div>
                            </div>
                        </div>
                        <a href="{{ auth('web')->check() ? route('admin.dashboard') : route('login') }}" class="btn btn-sm btn-dark rounded-pill px-3 fw-semibold flex-shrink-0">Connexion</a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Single-Page Contact Form -->
            <div class="col-lg-7">
                <div class="p-4 p-lg-5 rounded-4 border bg-white shadow-sm">
                    <h5 class="fw-bold text-dark mb-1">Envoyer un message à notre équipe</h5>
                    <p class="text-muted small mb-4">Remplissez ce formulaire et nous vous répondrons dans les plus brefs délais.</p>

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold text-dark">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Votre nom ou entreprise" required value="{{ old('name') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold text-dark">Adresse Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="contact@entreprise.com" required value="{{ old('email') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Objet de votre demande <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control" placeholder="ex: Cartes de visite NFC, Menu Restaurant, QR Dynamiques..." required value="{{ old('subject') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Votre message <span class="text-danger">*</span></label>
                                <textarea name="message" rows="4" class="form-control" placeholder="Décrivez votre projet ou votre besoin..." required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12 pt-1">
                                <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow-sm">
                                    <i class="bi bi-send-fill"></i>
                                    <span>Envoyer ma Demande</span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('styles')
<style>
html {
    scroll-behavior: smooth;
}
.hero-prod-section {
    background:
        radial-gradient(circle at 12% 15%, rgba(99, 32, 238, 0.08) 0%, transparent 42%),
        radial-gradient(circle at 88% 85%, rgba(14, 165, 233, 0.08) 0%, transparent 42%),
        #f8fafc;
}
.hero-prod-title {
    font-size: clamp(1.85rem, 4.2vw, 3.1rem);
    line-height: 1.12;
    letter-spacing: -0.03em;
}
.text-gradient-primary {
    background: linear-gradient(135deg, #6320ee 0%, #0ea5e9 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.hero-trust-pill {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 0.55rem 0.85rem;
    font-size: 0.78rem;
    font-weight: 600;
    color: #334155;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.demo-tab-btn {
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 0.76rem;
    font-weight: 700;
    padding: 0.45rem 0.65rem;
    border-radius: 999px;
    transition: all 0.18s ease;
}
.demo-tab-btn.active {
    background: #6320ee;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(99, 32, 238, 0.25);
}
.hero-phone-mockup {
    width: 100%;
    max-width: 325px;
    min-height: 465px;
    background: #0f172a;
    border-radius: 40px;
    border: 8px solid #1e293b;
    position: relative;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}
.hero-phone-notch {
    width: 84px;
    height: 18px;
    background: #020617;
    border-radius: 20px;
    margin: 8px auto 2px auto;
}
.hero-phone-pane {
    display: none;
    flex-grow: 1;
}
.hero-phone-pane.active {
    display: block;
    animation: fadeInPhonePane 0.22s ease;
}
@keyframes fadeInPhonePane {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}
.prod-feature-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease, border-color 0.2s ease;
    display: flex;
    flex-direction: column;
}
.prod-feature-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 24px -8px rgba(15, 23, 42, 0.08);
    border-color: #cbd5e1 !important;
}
.prod-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.step-num-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 10px;
    background: #f3f0ff;
    color: #6320ee;
    font-family: 'JetBrains Mono', monospace;
    font-weight: 800;
    font-size: 0.85rem;
}
.gap-1\.5 { gap: 0.375rem !important; }
.gap-2\.5 { gap: 0.625rem !important; }
.p-2\.5 { padding: 0.625rem !important; }
.p-3\.5 { padding: 0.875rem !important; }
.px-2\.5 { padding-left: 0.625rem !important; padding-right: 0.625rem !important; }
.px-3\.5 { padding-left: 0.875rem !important; padding-right: 0.875rem !important; }
.py-1\.5 { padding-top: 0.375rem !important; padding-bottom: 0.375rem !important; }
.py-2\.5 { padding-top: 0.625rem !important; padding-bottom: 0.625rem !important; }
.py-3\.5 { padding-top: 0.875rem !important; padding-bottom: 0.875rem !important; }
.mb-1\.5 { margin-bottom: 0.375rem !important; }
.mb-2\.5 { margin-bottom: 0.625rem !important; }
.mt-0\.5 { margin-top: 0.125rem !important; }
.mt-2\.5 { margin-top: 0.625rem !important; }
.me-1\.5 { margin-right: 0.375rem !important; }
.btn-white {
    background: #ffffff;
    color: #0f172a;
}
.btn-white:hover {
    background: #f8fafc;
}
</style>
@endpush

@push('scripts')
<script>
window.switchHeroDemo = function(tabKey, btnEl) {
    document.querySelectorAll('.demo-tab-btn').forEach(b => b.classList.remove('active'));
    if (btnEl) btnEl.classList.add('active');

    const map = {
        card: 'heroPaneCard',
        menu: 'heroPaneMenu',
        qr: 'heroPaneQr'
    };
    document.querySelectorAll('.hero-phone-pane').forEach(p => p.classList.remove('active'));
    const target = document.getElementById(map[tabKey] || 'heroPaneCard');
    if (target) target.classList.add('active');
};

window.switchHeroDemoByName = function(tabKey) {
    const buttons = document.querySelectorAll('.demo-tab-btn');
    const idx = tabKey === 'menu' ? 1 : (tabKey === 'qr' ? 2 : 0);
    window.switchHeroDemo(tabKey, buttons[idx] || null);
};
</script>
@endpush
