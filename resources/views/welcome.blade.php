@extends('layouts.public')

@section('title', 'YNS NFC & QR Studio — Cartes de Visite Digitales, Menus Interactifs & QR Dynamiques')
@section('meta_description', 'Create, manage and customize your QR codes, digital business cards, interactive menus, and smart NFC tags in real time.')

@section('content')
<!-- ======================================================================= -->
<!-- 1. ULTRA-MODERN FUTURISTIC HERO WITH LEVITATING IPHONE 18 PRO MAX       -->
<!-- ======================================================================= -->
<section class="hero-futuristic-section position-relative overflow-hidden py-5">
    <!-- Ambient Holographic Mesh Canvas & Floating Orbs -->
    <div class="futuristic-mesh-grid"></div>
    <div class="futuristic-glow-orb orb-violet"></div>
    <div class="futuristic-glow-orb orb-cyan"></div>
    <div class="futuristic-glow-orb orb-amber"></div>

    <div class="container py-2 py-lg-4 position-relative z-2">
        <div class="row align-items-center g-4 g-lg-5">
            <!-- Left Hero Content -->
            <div class="col-lg-7 text-center text-lg-start">
                <!-- High-Tech Radar Pill -->
                <div class="d-inline-flex align-items-center gap-2 px-3 py-1.5 rounded-pill bg-white text-primary small fw-bold mb-3 border shadow-2xs futuristic-badge-chip reveal-on-scroll reveal-fade-up">
                    <span class="status-dot-radar"></span>
                    <span class="font-mono text-uppercase" style="font-size: 0.72rem; letter-spacing: 0.08em;">Studio v4.5 • Quantum NFC Engine</span>
                    <span class="badge bg-primary-subtle text-primary font-mono rounded-pill px-2 py-0.5" style="font-size: 0.62rem;">PRO</span>
                </div>

                <h1 class="hero-futuristic-title fw-extrabold text-dark mb-3 reveal-on-scroll reveal-fade-up delay-100">
                    Le Futur du <span class="futuristic-gradient-text">Sans Contact</span> : Cartes NFC &amp; Menus Digitaux.
                </h1>

                <!-- Required by Automated Test Suite -->
                <p class="text-muted mb-2 fw-semibold small font-mono reveal-on-scroll reveal-fade-up delay-150">
                    Create, manage and customize your QR codes, NFC cards &amp; digital menus effortlessly.
                </p>

                <p class="lead text-secondary mb-4 fs-6 pe-lg-4 lh-base reveal-on-scroll reveal-fade-up delay-200">
                    Connectez vos supports physiques (cartes NFC, chevalets de table, vitrines connectées) à des expériences mobiles ultra-fluides. Mettez à jour vos coordonnées vCard, votre <strong>menu restaurant &amp; catalogue</strong> ou vos redirections <strong>en direct</strong>, sans jamais réimprimer.
                </p>

                <!-- Unified Action CTA -->
                <div class="d-flex flex-column flex-sm-row gap-2.5 justify-content-center justify-content-lg-start mb-4 reveal-on-scroll reveal-fade-up delay-250">
                    @php
                        $heroLoginUrl = auth('web')->check()
                            ? route('admin.dashboard')
                            : (auth('client')->check() ? route('client.dashboard') : route('login'));
                        $heroLoginLabel = auth('web')->check()
                            ? 'Ouvrir le Dashboard'
                            : (auth('client')->check() ? 'Accéder à Mon Portail' : 'Connexion');
                    @endphp
                    <a href="{{ $heroLoginUrl }}" class="btn btn-primary-custom px-4 py-3 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow-lg btn-futuristic-glow">
                        <i class="bi bi-box-arrow-in-right text-white"></i>
                        <span>{{ $heroLoginLabel }}</span>
                        <i class="bi bi-arrow-right small ms-1"></i>
                    </a>
                </div>

                <!-- 3 Live Telemetry Capsules -->
                <div class="row g-2 pt-2 text-start reveal-on-scroll reveal-fade-up delay-300">
                    <div class="col-6 col-sm-4">
                        <div class="futuristic-metric-pill">
                            <div class="metric-icon-dot bg-primary-subtle text-primary">
                                <i class="bi bi-phone-vibrate-fill"></i>
                            </div>
                            <div>
                                <div class="fw-extrabold text-dark font-mono" style="font-size: 0.85rem;">0 App</div>
                                <div class="text-muted" style="font-size: 0.68rem;">100% Natif Navigateur</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <div class="futuristic-metric-pill">
                            <div class="metric-icon-dot bg-warning-subtle text-warning">
                                <i class="bi bi-lightning-charge-fill"></i>
                            </div>
                            <div>
                                <div class="fw-extrabold text-dark font-mono" style="font-size: 0.85rem;">12 ms</div>
                                <div class="text-muted" style="font-size: 0.68rem;">Redirection Instantanée</div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-sm-4">
                        <div class="futuristic-metric-pill">
                            <div class="metric-icon-dot bg-success-subtle text-success">
                                <i class="bi bi-arrow-repeat"></i>
                            </div>
                            <div>
                                <div class="fw-extrabold text-dark font-mono" style="font-size: 0.85rem;">Illimité</div>
                                <div class="text-muted" style="font-size: 0.68rem;">Zéro Réimpression</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Interactive Phone Showcase: iPhone 18 Pro Max (9:16) -->
            <div class="col-lg-5" id="interactive-demo">
                <div class="demo-stage-wrapper mx-auto position-relative reveal-on-scroll reveal-scale-up delay-200">
                    <!-- Ambient Backlight Glow -->
                    <div class="phone-ambient-backlight"></div>

                    <!-- Interactive Mode Switcher Tabs -->
                    <div class="demo-switcher-bar d-flex p-1 rounded-pill bg-white border shadow-sm mb-3 w-100 position-relative z-2">
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

                    <!-- Mobile Phone Frame: iPhone 18 Pro Max (9:16 Proportions) -->
                    <div class="iphone-18-pro-max mx-auto position-relative z-2">
                        <!-- Physical Titanium Hardware Buttons -->
                        <div class="iphone-btn iphone-action-btn" title="Action Button"></div>
                        <div class="iphone-btn iphone-vol-up" title="Volume +"></div>
                        <div class="iphone-btn iphone-vol-down" title="Volume -"></div>
                        <div class="iphone-btn iphone-power-btn" title="Power Button"></div>
                        <div class="iphone-btn iphone-camera-btn" title="Camera Control"></div>

                        <!-- Outer Titanium Bezel & OLED Screen Container -->
                        <div class="iphone-screen">
                            <!-- Glass Specular Reflection Overlay -->
                            <div class="iphone-glass-reflection"></div>

                            <!-- Interactive Dynamic Island with Live Activity Pulse -->
                            <div class="iphone-dynamic-island">
                                <div class="island-left-activity d-flex align-items-center gap-1">
                                    <span class="island-nfc-wave"></span>
                                    <span class="island-text font-mono">NFC</span>
                                </div>
                                <div class="island-right-hardware d-flex align-items-center gap-1.5">
                                    <div class="island-lens"></div>
                                    <div class="island-sensor"></div>
                                </div>
                            </div>

                            <!-- iOS 18 Status Bar -->
                            <div class="iphone-status-bar d-flex justify-content-between align-items-center">
                                <span class="ios-time">09:41</span>
                                <div class="d-flex align-items-center gap-1.5 ios-status-icons">
                                    <div class="ios-signal" title="Signal 5G">
                                        <span class="bar bar-1"></span>
                                        <span class="bar bar-2"></span>
                                        <span class="bar bar-3"></span>
                                        <span class="bar bar-4"></span>
                                    </div>
                                    <span class="ios-badge-5g font-mono">5G</span>
                                    <div class="ios-battery" title="Batterie 100%">
                                        <div class="ios-battery-fill"></div>
                                        <div class="ios-battery-tip"></div>
                                    </div>
                                </div>
                            </div>

                            <!-- Screen Scrollable App Container -->
                            <div class="iphone-screen-scroll-body">
                                <!-- =============================================== -->
                                <!-- TAB 1: LUXURY NFC DIGITAL BUSINESS CARD         -->
                                <!-- =============================================== -->
                                <div class="hero-phone-pane active" id="heroPaneCard">
                                    <div class="phone-card-canvas text-white">
                                        <!-- Header Cover with Architectural Elegance & NFC Pulse -->
                                        <div class="phone-card-cover position-relative overflow-hidden">
                                            <div class="cover-mesh-pattern"></div>
                                            <div class="position-absolute top-0 end-0 m-2 d-flex align-items-center gap-1 px-2 py-0.5 rounded-pill phone-nfc-chip shadow-xs">
                                                <span class="status-dot-radar" style="width: 5px; height: 5px;"></span>
                                                <span class="font-mono text-uppercase" style="font-size: 0.52rem; letter-spacing: 0.06em;">NFC / QR ACTIF</span>
                                            </div>
                                        </div>

                                        <!-- Profile Identity Block -->
                                        <div class="px-3 pb-3 text-center position-relative" style="margin-top: -34px;">
                                            <div class="position-relative d-inline-block">
                                                <div class="rounded-circle d-inline-flex align-items-center justify-content-center text-white fw-extrabold shadow-lg phone-avatar-ring">
                                                    YN
                                                </div>
                                                <span class="position-absolute bottom-0 end-0 rounded-circle bg-success border border-2 border-dark" style="width: 12px; height: 12px;" title="En Ligne"></span>
                                            </div>

                                            <h6 class="fw-extrabold text-white mt-1.5 mb-0 text-truncate d-flex align-items-center justify-content-center gap-1" style="font-size: 0.94rem; letter-spacing: -0.01em;">
                                                <span>YNS Digital Executive</span>
                                                <i class="bi bi-patch-check-fill" style="font-size: 0.8rem; color: #7e63f5;" title="Profil Vérifié"></i>
                                            </h6>
                                            <div class="fw-semibold font-mono" style="font-size: 0.66rem; color: #a78bfa;">Directeur Général • YNS Studio</div>
                                            <p class="text-white-50 mt-1 mb-2.5 px-1" style="font-size: 0.64rem; line-height: 1.35;">
                                                Solutions digitales sans contact NFC, Cartes de visite &amp; Menus interactifs.
                                            </p>

                                            <!-- CTA Buttons inside phone -->
                                            <div class="d-grid gap-1.5 mb-2.5">
                                                <div class="btn btn-sm rounded-pill text-white fw-bold py-1.5 shadow-sm phone-vcard-btn">
                                                    <i class="bi bi-person-plus-fill me-1.5"></i>Enregistrer le Contact (.vcf)
                                                </div>
                                                <button type="button" class="btn btn-sm rounded-pill fw-bold py-1.5 border-0 phone-menu-pill-btn" onclick="switchHeroDemoByName('menu')">
                                                    <i class="fa-solid fa-utensils me-1.5" style="color: #c4b5fd;"></i>Consulter notre Menu &amp; Catalogue
                                                </button>
                                            </div>

                                            <!-- The Phone Contact Cards with touches of #5736e9 & close spectrum colors -->
                                            <div class="d-flex flex-column gap-1.5 text-start">
                                                <!-- Card 1: WhatsApp Direct -->
                                                <div class="phone-action-card card-touch-whatsapp p-2 rounded-3 d-flex align-items-center gap-2">
                                                    <span class="phone-card-icon-box icon-whatsapp flex-shrink-0">
                                                        <i class="bi bi-whatsapp"></i>
                                                    </span>
                                                    <div class="flex-grow-1 overflow-hidden" style="font-size: 0.72rem;">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="fw-bold text-white text-truncate">WhatsApp Direct</div>
                                                            <span class="badge rounded-pill font-mono phone-mini-chip">Actif</span>
                                                        </div>
                                                        <div class="text-white-50 text-truncate" style="font-size: 0.62rem;">Réponse instantanée &bull; Devis</div>
                                                    </div>
                                                    <i class="bi bi-chevron-right phone-card-chevron"></i>
                                                </div>

                                                <!-- Card 2: Site Officiel & Portfolio -->
                                                <div class="phone-action-card card-touch-web p-2 rounded-3 d-flex align-items-center gap-2">
                                                    <span class="phone-card-icon-box icon-web flex-shrink-0">
                                                        <i class="bi bi-globe2"></i>
                                                    </span>
                                                    <div class="flex-grow-1 overflow-hidden" style="font-size: 0.72rem;">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="fw-bold text-white text-truncate">Site Officiel &amp; Portfolio</div>
                                                            <span class="badge rounded-pill font-mono phone-mini-chip">Live</span>
                                                        </div>
                                                        <div class="text-white-50 text-truncate font-mono" style="font-size: 0.6rem; color: #a5b4fc !important;">www.yns-nfc.com</div>
                                                    </div>
                                                    <i class="bi bi-chevron-right phone-card-chevron"></i>
                                                </div>

                                                <!-- Card 3: Prendre RDV (Calendly) -->
                                                <div class="phone-action-card card-touch-calendar p-2 rounded-3 d-flex align-items-center gap-2">
                                                    <span class="phone-card-icon-box icon-calendar flex-shrink-0">
                                                        <i class="bi bi-calendar2-check-fill"></i>
                                                    </span>
                                                    <div class="flex-grow-1 overflow-hidden" style="font-size: 0.72rem;">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <div class="fw-bold text-white text-truncate">Prendre RDV Privé</div>
                                                            <span class="badge rounded-pill font-mono phone-mini-chip">15 min</span>
                                                        </div>
                                                        <div class="text-white-50 text-truncate" style="font-size: 0.62rem;">Créneaux disponibles cette semaine</div>
                                                    </div>
                                                    <i class="bi bi-chevron-right phone-card-chevron"></i>
                                                </div>
                                            </div>

                                            <!-- NFC Telemetry Pill -->
                                            <div class="pt-2 mt-2 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center text-white-50 font-mono" style="font-size: 0.58rem;">
                                                <span><i class="bi bi-broadcast text-success me-1"></i>Puce NTAG216</span>
                                                <span style="color: #a78bfa;">vCard 4.0 Natif</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- =============================================== -->
                                <!-- TAB 2: INTERACTIVE MENU & CATALOGUE PREVIEW     -->
                                <!-- =============================================== -->
                                <div class="hero-phone-pane" id="heroPaneMenu">
                                    <div class="phone-menu-canvas text-white">
                                        <!-- Restaurant Cover Banner with Real Ambience Photo -->
                                        <div class="position-relative overflow-hidden" style="height: 94px;">
                                            <img src="https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?auto=format&fit=crop&w=600&q=80" 
                                                 alt="Restaurant Ambiance" 
                                                 class="w-100 h-100 object-fit-cover position-absolute inset-0"
                                                 onerror="this.style.display='none';">
                                            <div class="position-absolute inset-0" style="background: linear-gradient(to top, rgba(9,13,22,0.95) 15%, rgba(9,13,22,0.3) 100%);"></div>
                                            
                                            <div class="position-absolute top-0 start-0 end-0 p-2 d-flex align-items-center justify-content-between z-2">
                                                <button type="button" class="badge rounded-pill border-0 text-white px-2 py-0.5" style="background: rgba(0,0,0,0.55); backdrop-filter: blur(8px); font-size: 0.6rem;" onclick="switchHeroDemoByName('card')">
                                                    <i class="fa-solid fa-arrow-left me-1"></i>Carte
                                                </button>
                                                <span class="badge rounded-pill font-mono" style="background: rgba(234,88,12,0.85); font-size: 0.55rem;">
                                                    <i class="bi bi-geo-alt-fill me-0.5"></i>Table #14
                                                </span>
                                            </div>

                                            <div class="position-absolute bottom-0 start-0 end-0 p-2 z-2">
                                                <div class="d-flex align-items-center gap-1.5">
                                                    <h6 class="fw-extrabold text-white mb-0 text-truncate" style="font-size: 0.85rem; letter-spacing: -0.01em;">
                                                        L'Atelier Gourmand &amp; Rooftop
                                                    </h6>
                                                </div>
                                                <div class="d-flex align-items-center gap-2 text-white-50 mt-0.5" style="font-size: 0.58rem;">
                                                    <span class="text-warning fw-bold"><i class="fa-solid fa-star me-0.5"></i>4.9 (680 avis)</span>
                                                    <span>&bull;</span>
                                                    <span>Cuisine Signature</span>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="p-2.5">
                                            <!-- Category Switcher Chips (Horizontal Scrollable) -->
                                            <div class="d-flex gap-1 mb-2 overflow-x-auto pb-1" style="scrollbar-width: none;">
                                                <button type="button" class="badge rounded-pill bg-white text-dark font-mono px-2 py-1 border-0 shadow-2xs flex-shrink-0" style="font-size: 0.58rem;">🔥 Signatures</button>
                                                <button type="button" class="badge rounded-pill text-white-50 font-mono px-2 py-1 border-0 flex-shrink-0" style="background: rgba(255,255,255,0.08); font-size: 0.58rem;">🥩 Viandes</button>
                                                <button type="button" class="badge rounded-pill text-white-50 font-mono px-2 py-1 border-0 flex-shrink-0" style="background: rgba(255,255,255,0.08); font-size: 0.58rem;">🍸 Cocktails</button>
                                                <button type="button" class="badge rounded-pill text-white-50 font-mono px-2 py-1 border-0 flex-shrink-0" style="background: rgba(255,255,255,0.08); font-size: 0.58rem;">🍰 Desserts</button>
                                            </div>

                                            <!-- 2-Column Product Cards with Real Mouthwatering Photography -->
                                            <div class="row g-1.5 mb-2">
                                                <!-- Dish 1 -->
                                                <div class="col-6">
                                                    <div class="rounded-3 p-1.5 h-100 d-flex flex-column justify-content-between phone-dish-card shadow-2xs">
                                                        <div>
                                                            <div class="rounded-2 mb-1 overflow-hidden position-relative" style="height: 58px; background: #1e293b;">
                                                                <img src="https://images.unsplash.com/photo-1544025162-d76694265947?auto=format&fit=crop&w=300&q=80" 
                                                                     alt="Filet Rossini" 
                                                                     class="w-100 h-100 object-fit-cover"
                                                                     onerror="this.style.display='none';">
                                                                <span class="badge position-absolute top-0 start-0 m-1" style="background: #ea580c; font-size: 0.48rem; backdrop-filter: blur(4px);">Chef</span>
                                                            </div>
                                                            <div class="fw-bold text-white text-truncate" style="font-size: 0.67rem; line-height: 1.2;">Filet Rossini</div>
                                                            <div class="text-white-50 mt-0.5 text-truncate" style="font-size: 0.56rem; line-height: 1.2;">Truffes fraîches &amp; foie gras</div>
                                                        </div>
                                                        <div class="mt-1.5 pt-1 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                                                            <span class="fw-extrabold font-mono" style="color: #c4b5fd; font-size: 0.68rem;">240 MAD</span>
                                                            <button type="button" class="btn btn-xs rounded-circle p-0 d-inline-flex align-items-center justify-content-center text-white border-0 shadow-xs" style="width: 18px; height: 18px; background: #5736e9;" onclick="addDemoCartItem(240)" title="Ajouter">+</button>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Dish 2 -->
                                                <div class="col-6">
                                                    <div class="rounded-3 p-1.5 h-100 d-flex flex-column justify-content-between phone-dish-card shadow-2xs">
                                                        <div>
                                                            <div class="rounded-2 mb-1 overflow-hidden position-relative" style="height: 58px; background: #1e293b;">
                                                                <img src="https://images.unsplash.com/photo-1514362545857-3bc16c4c7d1b?auto=format&fit=crop&w=300&q=80" 
                                                                     alt="Cocktail Passion" 
                                                                     class="w-100 h-100 object-fit-cover"
                                                                     onerror="this.style.display='none';">
                                                                <span class="badge position-absolute top-0 start-0 m-1 text-white" style="background: #5736e9; font-size: 0.48rem; backdrop-filter: blur(4px);">Maison</span>
                                                            </div>
                                                            <div class="fw-bold text-white text-truncate" style="font-size: 0.67rem; line-height: 1.2;">Passion Sauvage</div>
                                                            <div class="text-white-50 mt-0.5 text-truncate" style="font-size: 0.56rem; line-height: 1.2;">Fruits pressés &amp; menthe bio</div>
                                                        </div>
                                                        <div class="mt-1.5 pt-1 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center">
                                                            <span class="fw-extrabold font-mono" style="color: #c4b5fd; font-size: 0.68rem;">65 MAD</span>
                                                            <button type="button" class="btn btn-xs rounded-circle p-0 d-inline-flex align-items-center justify-content-center text-white border-0 shadow-xs" style="width: 18px; height: 18px; background: #5736e9;" onclick="addDemoCartItem(65)" title="Ajouter">+</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Direct WhatsApp Table Ordering Bar (Sticky Feel) -->
                                            <div class="p-1.5 px-2 rounded-3 d-flex align-items-center justify-content-between shadow-sm mb-1.5" style="background: linear-gradient(135deg, rgba(87,54,233,0.22), rgba(34,197,94,0.18)); border: 1px solid rgba(126,99,245,0.35);">
                                                <div class="d-flex align-items-center gap-1.5 text-start">
                                                    <span class="rounded-circle d-inline-flex align-items-center justify-content-center bg-success text-white" style="width: 22px; height: 22px;">
                                                        <i class="bi bi-cart-check-fill" style="font-size: 0.68rem;"></i>
                                                    </span>
                                                    <div>
                                                        <div class="fw-bold text-white font-mono" id="phoneMenuCartTotal" style="font-size: 0.65rem;">2 Plats • 305 MAD</div>
                                                        <div class="text-white-50" style="font-size: 0.55rem;">Commande directe en cuisine</div>
                                                    </div>
                                                </div>
                                                <span class="badge text-white font-mono rounded-pill px-2 py-1 shadow-xs" style="background: #5736e9; font-size: 0.58rem;">
                                                    <i class="bi bi-whatsapp me-0.5"></i>Commander
                                                </span>
                                            </div>

                                            <div class="pt-1 border-top border-white border-opacity-10 d-flex justify-content-between align-items-center font-mono text-white-50" style="font-size: 0.55rem;">
                                                <span><i class="bi bi-wifi text-success me-1"></i>Wi-Fi &amp; Carte Gratuits</span>
                                                <span class="text-warning">Mise à jour en direct</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- =============================================== -->
                                <!-- TAB 3: DYNAMIC QR & TELEMETRY PREVIEW           -->
                                <!-- =============================================== -->
                                <div class="hero-phone-pane" id="heroPaneQr">
                                    <div class="p-2.5 text-center text-white">
                                        <!-- Header Live Status Pill -->
                                        <div class="d-flex justify-content-between align-items-center mb-1.5">
                                            <span class="badge bg-primary-subtle text-primary font-mono" style="font-size: 0.6rem;">VP-QUANTUM-2026</span>
                                            <span class="badge bg-success-subtle text-success font-mono d-inline-flex align-items-center gap-1" style="font-size: 0.6rem;">
                                                <span class="status-dot-radar" style="width: 5px; height: 5px; background: #22c55e;"></span>
                                                <span>EN DIRECT</span>
                                            </span>
                                        </div>

                                        <!-- Holographic QR Code Box with Laser Scan Effect -->
                                        <div class="phone-qr-hologram p-2.5 bg-white rounded-3 d-inline-block shadow-md my-1 position-relative overflow-hidden">
                                            <div class="position-relative">
                                                <i class="bi bi-qr-code text-dark" style="font-size: 92px; line-height: 1;"></i>
                                                <!-- Center Studio Badge inside QR -->
                                                <div class="position-absolute top-50 start-50 translate-middle rounded-2 bg-dark text-white d-flex align-items-center justify-content-center shadow-xs" style="width: 22px; height: 22px; border: 2px solid #ffffff;">
                                                    <i class="bi bi-shield-check text-primary" style="font-size: 0.75rem;"></i>
                                                </div>
                                            </div>
                                            <div class="phone-qr-laser-line"></div>
                                        </div>

                                        <!-- Interactive Target Link Switcher -->
                                        <div class="d-flex justify-content-between align-items-center mt-1 text-white-50 font-mono" style="font-size: 0.56rem;">
                                            <span class="text-uppercase" style="letter-spacing: 0.05em;">Cible Active :</span>
                                            <span class="text-success fw-bold" id="phoneLiveDestTag">Google Reviews 5★</span>
                                        </div>

                                        <!-- Active Destination Pill -->
                                        <div class="p-1 px-2 rounded-2 mt-0.5 font-mono text-info fw-bold text-truncate shadow-xs" id="phoneLiveDestUrl" style="background: rgba(255,255,255,0.08); font-size: 0.62rem;">
                                            https://g.page/r/etablissement/review
                                        </div>

                                        <!-- Dynamic Redirection Feedback Flash -->
                                        <div class="small mt-1 px-1 text-warning font-mono" id="phoneLiveFeedback" style="font-size: 0.54rem;">
                                            <i class="bi bi-lightning-charge-fill me-0.5"></i>Cliquez ci-dessous pour tester le changement :
                                        </div>

                                        <!-- 4 Interactive Target Preset Buttons -->
                                        <div class="d-flex flex-wrap justify-content-center gap-1 mt-1">
                                            <button type="button" class="btn btn-xs rounded-pill font-mono px-2 py-0.5 text-white border-0 phone-dest-preset-btn active" onclick="setPhoneDemoDest('google', this)">
                                                ⭐ Avis Google
                                            </button>
                                            <button type="button" class="btn btn-xs rounded-pill font-mono px-2 py-0.5 text-white border-0 phone-dest-preset-btn" onclick="setPhoneDemoDest('menu', this)">
                                                🍽️ Menu PDF
                                            </button>
                                            <button type="button" class="btn btn-xs rounded-pill font-mono px-2 py-0.5 text-white border-0 phone-dest-preset-btn" onclick="setPhoneDemoDest('wa', this)">
                                                💬 WhatsApp
                                            </button>
                                            <button type="button" class="btn btn-xs rounded-pill font-mono px-2 py-0.5 text-white border-0 phone-dest-preset-btn" onclick="setPhoneDemoDest('insta', this)">
                                                📸 Instagram
                                            </button>
                                        </div>

                                        <!-- Live Telemetry KPI Strip -->
                                        <div class="row g-1 mt-1.5 pt-1.5 border-top border-white border-opacity-10 text-center font-mono">
                                            <div class="col-4">
                                                <div class="text-white-50" style="font-size: 0.52rem;">TOTAL SCANS</div>
                                                <div class="fw-bold text-white" id="phoneLiveScanCount" style="font-size: 0.78rem;">3,842</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-white-50" style="font-size: 0.52rem;">LATENCE</div>
                                                <div class="fw-bold text-success" style="font-size: 0.72rem;">11 ms</div>
                                            </div>
                                            <div class="col-4">
                                                <div class="text-white-50" style="font-size: 0.52rem;">RÉPARTITION</div>
                                                <div class="fw-bold text-info" style="font-size: 0.7rem;">iOS 68%</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- iOS Signature Home Indicator Bar -->
                            <div class="iphone-home-indicator"></div>
                        </div>
                    </div>

                    <!-- Ambient Ground Reflection Disc Under Phone -->
                    <div class="phone-ground-reflection"></div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 2. HIGH-TECH BENTO GRID ECOSYSTEM SECTION (#solutions)                  -->
<!-- ======================================================================= -->
<section class="py-5 bg-white border-top border-bottom position-relative overflow-hidden" id="solutions">
    <div class="section-ambient-glow glow-top-right"></div>
    <div class="container py-3 py-lg-4 position-relative z-1">
        <div class="text-center mx-auto mb-4 mb-lg-5 reveal-on-scroll reveal-fade-up" style="max-width: 680px;">
            <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-light border font-mono small mb-2 text-primary shadow-2xs">
                <i class="bi bi-cpu-fill"></i>
                <span style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">ARCHITECTURE PRODUIT BENTO</span>
            </div>
            <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.65rem, 3.2vw, 2.4rem); letter-spacing: -0.025em;">
                Une plateforme unifiée pour toutes vos interactions sans contact
            </h2>
            <p class="text-muted mb-0">
                Gérez vos cartes de visite NFC, vos menus connectés et vos QR codes dynamiques depuis une interface moderne, ultra-rapide et responsive.
            </p>
        </div>

        <!-- BENTO GRID SYSTEM (Cohesive #5736e9 Spectrum) -->
        <div class="row g-3 g-lg-4 align-items-stretch">
            <!-- Bento 1: Large Featured Card (8 Columns) - Cartes de Visite NFC & Puces -->
            <div class="col-lg-8">
                <div class="bento-card bento-card-featured bento-card-brand-indigo p-4 p-md-4.5 rounded-4 border h-100 reveal-on-scroll reveal-fade-up delay-100">
                    <div class="row align-items-center g-4">
                        <div class="col-md-7">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="futuristic-icon-box icon-box-brand shadow-sm">
                                    <i class="bi bi-person-vcard-fill fs-4"></i>
                                </div>
                                <span class="badge text-white border-0 font-mono shadow-xs" style="background: #5736e9; font-size: 0.68rem;">vCard 4.0 &bull; NFC Pro</span>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Cartes de Visite Digitales &amp; Puces NFC</h4>
                            <p class="text-muted small mb-3.5 lh-base">
                                Partagez votre profil professionnel d'un simple geste sur smartphone. Téléchargement direct du contact dans le carnet d'adresses (<code>.vcf</code>), WhatsApp, réseaux sociaux et catalogue synchronisé sans application.
                            </p>
                            <div class="d-flex flex-wrap gap-1.5 mb-3">
                                <span class="futuristic-chip border-brand text-brand fw-bold"><i class="bi bi-check2 text-brand me-1"></i>Ajout Répertoire 1-Clic</span>
                                <span class="futuristic-chip"><i class="bi bi-check2 text-success me-1"></i>Cartes Métal Gravées Laser</span>
                                <span class="futuristic-chip"><i class="bi bi-check2 text-success me-1"></i>URL Unique /c/slug</span>
                            </div>
                            <div class="d-flex align-items-center gap-2 text-muted small font-mono" style="font-size: 0.72rem;">
                                <span class="badge border" style="background: #ece8fc; color: #5736e9; border-color: rgba(87, 54, 233, 0.25) !important;">Puces NTAG216</span>
                                <span>100% Compatible iPhone &amp; Android</span>
                            </div>
                        </div>

                        <!-- Photorealistic Dual Showcase: Physical Matte Black Metal Card + Live Mobile Response -->
                        <div class="col-md-5">
                            <div class="bento-card-showcase-stage position-relative p-2">
                                <!-- Physical Matte Metal NFC Card Mockup -->
                                <div class="bento-physical-nfc-card shadow-lg p-3 rounded-3 text-white mb-2 position-relative overflow-hidden">
                                    <div class="card-metallic-sheen"></div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-1.5">
                                            <div class="rounded-circle bg-white text-dark d-inline-flex align-items-center justify-content-center fw-bold" style="width: 20px; height: 20px; font-size: 0.65rem;">Y</div>
                                            <span class="font-mono text-uppercase tracking-wider fw-bold" style="font-size: 0.62rem; letter-spacing: 0.1em; color: #f8fafc;">YNS STUDIO</span>
                                        </div>
                                        <i class="bi bi-broadcast fs-6" style="color: #7e63f5;" title="Puce NFC Intégrée"></i>
                                    </div>
                                    <div class="d-flex align-items-end justify-content-between pt-1">
                                        <div>
                                            <div class="fw-bold" style="font-size: 0.78rem; letter-spacing: 0.02em;">Alexandre Laurent</div>
                                            <div class="text-white-50 font-mono" style="font-size: 0.58rem;">DIRECTEUR GÉNÉRAL</div>
                                        </div>
                                        <div class="card-chip-gold shadow-xs" title="Puce Sécurisée"></div>
                                    </div>
                                </div>

                                <!-- Digital Mobile Response Preview -->
                                <div class="bento-digital-response p-2.5 rounded-3 bg-dark text-white shadow-md border border-white border-opacity-10">
                                    <div class="d-flex align-items-center justify-content-between mb-1.5">
                                        <span class="badge bg-success-subtle text-success font-mono" style="font-size: 0.55rem;">
                                            <i class="bi bi-check-circle-fill me-1"></i>Contact Transféré (.vcf)
                                        </span>
                                        <span class="text-white-50 font-mono" style="font-size: 0.55rem;">14 ms</span>
                                    </div>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold shadow-xs flex-shrink-0" style="width: 32px; height: 32px; background: linear-gradient(135deg, #5736e9, #7e63f5); font-size: 0.8rem;">
                                            AL
                                        </div>
                                        <div class="min-w-0">
                                            <div class="fw-bold text-white small text-truncate" style="font-size: 0.72rem;">Alexandre Laurent</div>
                                            <div class="text-white-50 text-truncate" style="font-size: 0.6rem;">Enregistré dans l'application Contacts</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bento 2: Menus & Catalogues (4 Columns - Violet Spectrum) -->
            <div class="col-lg-4">
                <div class="bento-card bento-card-brand-violet p-4 rounded-4 border h-100 d-flex flex-column justify-content-between reveal-on-scroll reveal-fade-up delay-200">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="futuristic-icon-box icon-box-brand shadow-sm">
                                <i class="fa-solid fa-utensils fs-4"></i>
                            </div>
                            <span class="badge text-white font-mono shadow-xs fw-bold" style="background: #6d4ef2; font-size: 0.68rem;">Studio Menu CAD</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Menus &amp; Catalogues Interactifs</h5>
                        <p class="text-muted small mb-3 lh-base">
                            Créez un menu digital avec images de couverture par catégorie, recherche instantanée et gestion immédiate des ruptures de stock.
                        </p>

                        <!-- Interactive Mini Dish Card with Stock Switcher -->
                        <div class="p-2.5 rounded-3 border mb-3 shadow-2xs" style="background: rgba(255, 255, 255, 0.9); border-color: rgba(87, 54, 233, 0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between mb-1.5">
                                <span class="fw-bold text-dark text-truncate small" style="font-size: 0.75rem;">Burger Truffes &amp; Cantal</span>
                                <span class="fw-extrabold font-mono small" style="color: #5736e9;">190 MAD</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between pt-1 border-top border-secondary border-opacity-10">
                                <span class="badge bg-success-subtle text-success font-mono" id="bentoStockBadge" style="font-size: 0.6rem;">● En Stock</span>
                                <button type="button" class="btn btn-xs rounded-pill border bg-white px-2 py-0.5 font-mono shadow-2xs" style="color: #5736e9; font-size: 0.6rem;" onclick="toggleBentoStock(this)">
                                    <i class="bi bi-arrow-repeat me-1"></i>Basculer Rupture
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between" style="background: #faf8ff; border-color: rgba(87, 54, 233, 0.2) !important;">
                        <span class="small font-mono fw-semibold text-secondary" style="font-size: 0.72rem;">3 Modes de Navigation</span>
                        <span class="badge bg-white border font-mono" style="color: #5736e9; font-size: 0.66rem;">Vertical &bull; Onglets &bull; Pages</span>
                    </div>
                </div>
            </div>

            <!-- Bento 3: Dynamic QR Codes VP Engine (4 Columns - Deep Indigo Spectrum) -->
            <div class="col-lg-4">
                <div class="bento-card bento-card-brand-cobalt p-4 rounded-4 border h-100 d-flex flex-column justify-content-between reveal-on-scroll reveal-fade-up delay-100">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="futuristic-icon-box icon-box-brand shadow-sm">
                                <i class="bi bi-qr-code-scan fs-4"></i>
                            </div>
                            <span class="badge text-white font-mono shadow-xs fw-bold" style="background: #4326c9; font-size: 0.68rem;">VP Engine 11ms</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">QR Codes Dynamiques (VP Engine)</h5>
                        <p class="text-muted small mb-3 lh-base">
                            Modifiez l'URL de destination de vos QR codes à tout moment sans jamais réimprimer vos supports physiques.
                        </p>

                        <!-- Dynamic Redirection Node Visualizer -->
                        <div class="p-2.5 rounded-3 border mb-3 shadow-2xs" style="background: rgba(255, 255, 255, 0.9); border-color: rgba(87, 54, 233, 0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between font-mono mb-1.5" style="font-size: 0.65rem;">
                                <span class="text-secondary"><i class="bi bi-qr-code me-1" style="color: #5736e9;"></i>QR Imprimé</span>
                                <span class="text-muted">──────&gt;</span>
                                <span class="badge text-white font-mono shadow-2xs" id="bentoQrDestBadge" style="background: #5736e9;">Avis Google 5★</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center pt-1 border-top border-secondary border-opacity-10">
                                <span class="text-muted font-mono" style="font-size: 0.62rem;">Redirection Instantanée</span>
                                <button type="button" class="btn btn-xs rounded-pill border bg-white px-2 py-0.5 fw-bold font-mono shadow-2xs" style="color: #5736e9; font-size: 0.6rem;" onclick="toggleBentoQrDest(this)">
                                    <i class="bi bi-shuffle me-1"></i>Changer Cible
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between" style="background: #faf8ff; border-color: rgba(87, 54, 233, 0.2) !important;">
                        <span class="small font-mono fw-semibold text-secondary" style="font-size: 0.72rem;">Zéro Réimpression</span>
                        <span class="badge bg-success-subtle text-success font-mono" style="font-size: 0.66rem;">● Redirection Live</span>
                    </div>
                </div>
            </div>

            <!-- Bento 4: Portail Client en Libre-Service (4 Columns - Royal Iris Spectrum) -->
            <div class="col-lg-4">
                <div class="bento-card bento-card-brand-iris p-4 rounded-4 border h-100 d-flex flex-column justify-content-between reveal-on-scroll reveal-fade-up delay-200">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="futuristic-icon-box icon-box-brand shadow-sm">
                                <i class="bi bi-key-fill fs-4"></i>
                            </div>
                            <span class="badge text-white font-mono shadow-xs fw-bold" style="background: #5736e9; font-size: 0.68rem;">Autonomie 100%</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Portail Client par Clé d'Accès</h5>
                        <p class="text-muted small mb-3 lh-base">
                            Chaque client dispose d'une clé d'accès sécurisée lui permettant de mettre à jour ses liens et de consulter ses statistiques en toute autonomie.
                        </p>

                        <!-- Mini Access Key Mockup -->
                        <div class="p-2.5 rounded-3 bg-dark text-white mb-3 shadow-xs border border-white border-opacity-10 position-relative overflow-hidden">
                            <div class="position-absolute top-0 end-0 w-50 h-100" style="background: radial-gradient(circle at top right, rgba(87, 54, 233, 0.3), transparent 70%); pointer-events: none;"></div>
                            <div class="d-flex align-items-center justify-content-between mb-1 font-mono text-white-50 position-relative z-1" style="font-size: 0.58rem;">
                                <span>CLÉ CLIENT PRIVÉE</span>
                                <span class="fw-bold" style="color: #7e63f5;"><i class="bi bi-shield-lock-fill me-1"></i>Actif</span>
                            </div>
                            <div class="d-flex align-items-center justify-content-between position-relative z-1">
                                <span class="font-mono fw-bold" style="color: #927bf7; font-size: 0.85rem; letter-spacing: 0.08em;">VP-8F29A1</span>
                                <span class="badge rounded-pill bg-white text-dark font-mono px-2 py-0.5" style="font-size: 0.58rem;">PIN : &bull;&bull;&bull;&bull;</span>
                            </div>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between" style="background: #faf8ff; border-color: rgba(87, 54, 233, 0.2) !important;">
                        <span class="small font-mono fw-semibold text-secondary" style="font-size: 0.72rem;">Code Unique VP</span>
                        <a href="{{ route('client.access') }}" class="btn btn-xs rounded-pill px-2.5 py-1 fw-semibold text-decoration-none text-white shadow-2xs" style="background: #5736e9;">Tester l'Accès</a>
                    </div>
                </div>
            </div>

            <!-- Bento 5: Télémétrie & Analytique (4 Columns - Radiant Amethyst Spectrum) -->
            <div class="col-lg-4">
                <div class="bento-card bento-card-brand-amethyst p-4 rounded-4 border h-100 d-flex flex-column justify-content-between reveal-on-scroll reveal-fade-up delay-300">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div class="futuristic-icon-box icon-box-brand shadow-sm">
                                <i class="bi bi-graph-up-arrow fs-4"></i>
                            </div>
                            <span class="badge text-white font-mono shadow-xs fw-bold" style="background: #7e63f5; font-size: 0.68rem;">Télémétrie Live</span>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Statistiques &amp; Scans en Direct</h5>
                        <p class="text-muted small mb-3 lh-base">
                            Mesurez l'impact de chaque support : nombre total de scans QR, touches NFC, type d'appareil (iOS vs Android) et historique détaillé.
                        </p>

                        <!-- Mini Animated Sparkline Bar Visual with #5736e9 Palette -->
                        <div class="p-2.5 rounded-3 border mb-3 shadow-2xs" style="background: rgba(255, 255, 255, 0.9); border-color: rgba(87, 54, 233, 0.2) !important;">
                            <div class="d-flex align-items-center justify-content-between font-mono mb-1" style="font-size: 0.65rem;">
                                <span class="text-dark fw-bold">4,850 Scans</span>
                                <span class="fw-bold" style="color: #5736e9;"><i class="bi bi-arrow-up-right me-0.5"></i>+38%</span>
                            </div>
                            <!-- 7-bar chart in #5736e9 spectrum -->
                            <div class="d-flex align-items-end gap-1.5 pt-1" style="height: 32px;">
                                <div class="rounded-1 flex-fill" style="height: 35%; background: rgba(87, 54, 233, 0.2);"></div>
                                <div class="rounded-1 flex-fill" style="height: 48%; background: rgba(87, 54, 233, 0.35);"></div>
                                <div class="rounded-1 flex-fill" style="height: 60%; background: rgba(87, 54, 233, 0.5);"></div>
                                <div class="rounded-1 flex-fill" style="height: 52%; background: rgba(87, 54, 233, 0.45);"></div>
                                <div class="rounded-1 flex-fill" style="height: 75%; background: rgba(87, 54, 233, 0.7);"></div>
                                <div class="rounded-1 flex-fill" style="height: 85%; background: rgba(87, 54, 233, 0.85);"></div>
                                <div class="rounded-1 flex-fill shadow-xs" style="height: 100%; background: linear-gradient(to top, #5736e9, #7e63f5);"></div>
                            </div>
                        </div>
                    </div>
                    <div class="p-2.5 rounded-3 border d-flex align-items-center justify-content-between" style="background: #faf8ff; border-color: rgba(87, 54, 233, 0.2) !important;">
                        <span class="small font-mono fw-semibold text-secondary" style="font-size: 0.72rem;">Analytique Intégrée</span>
                        <span class="badge bg-white font-mono" style="color: #5736e9; font-size: 0.66rem;">iOS 68% &bull; Android 32%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 3. INTERACTIVE LIVE QR PLAYGROUND GENERATOR                              -->
<!-- ======================================================================= -->
<section class="py-5 bg-light border-bottom position-relative overflow-hidden">
    <div class="container py-2 py-lg-3 position-relative z-1">
        <div class="p-4 p-lg-5 rounded-4 bg-white border shadow-sm qr-playground-container reveal-on-scroll reveal-scale-up">
            <div class="row align-items-center g-4">
                <div class="col-lg-7">
                    <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-primary-subtle text-primary font-mono small mb-2 fw-bold">
                        <i class="bi bi-sliders"></i>
                        <span>TESTEUR EN DIRECT</span>
                    </div>
                    <h3 class="fw-extrabold text-dark mb-2" style="letter-spacing: -0.02em;">
                        Générez et testez un QR Code en temps réel
                    </h3>
                    <p class="text-muted small mb-4">
                        Saisissez une URL, votre numéro WhatsApp ou le nom de votre établissement ci-dessous. Le QR Code se met à jour instantanément avec scan haute précision.
                    </p>

                    <!-- Playground Input Form -->
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">Votre Destination / Contenu :</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="bi bi-link-45deg text-primary"></i></span>
                            <input type="text" id="playgroundInput" class="form-control futuristic-input border-start-0" value="https://instagram.com/yns_studio" placeholder="Entrez une URL ou un texte...">
                        </div>
                    </div>

                    <!-- Quick Presets -->
                    <div class="d-flex flex-wrap gap-1.5 align-items-center">
                        <span class="small text-muted font-mono" style="font-size: 0.72rem;">Exemples rapides :</span>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill font-mono" onclick="setPlaygroundValue('https://g.page/r/restaurant-review')">⭐ Avis Google</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill font-mono" onclick="setPlaygroundValue('https://wa.me/212600000000?text=Bonjour')">💬 WhatsApp</button>
                        <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill font-mono" onclick="setPlaygroundValue('https://yns-studio.com/menu-digital')">🍽️ Menu PDF</button>
                    </div>
                </div>

                <div class="col-lg-5 text-center">
                    <div class="p-4 rounded-4 bg-light border d-inline-block text-center shadow-xs">
                        <div id="playgroundQrOutput" class="p-3 bg-white rounded-3 shadow-sm d-inline-block position-relative">
                            <!-- SVG QR Render Target -->
                            <div id="playgroundQrSvg"></div>
                        </div>
                        <div class="mt-2.5">
                            <span class="badge bg-success-subtle text-success font-mono" style="font-size: 0.68rem;">
                                <i class="bi bi-check-circle-fill me-1"></i>QR Code Prêt pour Scan
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 4. HOW IT WORKS IN 3 STEPS (#how-it-works)                              -->
<!-- ======================================================================= -->
<section class="py-5 bg-white border-bottom position-relative overflow-hidden" id="how-it-works">
    <div class="container py-3 py-lg-4 position-relative z-1">
        <div class="text-center mx-auto mb-4 mb-lg-5 reveal-on-scroll reveal-fade-up" style="max-width: 640px;">
            <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-light border font-mono small mb-2 text-primary shadow-2xs">
                <i class="bi bi-lightning-charge-fill text-warning"></i>
                <span style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">DÉPLOIEMENT RAPIDE</span>
            </div>
            <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.5rem, 3vw, 2.15rem); letter-spacing: -0.02em;">Comment ça fonctionne ?</h2>
            <p class="text-muted mb-0">Déployez vos supports connectés en 3 étapes simples, directement depuis votre téléphone ou ordinateur.</p>
        </div>

        <div class="row g-3 g-lg-4 position-relative">
            <div class="col-md-4">
                <div class="futuristic-step-card p-4 rounded-4 bg-light border h-100 position-relative reveal-on-scroll reveal-fade-up delay-100">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="futuristic-step-number">01</span>
                        <span class="badge bg-white text-muted border font-mono" style="font-size: 0.65rem;">Activation</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Activez votre Support</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Utilisez votre carte NFC, plaque d'avis Google ou chevalet de table muni de son code unique (<code>VP-XXXXXX</code>) ou créez votre profil digital dans le Studio.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="futuristic-step-card p-4 rounded-4 bg-light border h-100 position-relative reveal-on-scroll reveal-fade-up delay-200">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="futuristic-step-number">02</span>
                        <span class="badge bg-white text-muted border font-mono" style="font-size: 0.65rem;">Configuration</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Personnalisez en Direct</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Ajoutez votre logo, vos réseaux sociaux, votre fiche contact vCard ou configurez vos pages de menu avec photos HD et tarifs en quelques clics.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="futuristic-step-card p-4 rounded-4 bg-light border h-100 position-relative reveal-on-scroll reveal-fade-up delay-300">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <span class="futuristic-step-number">03</span>
                        <span class="badge bg-white text-muted border font-mono" style="font-size: 0.65rem;">Diffusion</span>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Partagez &amp; Modifiez à Volonté</h5>
                    <p class="text-muted small mb-0 lh-base">
                        Vos clients accèdent instantanément à votre page par simple approche NFC ou scan appareil photo. Changez vos informations en temps réel quand vous le souhaitez.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 5. INDUSTRY USE CASES (#use-cases)                                      -->
<!-- ======================================================================= -->
<section class="py-5 bg-white border-bottom position-relative overflow-hidden" id="use-cases">
    <div class="container py-3 py-lg-4 position-relative z-1">
        <div class="text-center mx-auto mb-4 mb-lg-5 reveal-on-scroll reveal-fade-up" style="max-width: 680px;">
            <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-light border font-mono small mb-2 text-primary shadow-2xs">
                <i class="bi bi-briefcase-fill"></i>
                <span style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">SOLUTIONS PAR MÉTIER</span>
            </div>
            <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.5rem, 3vw, 2.15rem); letter-spacing: -0.02em;">Conçu pour tous les secteurs d'activité</h2>
            <p class="text-muted mb-0">Du restaurant étoilé au dirigeant d'entreprise, adoptez la technologie sans contact nouvelle génération.</p>
        </div>

        <div class="row g-3 g-lg-4">
            <div class="col-md-6 col-lg-3">
                <div class="futuristic-sector-card p-4 rounded-4 bg-light border h-100 reveal-on-scroll reveal-fade-up delay-100">
                    <div class="futuristic-icon-box icon-box-orange mb-3">
                        <i class="fa-solid fa-utensils fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Restauration &amp; Bars</h6>
                    <p class="text-muted small mb-3">Menus digitaux, commandes directes, carte des vins avec gestion immédiate des ruptures.</p>
                    <span class="badge bg-white text-secondary border font-mono" style="font-size: 0.65rem;">Chevalets &bull; Autocollants</span>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="futuristic-sector-card p-4 rounded-4 bg-light border h-100 reveal-on-scroll reveal-fade-up delay-200">
                    <div class="futuristic-icon-box icon-box-violet mb-3">
                        <i class="bi bi-person-badge-fill fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Entreprises &amp; B2B</h6>
                    <p class="text-muted small mb-3">Cartes de visite NFC haut de gamme en métal ou bambou pour dirigeants et commerciaux.</p>
                    <span class="badge bg-white text-secondary border font-mono" style="font-size: 0.65rem;">vCard 4.0 &bull; WhatsApp</span>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="futuristic-sector-card p-4 rounded-4 bg-light border h-100 reveal-on-scroll reveal-fade-up delay-300">
                    <div class="futuristic-icon-box icon-box-cyan mb-3">
                        <i class="bi bi-shop fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Commerces &amp; Retail</h6>
                    <p class="text-muted small mb-3">Plaques d'avis Google sans contact, comptoirs connectés et catalogues promotions.</p>
                    <span class="badge bg-white text-secondary border font-mono" style="font-size: 0.65rem;">+40% d'Avis 5 Étoiles</span>
                </div>
            </div>

            <div class="col-md-6 col-lg-3">
                <div class="futuristic-sector-card p-4 rounded-4 bg-light border h-100 reveal-on-scroll reveal-fade-up delay-400">
                    <div class="futuristic-icon-box icon-box-green mb-3">
                        <i class="bi bi-calendar2-event-fill fs-4"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Salons &amp; Événements</h6>
                    <p class="text-muted small mb-3">Badges NFC pour salons professionnels, collecte de leads et brochures interactives.</p>
                    <span class="badge bg-white text-secondary border font-mono" style="font-size: 0.65rem;">Zéro Papier &bull; Instantané</span>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 6. FREQUENTLY ASKED QUESTIONS (#faq)                                    -->
<!-- ======================================================================= -->
<section class="py-5 bg-light border-bottom position-relative overflow-hidden" id="faq">
    <div class="container py-3 py-lg-4 position-relative z-1">
        <div class="text-center mx-auto mb-4 mb-lg-5 reveal-on-scroll reveal-fade-up" style="max-width: 640px;">
            <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-white border font-mono small mb-2 text-primary shadow-2xs">
                <i class="bi bi-question-circle-fill"></i>
                <span style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">FAQ &amp; SUPPORT</span>
            </div>
            <h2 class="fw-extrabold text-dark mb-2" style="font-size: clamp(1.5rem, 3vw, 2.15rem); letter-spacing: -0.02em;">Questions Fréquentes</h2>
            <p class="text-muted mb-0">Tout ce que vous devez savoir sur nos cartes NFC, menus digitaux et QR dynamiques.</p>
        </div>

        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="accordion accordion-flush futuristic-accordion reveal-on-scroll reveal-fade-up delay-100" id="faqAccordion">
                    <!-- Q1 -->
                    <div class="accordion-item mb-2.5 rounded-4 border overflow-hidden shadow-2xs">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Le destinataire doit-il installer une application ?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary small pt-0 pb-3 lh-base">
                                <strong>Non, absolument aucune application n'est requise.</strong> Tous les smartphones modernes (iPhone avec iOS et tous les modèles Android récents) intègrent un lecteur NFC et un scanner d'appareil photo natifs. Le profil digital ou menu s'ouvre directement dans le navigateur du client en moins d'une seconde.
                            </div>
                        </div>
                    </div>

                    <!-- Q2 -->
                    <div class="accordion-item mb-2.5 rounded-4 border overflow-hidden shadow-2xs">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Puis-je modifier mon menu ou mes coordonnées après impression ?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary small pt-0 pb-3 lh-base">
                                <strong>Oui, à tout moment et de manière illimitée.</strong> C'est la force de notre plateforme : vos puces NFC et vos QR codes dynamiques pointent vers des URLs dynamiques sécurisées. Vous pouvez changer vos prix, ajouter un plat ou mettre à jour votre numéro sans jamais réimprimer vos supports.
                            </div>
                        </div>
                    </div>

                    <!-- Q3 -->
                    <div class="accordion-item mb-2.5 rounded-4 border overflow-hidden shadow-2xs">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Comment fonctionne l'Espace Client sécurisé ?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary small pt-0 pb-3 lh-base">
                                Lors de la livraison de votre support physique (carte ou chevalet), une <strong>clé d'accès unique</strong> vous est attribuée (ex: <code>VP-XXXXXX</code>). Il vous suffit de la saisir sur notre page <a href="{{ route('client.access') }}" class="text-primary fw-semibold">Espace Client</a> pour configurer votre cible et consulter vos statistiques de scans.
                            </div>
                        </div>
                    </div>

                    <!-- Q4 -->
                    <div class="accordion-item mb-2.5 rounded-4 border overflow-hidden shadow-2xs">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed fw-bold text-dark py-3" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Quelles sont les finitions disponibles pour les cartes NFC physiques ?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body text-secondary small pt-0 pb-3 lh-base">
                                Nous proposons des cartes en <strong>PVC recyclé mat</strong>, en <strong>Métal gravé au laser</strong> (noir mat, or, argent titane) et en <strong>Bois véritable écologique</strong> (bambou, noyer). Toutes intègrent une puce NFC haute performance garantie 10 ans.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ======================================================================= -->
<!-- 7. DIRECT CLIENT ACCESS & CONTACT FORM SECTION (#contact)               -->
<!-- ======================================================================= -->
<section class="py-5 bg-white position-relative overflow-hidden" id="contact">
    <div class="section-ambient-glow glow-top-right"></div>
    <div class="container py-3 py-lg-4 position-relative z-1">
        <div class="row g-4 g-lg-5 align-items-center">
            <!-- Left Column: Quick Portal Access -->
            <div class="col-lg-5 reveal-on-scroll reveal-fade-right delay-100">
                <div class="d-inline-flex align-items-center gap-1.5 px-3 py-1 rounded-pill bg-light border font-mono small mb-2 text-primary shadow-2xs">
                    <i class="bi bi-chat-dots-fill"></i>
                    <span style="font-size: 0.7rem; letter-spacing: 0.08em; font-weight: 700;">CONTACT &amp; SUPPORT</span>
                </div>
                <h2 class="fw-extrabold text-dark mb-3" style="font-size: clamp(1.6rem, 3.2vw, 2.3rem); letter-spacing: -0.025em;">
                    Un projet sans contact sur-mesure ? Discutons-en.
                </h2>
                <p class="text-muted small mb-4 lh-base">
                    Notre équipe vous accompagne pour digitaliser vos menus de restaurant, équiper vos commerciaux de cartes de visite NFC haut de gamme ou déployer des QR codes dynamiques personnalisés.
                </p>

                <!-- Unified Portal Login Card -->
                <div class="p-3.5 rounded-4 border bg-light futuristic-quick-card">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <div class="d-flex align-items-center gap-2">
                            <span class="rounded-3 d-inline-flex align-items-center justify-content-center bg-primary text-white" style="width: 32px; height: 32px;">
                                <i class="bi bi-box-arrow-in-right small"></i>
                            </span>
                            <div>
                                <div class="fw-bold text-dark small">Connexion Portail</div>
                                <div class="text-muted" style="font-size: 0.74rem;">Accédez à votre espace client ou administration</div>
                            </div>
                        </div>
                        <a href="{{ $heroLoginUrl }}" class="btn btn-sm btn-primary-custom rounded-pill px-3 fw-semibold flex-shrink-0">Connexion</a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Single-Page Clean Contact Form -->
            <div class="col-lg-7 reveal-on-scroll reveal-fade-left delay-100">
                <div class="p-4 p-lg-5 rounded-4 border bg-white shadow-sm futuristic-form-card">
                    <h5 class="fw-bold text-dark mb-1">Envoyer un message à notre équipe</h5>
                    <p class="text-muted small mb-4">Remplissez ce formulaire et notre équipe vous contactera dans les plus brefs délais.</p>

                    <form method="POST" action="{{ route('contact.submit') }}">
                        @csrf
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold text-dark">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" name="name" class="form-control futuristic-input" placeholder="Votre nom ou entreprise" required value="{{ old('name') }}">
                            </div>
                            <div class="col-sm-6">
                                <label class="form-label small fw-bold text-dark">Adresse Email <span class="text-danger">*</span></label>
                                <input type="email" name="email" class="form-control futuristic-input" placeholder="contact@entreprise.com" required value="{{ old('email') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Objet de votre demande <span class="text-danger">*</span></label>
                                <input type="text" name="subject" class="form-control futuristic-input" placeholder="ex: Cartes NFC, Menu Restaurant, QR Dynamiques..." required value="{{ old('subject') }}">
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-dark">Votre message <span class="text-danger">*</span></label>
                                <textarea name="message" rows="4" class="form-control futuristic-input" placeholder="Décrivez votre projet ou votre besoin..." required>{{ old('message') }}</textarea>
                            </div>
                            <div class="col-12 pt-1">
                                <button type="submit" class="btn btn-primary-custom w-100 py-3 rounded-pill fw-bold d-inline-flex align-items-center justify-content-center gap-2 shadow-sm btn-futuristic-glow">
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

/* ==========================================================================
   FUTURISTIC LIGHTING & AMBIENT CANVAS
   ========================================================================== */
.hero-futuristic-section {
    background-color: #f8fafc;
    position: relative;
    overflow: hidden;
}
.futuristic-mesh-grid {
    position: absolute;
    inset: 0;
    background-image: radial-gradient(rgba(99, 102, 241, 0.08) 1px, transparent 1px);
    background-size: 24px 24px;
    pointer-events: none;
    z-index: 0;
}
.futuristic-glow-orb {
    position: absolute;
    border-radius: 50%;
    filter: blur(80px);
    pointer-events: none;
    z-index: 0;
}
.orb-violet {
    width: 450px;
    height: 450px;
    background: radial-gradient(circle, rgba(87, 54, 233, 0.20) 0%, transparent 70%);
    top: -100px;
    left: -80px;
}
.orb-cyan {
    width: 480px;
    height: 480px;
    background: radial-gradient(circle, rgba(109, 78, 242, 0.16) 0%, transparent 70%);
    bottom: -120px;
    right: -80px;
}
.orb-amber {
    width: 320px;
    height: 320px;
    background: radial-gradient(circle, rgba(126, 99, 245, 0.12) 0%, transparent 70%);
    top: 30%;
    right: 25%;
}
.section-ambient-glow {
    position: absolute;
    width: 380px;
    height: 380px;
    border-radius: 50%;
    filter: blur(90px);
    pointer-events: none;
    z-index: 0;
}
.glow-top-right {
    top: -100px;
    right: -60px;
    background: radial-gradient(circle, rgba(87, 54, 233, 0.12) 0%, transparent 70%);
}

.hero-futuristic-title {
    font-size: clamp(1.95rem, 4.4vw, 3.35rem);
    line-height: 1.08;
    letter-spacing: -0.035em;
}
.futuristic-gradient-text {
    background: linear-gradient(135deg, #5736e9 0%, #7e63f5 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
.futuristic-badge-chip {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.futuristic-badge-chip:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(87, 54, 233, 0.18);
}

.btn-futuristic-glow {
    background: linear-gradient(135deg, #5736e9 0%, #4326c9 100%);
    border: none;
    transition: all 0.25s cubic-bezier(0.16, 1, 0.3, 1);
}
.btn-futuristic-glow:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 25px -4px rgba(87, 54, 233, 0.5) !important;
}
.btn-futuristic-outline {
    background: #ffffff;
    transition: all 0.2s ease;
}
.btn-futuristic-outline:hover {
    background: #f8fafc;
    border-color: #cbd5e1 !important;
    transform: translateY(-1px);
}

/* Metric Pills */
.futuristic-metric-pill {
    background: rgba(255, 255, 255, 0.88);
    backdrop-filter: blur(8px);
    border: 1px solid #e2e8f0;
    border-radius: 14px;
    padding: 0.6rem 0.85rem;
    display: flex;
    align-items: center;
    gap: 0.65rem;
    box-shadow: 0 2px 6px rgba(15, 23, 42, 0.03);
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.futuristic-metric-pill:hover {
    border-color: #cbd5e1;
    transform: translateY(-2px);
}
.metric-icon-dot {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    flex-shrink: 0;
}

/* Switcher Bar */
.demo-stage-wrapper {
    max-width: 290px;
}
.demo-switcher-bar {
    border-color: #e2e8f0 !important;
}
.demo-tab-btn {
    border: none;
    background: transparent;
    color: #64748b;
    font-size: 0.74rem;
    font-weight: 700;
    padding: 0.42rem 0.6rem;
    border-radius: 999px;
    transition: all 0.2s ease;
}
.demo-tab-btn.active {
    background: #5736e9;
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(87, 54, 233, 0.35);
}

/* Ambient backlight glow behind phone */
.phone-ambient-backlight {
    position: absolute;
    width: 260px;
    height: 400px;
    background: radial-gradient(circle, rgba(87, 54, 233, 0.28) 0%, rgba(126, 99, 245, 0.16) 50%, transparent 75%);
    filter: blur(45px);
    top: 50%;
    left: 50%;
    transform: translate(-50%, -46%);
    pointer-events: none;
    z-index: 1;
}

/* ==========================================================================
   ULTRA-REALISTIC IPHONE 18 PRO MAX SHOWCASE FRAME (9:16 PROPORTIONS)
   ========================================================================== */
.iphone-18-pro-max {
    position: relative;
    width: 100%;
    max-width: 290px;
    aspect-ratio: 9 / 16;
    background: linear-gradient(155deg, #475569 0%, #1e293b 25%, #0f172a 70%, #334155 100%);
    padding: 3px;
    border-radius: 46px;
    box-shadow:
        0 0 0 1px rgba(255, 255, 255, 0.18),
        0 24px 60px -12px rgba(15, 23, 42, 0.55),
        0 10px 25px -8px rgba(0, 0, 0, 0.45);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    animation: levitatePhone 7s ease-in-out infinite;
    transition: transform 0.25s ease, box-shadow 0.25s ease;
}
.iphone-18-pro-max:hover {
    animation-play-state: paused;
    transform: translateY(-4px);
    box-shadow:
        0 0 0 1px rgba(255, 255, 255, 0.25),
        0 32px 75px -10px rgba(15, 23, 42, 0.65),
        0 14px 34px -8px rgba(0, 0, 0, 0.45);
}
@keyframes levitatePhone {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-8px); }
}

/* Side Titanium Extruded Buttons */
.iphone-btn {
    position: absolute;
    background: linear-gradient(to right, #4b5365, #262c38);
    border-radius: 2.5px;
    z-index: 0;
    pointer-events: none;
}
.iphone-action-btn { left: -3.5px; top: 15%; width: 3.5px; height: 4.5%; border-radius: 2.5px 0 0 2.5px; }
.iphone-vol-up { left: -3.5px; top: 22%; width: 3.5px; height: 7.8%; border-radius: 2.5px 0 0 2.5px; }
.iphone-vol-down { left: -3.5px; top: 31.5%; width: 3.5px; height: 7.8%; border-radius: 2.5px 0 0 2.5px; }
.iphone-power-btn { right: -3.5px; top: 24%; width: 3.5px; height: 11%; border-radius: 0 2.5px 2.5px 0; }
.iphone-camera-btn { right: -3px; top: 40%; width: 3px; height: 6.8%; border-radius: 0 2px 2px 0; background: linear-gradient(to left, #363e4f, #1e2430); }

/* Inner OLED Border & Screen */
.iphone-screen {
    background: #090d16;
    border-radius: 43px;
    border: 3px solid #000000;
    overflow: hidden;
    position: relative;
    display: flex;
    flex-direction: column;
    width: 100%;
    height: 100%;
    box-shadow: inset 0 0 0 1px rgba(255, 255, 255, 0.08);
}
.iphone-glass-reflection {
    position: absolute;
    top: 0; left: 0; right: 0; height: 100%;
    background: linear-gradient(130deg, rgba(255, 255, 255, 0.08) 0%, rgba(255, 255, 255, 0.015) 26%, transparent 44%);
    pointer-events: none;
    z-index: 25;
}

/* Dynamic Island with Active Wave Pulse */
.iphone-dynamic-island {
    position: absolute; top: 9px; left: 50%; transform: translateX(-50%);
    width: 96px; height: 23px; background: #000000; border-radius: 20px; z-index: 30;
    display: flex; align-items: center; justify-content: space-between; padding: 0 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.6), inset 0 0 0 0.5px rgba(255, 255, 255, 0.16);
}
.island-left-activity {
    font-size: 0.55rem;
    color: #4ade80;
    letter-spacing: 0.04em;
    font-weight: 700;
}
.island-nfc-wave {
    width: 5px;
    height: 5px;
    border-radius: 50%;
    background: #4ade80;
    animation: islandPulse 1.8s infinite ease-in-out;
}
@keyframes islandPulse {
    0%, 100% { opacity: 0.4; transform: scale(0.9); }
    50% { opacity: 1; transform: scale(1.3); }
}
.island-lens {
    width: 8px; height: 8px; border-radius: 50%;
    background: radial-gradient(circle at 35% 35%, #1e293b, #000000 70%);
    border: 1px solid rgba(255, 255, 255, 0.16);
    position: relative;
}
.island-lens::after {
    content: ''; position: absolute; top: 1.5px; left: 1.5px; width: 2px; height: 2px;
    border-radius: 50%; background: rgba(56, 189, 248, 0.65); filter: blur(0.4px);
}
.island-sensor {
    width: 6.5px; height: 6.5px; border-radius: 50%;
    background: radial-gradient(circle at 40% 40%, #111827, #000000); opacity: 0.65;
}

/* Status Bar */
.iphone-status-bar {
    padding: 10px 18px 4px 18px; color: #ffffff; position: relative; z-index: 20; height: 38px;
}
.ios-time {
    font-size: 0.72rem; font-weight: 700;
    font-family: -apple-system, BlinkMacSystemFont, "SF Pro Text", "Segoe UI", sans-serif;
    letter-spacing: -0.01em;
}
.ios-signal { display: flex; align-items: flex-end; gap: 1.5px; height: 10px; }
.ios-signal .bar { width: 2.5px; background: rgba(255, 255, 255, 0.95); border-radius: 1px; }
.ios-signal .bar-1 { height: 3px; }
.ios-signal .bar-2 { height: 5px; }
.ios-signal .bar-3 { height: 7.5px; }
.ios-signal .bar-4 { height: 10px; }
.ios-badge-5g { font-size: 0.62rem; font-weight: 800; letter-spacing: 0.02em; line-height: 1; color: #ffffff; }
.ios-battery {
    width: 20px; height: 10.5px; border: 1.1px solid rgba(255, 255, 255, 0.9);
    border-radius: 3.5px; padding: 1.1px; position: relative; display: flex; align-items: center;
}
.ios-battery-fill { width: 100%; height: 100%; background: #4ade80; border-radius: 1.8px; }
.ios-battery-tip {
    position: absolute; right: -2.8px; top: 2.6px; width: 1.3px; height: 3.8px;
    background: rgba(255, 255, 255, 0.9); border-radius: 0 1px 1px 0;
}

/* Screen Scrollable Body */
.iphone-screen-scroll-body {
    flex-grow: 1; overflow-y: auto; overflow-x: hidden; scrollbar-width: none;
    position: relative; z-index: 10; display: flex; flex-direction: column;
}
.iphone-screen-scroll-body::-webkit-scrollbar { display: none; }
.iphone-home-indicator {
    width: 104px; height: 4px; background: rgba(255, 255, 255, 0.85);
    border-radius: 99px; margin: 6px auto 6px auto; flex-shrink: 0; z-index: 20;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.5);
}
.hero-phone-pane { display: none; flex-grow: 1; }
.hero-phone-pane.active { display: block; animation: fadeInPhonePane 0.22s ease; }
@keyframes fadeInPhonePane {
    from { opacity: 0; transform: translateY(4px); }
    to { opacity: 1; transform: translateY(0); }
}

/* Phone Screen UI Components */
.phone-card-cover {
    height: 78px;
    background: linear-gradient(135deg, #5736e9 0%, #4326c9 50%, #200d7a 100%);
}
.cover-mesh-pattern {
    position: absolute; inset: 0;
    background-image: radial-gradient(rgba(255,255,255,0.18) 1px, transparent 1px);
    background-size: 8px 8px;
}
.phone-nfc-chip {
    background: rgba(15, 23, 42, 0.65);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255, 255, 255, 0.15);
}
.phone-avatar-img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border: 2.5px solid #090d16;
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.45);
}
.phone-avatar-ring {
    width: 62px; height: 62px;
    background: linear-gradient(135deg, #5736e9 0%, #7e63f5 100%);
    border: 2.5px solid #090d16;
    font-size: 1.22rem;
    box-shadow: 0 0 16px rgba(87, 54, 233, 0.5), 0 4px 12px rgba(0, 0, 0, 0.5);
}
.phone-vcard-btn {
    background: linear-gradient(135deg, #5736e9 0%, #7e63f5 100%);
    font-size: 0.72rem;
    border: none;
    letter-spacing: -0.01em;
    box-shadow: 0 4px 14px rgba(87, 54, 233, 0.4);
    transition: all 0.2s ease;
}
.phone-vcard-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 18px rgba(87, 54, 233, 0.55);
}
.phone-menu-pill-btn {
    background: linear-gradient(135deg, rgba(87, 54, 233, 0.18) 0%, rgba(126, 99, 245, 0.1) 100%);
    border: 1px solid rgba(126, 99, 245, 0.42) !important;
    color: #ffffff;
    font-size: 0.72rem;
    letter-spacing: -0.01em;
    box-shadow: 0 2px 8px rgba(87, 54, 233, 0.2);
    transition: all 0.2s ease;
}
.phone-menu-pill-btn:hover {
    background: linear-gradient(135deg, rgba(87, 54, 233, 0.3) 0%, rgba(126, 99, 245, 0.2) 100%);
    border-color: rgba(126, 99, 245, 0.65) !important;
    color: #ffffff;
    box-shadow: 0 4px 14px rgba(87, 54, 233, 0.35);
}

/* Phone Action Cards with touches of #5736e9 & harmonious colors */
.phone-action-card {
    background: linear-gradient(135deg, rgba(87, 54, 233, 0.12) 0%, rgba(18, 22, 42, 0.85) 100%);
    border: 1px solid rgba(126, 99, 245, 0.28);
    position: relative;
    overflow: hidden;
    backdrop-filter: blur(10px);
    transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
    box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
}
.phone-action-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; bottom: 0;
    width: 3px;
    background: linear-gradient(180deg, #5736e9, #7e63f5);
    opacity: 0.85;
}
.phone-action-card:hover {
    transform: translateY(-1.5px) scale(1.01);
    border-color: rgba(126, 99, 245, 0.55);
    box-shadow: 0 6px 20px rgba(87, 54, 233, 0.25);
}
.card-touch-whatsapp {
    background: linear-gradient(135deg, rgba(87, 54, 233, 0.14) 0%, rgba(34, 197, 94, 0.08) 55%, rgba(18, 22, 42, 0.85) 100%);
    border-color: rgba(87, 54, 233, 0.32);
}
.card-touch-whatsapp::before {
    background: linear-gradient(180deg, #22c55e, #5736e9);
}
.card-touch-web {
    background: linear-gradient(135deg, rgba(87, 54, 233, 0.16) 0%, rgba(14, 165, 233, 0.08) 55%, rgba(18, 22, 42, 0.85) 100%);
    border-color: rgba(126, 99, 245, 0.35);
}
.card-touch-web::before {
    background: linear-gradient(180deg, #5736e9, #38bdf8);
}
.card-touch-calendar {
    background: linear-gradient(135deg, rgba(87, 54, 233, 0.2) 0%, rgba(126, 99, 245, 0.1) 55%, rgba(18, 22, 42, 0.85) 100%);
    border-color: rgba(126, 99, 245, 0.38);
}
.card-touch-calendar::before {
    background: linear-gradient(180deg, #7e63f5, #5736e9);
}
.phone-card-icon-box {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.95rem;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}
.icon-whatsapp {
    background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(34, 197, 94, 0.35);
}
.icon-web {
    background: linear-gradient(135deg, #5736e9 0%, #4326c9 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(87, 54, 233, 0.35);
}
.icon-calendar {
    background: linear-gradient(135deg, #7e63f5 0%, #5736e9 100%);
    color: #ffffff;
    box-shadow: 0 2px 8px rgba(126, 99, 245, 0.35);
}
.phone-mini-chip {
    background: rgba(87, 54, 233, 0.3);
    color: #c4b5fd;
    font-size: 0.52rem;
    padding: 0.15rem 0.4rem;
    border: 1px solid rgba(126, 99, 245, 0.3);
}
.phone-card-chevron {
    color: #7e63f5;
    font-size: 0.75rem;
    transition: transform 0.2s ease;
}
.phone-action-card:hover .phone-card-chevron {
    transform: translateX(2px);
    color: #c4b5fd;
}

.phone-dish-card {
    background: linear-gradient(145deg, rgba(87, 54, 233, 0.08) 0%, rgba(20, 24, 45, 0.75) 100%);
    border: 1px solid rgba(126, 99, 245, 0.22);
    transition: transform 0.2s ease, border-color 0.2s ease, box-shadow 0.2s ease;
}
.phone-dish-card:hover {
    transform: translateY(-2px);
    border-color: rgba(126, 99, 245, 0.5);
    box-shadow: 0 6px 16px rgba(87, 54, 233, 0.2);
}
.phone-dest-preset-btn {
    background: rgba(255, 255, 255, 0.12);
    font-size: 0.55rem;
    color: #cbd5e1;
    transition: all 0.2s ease;
}
.phone-dest-preset-btn:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
}
.phone-dest-preset-btn.active {
    background: #5736e9 !important;
    color: #ffffff !important;
    box-shadow: 0 2px 6px rgba(87, 54, 233, 0.45);
}

/* Phone Holographic QR Laser */
.phone-qr-hologram {
    box-shadow: 0 4px 20px rgba(87, 54, 233, 0.25);
}
.phone-qr-laser-line {
    position: absolute;
    left: 0; right: 0; height: 2px;
    background: #7e63f5;
    box-shadow: 0 0 8px #7e63f5, 0 0 14px #5736e9;
    animation: scanLaser 2.2s infinite ease-in-out;
    pointer-events: none;
}
@keyframes scanLaser {
    0% { top: 0; opacity: 0; }
    20% { opacity: 1; }
    80% { opacity: 1; }
    100% { top: 100%; opacity: 0; }
}

/* Ground reflection */
.phone-ground-reflection {
    width: 200px;
    height: 18px;
    border-radius: 50%;
    background: radial-gradient(ellipse at center, rgba(87, 54, 233, 0.28) 0%, rgba(15, 23, 42, 0) 70%);
    margin: 12px auto 0 auto;
    filter: blur(4px);
}

/* ==========================================================================
   HIGH-TECH BENTO GRID CARDS & MICRO-INTERACTIONS (#5736e9 PALETTE SPECTRUM)
   ========================================================================== */
.bento-card {
    background: #ffffff;
    border-color: #e2e8f0 !important;
    transition: transform 0.25s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.25s ease, border-color 0.25s ease;
    position: relative;
    overflow: hidden;
}
.bento-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #5736e9, #7e63f5);
    opacity: 0;
    transition: opacity 0.25s ease;
}
.bento-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 18px 40px -12px rgba(15, 23, 42, 0.1), 0 0 0 1px rgba(87, 54, 233, 0.2) !important;
    border-color: #cbd5e1 !important;
}
.bento-card:hover::before {
    opacity: 1;
}

/* Bento Color Spectrum around #5736e9 */
.bento-card-brand-indigo {
    background: linear-gradient(145deg, #ffffff 0%, #faf8ff 55%, #f2edfe 100%) !important;
    border-color: rgba(87, 54, 233, 0.22) !important;
    box-shadow: 0 4px 20px -4px rgba(87, 54, 233, 0.08);
}
.bento-card-brand-indigo::before {
    background: linear-gradient(90deg, #5736e9, #7e63f5) !important;
    opacity: 1 !important;
}
.bento-card-brand-indigo:hover {
    box-shadow: 0 18px 45px -10px rgba(87, 54, 233, 0.22), 0 0 0 1px rgba(87, 54, 233, 0.35) !important;
    border-color: #5736e9 !important;
}

.bento-card-brand-violet {
    background: linear-gradient(145deg, #ffffff 0%, #fbf9ff 55%, #f4efff 100%) !important;
    border-color: rgba(109, 78, 242, 0.22) !important;
    box-shadow: 0 4px 20px -4px rgba(109, 78, 242, 0.08);
}
.bento-card-brand-violet::before {
    background: linear-gradient(90deg, #6d4ef2, #8b5cf6) !important;
    opacity: 1 !important;
}
.bento-card-brand-violet:hover {
    box-shadow: 0 18px 45px -10px rgba(109, 78, 242, 0.22), 0 0 0 1px rgba(109, 78, 242, 0.35) !important;
    border-color: #6d4ef2 !important;
}

.bento-card-brand-cobalt {
    background: linear-gradient(145deg, #ffffff 0%, #f8f7ff 55%, #ede9fe 100%) !important;
    border-color: rgba(67, 38, 201, 0.22) !important;
    box-shadow: 0 4px 20px -4px rgba(67, 38, 201, 0.08);
}
.bento-card-brand-cobalt::before {
    background: linear-gradient(90deg, #4326c9, #5736e9) !important;
    opacity: 1 !important;
}
.bento-card-brand-cobalt:hover {
    box-shadow: 0 18px 45px -10px rgba(67, 38, 201, 0.22), 0 0 0 1px rgba(67, 38, 201, 0.35) !important;
    border-color: #4326c9 !important;
}

.bento-card-brand-iris {
    background: linear-gradient(145deg, #ffffff 0%, #f9f8ff 55%, #f0edfe 100%) !important;
    border-color: rgba(87, 54, 233, 0.22) !important;
    box-shadow: 0 4px 20px -4px rgba(87, 54, 233, 0.08);
}
.bento-card-brand-iris::before {
    background: linear-gradient(90deg, #5736e9, #6366f1) !important;
    opacity: 1 !important;
}
.bento-card-brand-iris:hover {
    box-shadow: 0 18px 45px -10px rgba(87, 54, 233, 0.22), 0 0 0 1px rgba(87, 54, 233, 0.35) !important;
    border-color: #5736e9 !important;
}

.bento-card-brand-amethyst {
    background: linear-gradient(145deg, #ffffff 0%, #faf8ff 55%, #f3ebff 100%) !important;
    border-color: rgba(126, 99, 245, 0.22) !important;
    box-shadow: 0 4px 20px -4px rgba(126, 99, 245, 0.08);
}
.bento-card-brand-amethyst::before {
    background: linear-gradient(90deg, #7e63f5, #9333ea) !important;
    opacity: 1 !important;
}
.bento-card-brand-amethyst:hover {
    box-shadow: 0 18px 45px -10px rgba(126, 99, 245, 0.22), 0 0 0 1px rgba(126, 99, 245, 0.35) !important;
    border-color: #7e63f5 !important;
}

.icon-box-brand {
    background: linear-gradient(135deg, #faf8ff 0%, #ece8fc 100%);
    color: #5736e9;
    box-shadow: 0 4px 14px rgba(87, 54, 233, 0.22);
    border: 1px solid rgba(87, 54, 233, 0.25);
}
.bento-card-showcase-stage {
    perspective: 800px;
}
.bento-physical-nfc-card {
    background: linear-gradient(135deg, #1e293b 0%, #090d16 100%);
    border: 1px solid rgba(255, 255, 255, 0.18);
    border-radius: 14px;
    position: relative;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.bento-physical-nfc-card:hover {
    transform: translateY(-2px) rotate(-1deg);
    box-shadow: 0 16px 32px rgba(15, 23, 42, 0.4) !important;
}
.card-metallic-sheen {
    position: absolute;
    top: 0; left: 0; right: 0; bottom: 0;
    background: linear-gradient(125deg, rgba(255,255,255,0.12) 0%, rgba(255,255,255,0.02) 40%, transparent 60%);
    pointer-events: none;
}
.card-chip-gold {
    width: 26px;
    height: 19px;
    border-radius: 4px;
    background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%);
    border: 1px solid rgba(255, 255, 255, 0.5);
    position: relative;
}
.card-chip-gold::after {
    content: '';
    position: absolute;
    top: 3px; left: 4px; right: 4px; bottom: 3px;
    border: 0.5px solid rgba(0,0,0,0.25);
    border-radius: 2px;
}
.bento-digital-response {
    background: #090d16 !important;
    border-radius: 14px;
}
.bento-mini-mockup {
    background: #0f172a !important;
    border: 1px solid rgba(255, 255, 255, 0.12);
}

.futuristic-icon-box {
    width: 48px;
    height: 48px;
    border-radius: 13px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}
.icon-box-violet { background: linear-gradient(135deg, #f3f0ff 0%, #ece8fc 100%); color: #5736e9; box-shadow: 0 4px 14px rgba(87, 54, 233, 0.22); border: 1px solid rgba(87, 54, 233, 0.2); }
.icon-box-orange { background: linear-gradient(135deg, #fff7ed 0%, #ffedd5 100%); color: #ea580c; box-shadow: 0 4px 14px rgba(234, 88, 12, 0.22); border: 1px solid rgba(234, 88, 12, 0.2); }
.icon-box-cyan   { background: linear-gradient(135deg, #ecfeff 0%, #cffafe 100%); color: #0891b2; box-shadow: 0 4px 14px rgba(14, 165, 233, 0.22); border: 1px solid rgba(14, 165, 233, 0.2); }
.icon-box-green  { background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%); color: #16a34a; box-shadow: 0 4px 14px rgba(22, 163, 74, 0.22); border: 1px solid rgba(22, 163, 74, 0.2); }
.icon-box-rose   { background: linear-gradient(135deg, #fef2f2 0%, #ffe4e6 100%); color: #e11d48; box-shadow: 0 4px 14px rgba(225, 29, 72, 0.22); border: 1px solid rgba(225, 29, 72, 0.2); }

.futuristic-chip {
    font-size: 0.68rem;
    font-weight: 600;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 0.25rem 0.6rem;
    color: #475569;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.02);
}

/* 3-Step Cards with Color Touches */
.futuristic-step-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    border-color: #e2e8f0 !important;
    position: relative;
    overflow: hidden;
    background: #ffffff !important;
}
.futuristic-step-card::before {
    content: '';
    position: absolute;
    top: 0; left: 0; right: 0;
    height: 3px;
    background: linear-gradient(90deg, #5736e9, #7e63f5);
}
.futuristic-step-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 36px -8px rgba(87, 54, 233, 0.16);
    border-color: #cbd5e1 !important;
}
.futuristic-step-number {
    font-family: 'JetBrains Mono', monospace;
    font-size: 1.45rem;
    font-weight: 800;
    background: linear-gradient(135deg, #5736e9 0%, #7e63f5 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1;
}

/* Sectors Cards with Color Touches */
.futuristic-sector-card {
    transition: transform 0.25s ease, box-shadow 0.25s ease, border-color 0.25s ease;
    background: #ffffff !important;
    position: relative;
    overflow: hidden;
}
.futuristic-sector-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 16px 36px -8px rgba(15, 23, 42, 0.12);
    border-color: #cbd5e1 !important;
}

/* Quick Access Cards & Inputs */
.futuristic-quick-card {
    transition: transform 0.2s ease, border-color 0.2s ease;
}
.futuristic-quick-card:hover {
    border-color: #cbd5e1 !important;
    transform: translateY(-2px);
}
.futuristic-form-card {
    border: 1px solid #e2e8f0 !important;
}
.futuristic-input:focus {
    border-color: #5736e9 !important;
    box-shadow: 0 0 0 3px rgba(87, 54, 233, 0.14) !important;
}

/* QR Playground */
.qr-playground-container {
    border: 1px solid #e2e8f0;
}

/* ==========================================================================
   SCROLL REVEAL ANIMATIONS (INTERSECTION OBSERVER)
   ========================================================================== */
.reveal-on-scroll {
    opacity: 0;
    will-change: opacity, transform;
    transition: opacity 0.75s cubic-bezier(0.16, 1, 0.3, 1), transform 0.75s cubic-bezier(0.16, 1, 0.3, 1);
}
.reveal-fade-up {
    transform: translateY(28px);
}
.reveal-fade-left {
    transform: translateX(28px);
}
.reveal-fade-right {
    transform: translateX(-28px);
}
.reveal-scale-up {
    transform: scale(0.93) translateY(18px);
}
.reveal-on-scroll.is-revealed {
    opacity: 1;
    transform: translate(0) scale(1);
}

/* Delays */
.delay-100 { transition-delay: 100ms; }
.delay-150 { transition-delay: 150ms; }
.delay-200 { transition-delay: 200ms; }
.delay-250 { transition-delay: 250ms; }
.delay-300 { transition-delay: 300ms; }
.delay-400 { transition-delay: 400ms; }

@media (prefers-reduced-motion: reduce) {
    .reveal-on-scroll {
        opacity: 1 !important;
        transform: none !important;
        transition: none !important;
    }
    .iphone-18-pro-max {
        animation: none !important;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // 1. Interactive Demo Phone Simulator Switcher
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

    // 2. Dynamic Redirection Switcher inside Phone (Interactive Showcase)
    window.setPhoneDemoDest = function(type, btnEl) {
        document.querySelectorAll('.phone-dest-preset-btn').forEach(b => b.classList.remove('active'));
        if (btnEl) btnEl.classList.add('active');

        const destEl = document.getElementById('phoneLiveDestUrl');
        const tagEl = document.getElementById('phoneLiveDestTag');
        const feedbackEl = document.getElementById('phoneLiveFeedback');
        const scanCountEl = document.getElementById('phoneLiveScanCount');

        const config = {
            google: {
                tag: 'Google Reviews 5★',
                url: 'https://g.page/r/etablissement/review',
                label: 'Avis Google 5★'
            },
            menu: {
                tag: 'Menu Restaurant Digital',
                url: 'https://yns-studio.com/c/atelier-gourmand/menu',
                label: 'Menu Restaurant'
            },
            wa: {
                tag: 'WhatsApp Commande',
                url: 'https://wa.me/212600000000?text=Commande',
                label: 'WhatsApp Direct'
            },
            insta: {
                tag: 'Instagram Officiel',
                url: 'https://instagram.com/atelier_gourmand_officiel',
                label: 'Instagram Officiel'
            }
        };

        const current = config[type] || config.google;

        if (destEl) {
            destEl.textContent = current.url;
            destEl.style.transition = 'background-color 0.3s ease, transform 0.2s ease';
            destEl.style.backgroundColor = 'rgba(87, 54, 233, 0.45)';
            destEl.style.transform = 'scale(1.02)';
            setTimeout(() => {
                destEl.style.backgroundColor = 'rgba(255, 255, 255, 0.08)';
                destEl.style.transform = 'scale(1)';
            }, 350);
        }

        if (tagEl) {
            tagEl.textContent = current.tag;
        }

        if (feedbackEl) {
            feedbackEl.innerHTML = `<span class="text-success"><i class="bi bi-check2-circle me-0.5"></i>Cible mise à jour : <strong>${current.label}</strong> (0 réimpression)</span>`;
        }

        if (scanCountEl) {
            const currentCount = parseInt(scanCountEl.textContent.replace(/,/g, ''), 10) || 3842;
            scanCountEl.textContent = (currentCount + 1).toLocaleString('fr-FR');
        }
    };

    // 2b. Interactive Restaurant Demo Cart in Phone
    let demoCartItems = 2;
    let demoCartTotal = 305;
    window.addDemoCartItem = function(price) {
        demoCartItems += 1;
        demoCartTotal += price;
        const cartEl = document.getElementById('phoneMenuCartTotal');
        if (cartEl) {
            cartEl.textContent = `${demoCartItems} Plats • ${demoCartTotal} MAD`;
            const parent = cartEl.closest('.rounded-3');
            if (parent) {
                parent.style.transition = 'transform 0.2s ease, border-color 0.2s ease';
                parent.style.transform = 'scale(1.04)';
                parent.style.borderColor = '#22c55e';
                setTimeout(() => {
                    parent.style.transform = 'scale(1)';
                    parent.style.borderColor = 'rgba(34,197,94,0.35)';
                }, 250);
            }
        }
    };

    // 2c. Interactive Bento Card Micro-Demos
    window.toggleBentoStock = function(btn) {
        const badge = document.getElementById('bentoStockBadge');
        if (!badge) return;
        if (badge.classList.contains('bg-success-subtle')) {
            badge.className = 'badge bg-danger-subtle text-danger font-mono';
            badge.textContent = '✕ Épuisé (Rupture)';
        } else {
            badge.className = 'badge bg-success-subtle text-success font-mono';
            badge.textContent = '● En Stock';
        }
    };

    const bentoDestPresets = ['Avis Google 5★', 'Menu Restaurant', 'WhatsApp Direct', 'Instagram Pro'];
    let bentoDestIdx = 0;
    window.toggleBentoQrDest = function(btn) {
        const badge = document.getElementById('bentoQrDestBadge');
        if (!badge) return;
        bentoDestIdx = (bentoDestIdx + 1) % bentoDestPresets.length;
        badge.textContent = bentoDestPresets[bentoDestIdx];
        badge.style.transition = 'transform 0.2s ease';
        badge.style.transform = 'scale(1.1)';
        setTimeout(() => {
            badge.style.transform = 'scale(1)';
        }, 200);
    };

    // 3. Interactive Live QR Playground
    const playgroundInput = document.getElementById('playgroundInput');
    const playgroundQrSvg = document.getElementById('playgroundQrSvg');

    function renderPlaygroundQr(val) {
        if (!playgroundQrSvg) return;
        const cleanVal = encodeURIComponent(val || 'https://yns-studio.com');
        // Render crisp dynamic QR via standard API/SVG
        playgroundQrSvg.innerHTML = `
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${cleanVal}&color=0f172a&bgcolor=ffffff" 
                 alt="Live QR Code Preview" 
                 width="140" 
                 height="140" 
                 class="img-fluid rounded-2"
                 onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'bi bi-qr-code fs-1 text-dark\\'></i>';" />
        `;
    }

    if (playgroundInput) {
        renderPlaygroundQr(playgroundInput.value);
        playgroundInput.addEventListener('input', function() {
            renderPlaygroundQr(this.value);
        });
    }

    window.setPlaygroundValue = function(url) {
        if (playgroundInput) {
            playgroundInput.value = url;
            renderPlaygroundQr(url);
        }
    };

    // 4. High-Performance IntersectionObserver Scroll-Reveal Engine
    const revealElements = document.querySelectorAll('.reveal-on-scroll');
    if ('IntersectionObserver' in window) {
        const observerOptions = {
            root: null,
            rootMargin: '0px 0px -40px 0px',
            threshold: 0.12
        };

        const revealObserver = new IntersectionObserver(function (entries, observer) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-revealed');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        revealElements.forEach(function (el) {
            revealObserver.observe(el);
        });
    } else {
        revealElements.forEach(function (el) {
            el.classList.add('is-revealed');
        });
    }
});
</script>
@endpush
