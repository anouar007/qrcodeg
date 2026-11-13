<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DigitalCardController;
use App\Http\Controllers\Admin\LoyaltyController;
use App\Http\Controllers\Admin\NfcTagController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\QrDocumentController;
use App\Http\Controllers\Admin\QrTemplateController;
use App\Http\Controllers\Admin\RestaurantMenuController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Client\ClientAnalyticsController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ClientDigitalCardController;
use App\Http\Controllers\Client\ClientDynamicQrController;
use App\Http\Controllers\Client\ClientLoyaltyController;
use App\Http\Controllers\Client\ClientMenuController;
use App\Http\Controllers\Client\ClientNfcTagController;
use App\Http\Controllers\Client\ClientProductController;
use App\Http\Controllers\Client\ClientQrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DynamicQrCodeController;
use App\Http\Controllers\DynamicQrController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Public\PublicDigitalCardController;
use App\Http\Controllers\Public\PublicLoyaltyPassController;
use App\Http\Controllers\Public\PublicMenuController;
use App\Http\Controllers\Public\PublicNfcController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Dashboard Routes (Secured by auth:web)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth:web')->name('dashboard');

/*
|--------------------------------------------------------------------------
| 1. Public Marketing Website
|--------------------------------------------------------------------------
*/
// PWA Manifest & Service Worker
Route::get('/manifest.json', function () {
    return response(file_get_contents(public_path('manifest.json')), 200, [
        'Content-Type' => 'application/manifest+json',
    ]);
});
Route::get('/sw.js', function () {
    return response(file_get_contents(public_path('sw.js')), 200, [
        'Content-Type' => 'application/javascript',
        'Service-Worker-Allowed' => '/',
    ]);
});

Route::get('/', [PublicController::class, 'index'])->name('home');
Route::get('/features', [PublicController::class, 'features'])->name('features');
Route::get('/types', [PublicController::class, 'types'])->name('types');
Route::get('/pricing', [PublicController::class, 'pricing'])->name('pricing');
Route::get('/about', [PublicController::class, 'about'])->name('about');
Route::get('/contact', [PublicController::class, 'contact'])->name('contact');
Route::post('/contact', [PublicController::class, 'submitContact'])->name('contact.submit');

/*
|--------------------------------------------------------------------------
| 2. Dynamic Endpoints: QR Codes, Digital Business Cards & NFC Tags
|--------------------------------------------------------------------------
*/
// Dynamic QR Scanner & Redirection Engine
Route::get('/qr/{code}', [DynamicQrCodeController::class, 'redirect'])->name('qr.redirect');
Route::get('/q/{publicCode}', [DynamicQrController::class, 'handle'])->name('qr.scan');
Route::get('/q/{publicCode}/vcard', [DynamicQrController::class, 'downloadVCard'])->name('qr.vcard');

// Carte Visite Digitale (Public View, Interactive Menu & vCard Download)
Route::get('/c/{slug}', [PublicDigitalCardController::class, 'show'])->name('digital-cards.public.show');
Route::get('/c/{slug}/menu', [PublicDigitalCardController::class, 'showMenu'])->name('digital-cards.public.menu');
Route::get('/c/{slug}/vcard', [PublicDigitalCardController::class, 'downloadVCard'])->name('digital-cards.public.vcard');

// NFC Touch & Telemetry Redirection Endpoint
Route::get('/nfc/{id_or_uid}', [NfcTagController::class, 'redirect'])->name('nfc.redirect');
Route::get('/n/{uid}', [PublicNfcController::class, 'handle'])->name('nfc.public.handle');

// Loyalty Digital Card Public Pass
Route::get('/loyalty/{card_code}', [PublicLoyaltyPassController::class, 'show'])->name('loyalty.public.show');

// Public Digital Restaurant & Brand Menu
Route::get('/m/{slug}', [PublicMenuController::class, 'show'])->name('menu.public.show');

/*
|--------------------------------------------------------------------------
| 3. Staff & Admin Authentication
|--------------------------------------------------------------------------
*/
Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| 4. Client Access Code Authentication & Onboarding
|--------------------------------------------------------------------------
*/
Route::prefix('client')->name('client.')->group(function () {
    Route::get('/login', [ClientAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [ClientAuthController::class, 'loginWithEmail'])->name('login.submit');
    Route::get('/access', [ClientAuthController::class, 'showAccess'])->name('access');
    Route::post('/access', [ClientAuthController::class, 'verifyAccessCode'])->name('access.verify');
    Route::get('/password/setup', [ClientAuthController::class, 'showPasswordSetup'])->name('password.setup');
    Route::post('/password/setup', [ClientAuthController::class, 'setPassword'])->name('password.set');
    Route::get('/password/enter', [ClientAuthController::class, 'showPasswordEnter'])->name('password.enter');
    Route::post('/password/enter', [ClientAuthController::class, 'loginWithPassword'])->name('password.login');
    Route::post('/logout', [ClientAuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| 5. Client Portal (Secured by client.auth middleware)
|--------------------------------------------------------------------------
*/
Route::prefix('client')->name('client.')->middleware('client.auth')->group(function () {
    Route::get('/dashboard', [ClientDashboardController::class, 'index'])->name('dashboard');

    // Central Products Hub (All products purchased/assigned)
    Route::get('/products', [ClientProductController::class, 'index'])->name('products.index');

    // Client Dynamic QR Codes (VP-Engine)
    Route::get('/dynamic-qrs', [ClientDynamicQrController::class, 'index'])->name('dynamic-qrs.index');
    Route::get('/dynamic-qrs/{dynamicQr}/edit', [ClientDynamicQrController::class, 'edit'])->name('dynamic-qrs.edit');
    Route::put('/dynamic-qrs/{dynamicQr}', [ClientDynamicQrController::class, 'update'])->name('dynamic-qrs.update');
    Route::get('/dynamic-qrs/{dynamicQr}/download/{format}', [ClientDynamicQrController::class, 'download'])->name('dynamic-qrs.download');

    // Client Digital Business Cards & Menus
    Route::get('/digital-cards', [ClientDigitalCardController::class, 'index'])->name('digital-cards.index');
    Route::get('/digital-cards/{digitalCard}/edit', [ClientDigitalCardController::class, 'edit'])->name('digital-cards.edit');
    Route::put('/digital-cards/{digitalCard}', [ClientDigitalCardController::class, 'update'])->name('digital-cards.update');
    Route::get('/digital-cards/{digitalCard}/menu', [ClientDigitalCardController::class, 'menu'])->name('digital-cards.menu');
    Route::put('/digital-cards/{digitalCard}/menu', [ClientDigitalCardController::class, 'updateMenu'])->name('digital-cards.update-menu');

    // Client NFC Tags
    Route::get('/nfc-tags', [ClientNfcTagController::class, 'index'])->name('nfc-tags.index');
    Route::get('/nfc-tags/{nfcTag}/edit', [ClientNfcTagController::class, 'edit'])->name('nfc-tags.edit');
    Route::put('/nfc-tags/{nfcTag}', [ClientNfcTagController::class, 'update'])->name('nfc-tags.update');
    Route::post('/nfc-tags/{nfcTag}/bind-nfc', [ClientNfcTagController::class, 'bindUid'])->name('nfc-tags.bind');

    // Client QR Codes (Standard)
    Route::get('/qr-codes', [ClientQrController::class, 'index'])->name('qr-codes.index');
    Route::get('/qr-codes/{qrCode}/customize', [ClientQrController::class, 'customize'])->name('qr-codes.customize');
    Route::put('/qr-codes/{qrCode}/customize', [ClientQrController::class, 'update'])->name('qr-codes.update');
    Route::get('/qr-codes/{qrCode}/download/{format}', [ClientQrController::class, 'download'])->name('qr-codes.download');

    // Client Loyalty Cards Program & Points Scanner Terminal
    Route::get('/loyalty/terminal', [ClientLoyaltyController::class, 'terminal'])->name('loyalty.terminal');
    Route::post('/loyalty/terminal/lookup', [ClientLoyaltyController::class, 'lookupCard'])->name('loyalty.terminal.lookup');
    Route::post('/loyalty/terminal/process', [ClientLoyaltyController::class, 'processPoints'])->name('loyalty.terminal.process');
    Route::get('/loyalty/settings', [ClientLoyaltyController::class, 'settings'])->name('loyalty.settings');
    Route::post('/loyalty/settings', [ClientLoyaltyController::class, 'updateSettings'])->name('loyalty.settings.update');
    Route::post('/loyalty/{loyalty}/points', [ClientLoyaltyController::class, 'adjustCardPoints'])->name('loyalty.points.adjust');
    Route::post('/loyalty/{loyalty}/bind-nfc', [ClientLoyaltyController::class, 'bindNfcUid'])->name('loyalty.bind-nfc');

    Route::get('/loyalty/scan', [ClientLoyaltyController::class, 'scan'])->name('loyalty.scan');
    Route::get('/loyalty', [ClientLoyaltyController::class, 'index'])->name('loyalty.index');
    Route::get('/loyalty/create', [ClientLoyaltyController::class, 'create'])->name('loyalty.create');
    Route::post('/loyalty', [ClientLoyaltyController::class, 'store'])->name('loyalty.store');
    Route::get('/loyalty/{loyalty}', [ClientLoyaltyController::class, 'show'])->name('loyalty.show');
    Route::put('/loyalty/{loyalty}', [ClientLoyaltyController::class, 'update'])->name('loyalty.update');
    Route::post('/loyalty/{loyalty}/stamp', [ClientLoyaltyController::class, 'stamp'])->name('loyalty.stamp');
    Route::post('/loyalty/{loyalty}/redeem', [ClientLoyaltyController::class, 'redeem'])->name('loyalty.redeem');
    Route::delete('/loyalty/{loyalty}', [ClientLoyaltyController::class, 'destroy'])->name('loyalty.destroy');

    // Client Analytics
    Route::get('/analytics', [ClientAnalyticsController::class, 'index'])->name('analytics');

    // Client Restaurant & Brand Menus
    Route::post('menus/upload-item-image', [ClientMenuController::class, 'uploadItemImage'])->name('menus.upload-item-image');
    Route::post('menus/upload-cover-image', [ClientMenuController::class, 'uploadCoverImage'])->name('menus.upload-cover-image');
    Route::resource('menus', ClientMenuController::class)->only(['index', 'show', 'edit', 'update']);
    Route::get('menus/{menu}/download-qr/{format}', [ClientMenuController::class, 'downloadQr'])->name('menus.download-qr');
});

/*
|--------------------------------------------------------------------------
| 6. Administrative Dashboard & Management (Secured by auth:web)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->name('admin.')->middleware('auth:web')->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Users & Staff
    Route::middleware('permission:view_users,manage_users')->group(function () {
        Route::get('users/export', [UserController::class, 'exportCsv'])->name('users.export');
        Route::post('users/bulk-action', [UserController::class, 'bulkAction'])->name('users.bulk-action');
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        Route::post('users/{user}/reset-password', [UserController::class, 'resetPassword'])->name('users.reset-password');
        Route::resource('users', UserController::class);
    });

    // Roles & Dynamic Permissions
    Route::middleware('permission:manage_roles,manage_permissions')->group(function () {
        Route::resource('roles', RoleController::class);
    });

    // Clients
    Route::middleware('permission:view_clients')->group(function () {
        Route::get('clients/export', [ClientController::class, 'export'])->name('clients.export');
        Route::resource('clients', ClientController::class);
        Route::post('clients/{client}/assign-qr', [ClientController::class, 'assignQr'])->name('clients.assign-qr');
        Route::post('clients/{client}/reset-access', [ClientController::class, 'resetAccess'])->name('clients.reset-access');
        Route::post('clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');
        Route::post('clients/{client}/set-credentials', [ClientController::class, 'setCredentials'])->name('clients.set-credentials');
    });

    // Carte Visite Digitale
    Route::middleware('permission:view_digital_cards')->group(function () {
        Route::get('digital-cards/export', [DigitalCardController::class, 'exportCsv'])->name('digital-cards.export');
        Route::post('digital-cards/upload-image', [DigitalCardController::class, 'uploadImage'])->name('digital-cards.upload-image');
        Route::get('digital-cards/{digital_card}/menu', [DigitalCardController::class, 'manageMenu'])->name('digital-cards.menu.edit');
        Route::put('digital-cards/{digital_card}/menu', [DigitalCardController::class, 'updateMenu'])->name('digital-cards.menu.update');
        Route::resource('digital-cards', DigitalCardController::class);
    });

    // NFC Tags (Chip Management, Quick Destination Swap & Telemetry)
    Route::middleware('permission:view_nfc_tags')->group(function () {
        Route::get('nfc-encode', [NfcTagController::class, 'encodeCenter'])->name('nfc-encode.index');
        Route::get('nfc-tags/export', [NfcTagController::class, 'exportCsv'])->name('nfc-tags.export');
        Route::put('nfc-tags/{nfcTag}/destination', [NfcTagController::class, 'swapDestination'])->name('nfc-tags.destination');
        Route::post('nfc-tags/{nfcTag}/toggle-status', [NfcTagController::class, 'toggleStatus'])->name('nfc-tags.toggle-status');
        Route::resource('nfc-tags', NfcTagController::class);
    });

    // Dynamic QR Codes Engine (VP- Identifier & Target Management)
    Route::middleware('permission:view_qr')->group(function () {
        Route::get('dynamic-qrs/export', [DynamicQrCodeController::class, 'exportCsv'])->name('dynamic-qrs.export');
        Route::post('dynamic-qrs/bulk-action', [DynamicQrCodeController::class, 'bulkAction'])->name('dynamic-qrs.bulk-action');
        Route::get('dynamic-qrs/designs', [DynamicQrCodeController::class, 'listDesigns'])->name('dynamic-qrs.designs.index');
        Route::post('dynamic-qrs/designs', [DynamicQrCodeController::class, 'storeDesign'])->name('dynamic-qrs.designs.store');
        Route::delete('dynamic-qrs/designs/{design}', [DynamicQrCodeController::class, 'destroyDesign'])->name('dynamic-qrs.designs.destroy');
        Route::post('dynamic-qrs/bulk', [DynamicQrCodeController::class, 'bulkStore'])->name('dynamic-qrs.bulk');
        Route::post('dynamic-qrs/generate-pdf', [DynamicQrCodeController::class, 'generatePdfBatch'])->name('dynamic-qrs.generate-pdf');
        Route::put('dynamic-qrs/{dynamicQr}/target', [DynamicQrCodeController::class, 'updateTarget'])->name('dynamic-qrs.target');
        Route::post('dynamic-qrs/{dynamicQr}/toggle-status', [DynamicQrCodeController::class, 'toggleStatus'])->name('dynamic-qrs.toggle-status');
        Route::get('dynamic-qrs/{dynamicQr}/pdf', [DynamicQrCodeController::class, 'exportPdf'])->name('dynamic-qrs.pdf');
        Route::get('dynamic-qrs/{dynamicQr}/svg', [DynamicQrCodeController::class, 'downloadSvg'])->name('dynamic-qrs.svg');
        Route::get('dynamic-qrs/{dynamicQr}/png', [DynamicQrCodeController::class, 'downloadPng'])->name('dynamic-qrs.png');
        Route::resource('dynamic-qrs', DynamicQrCodeController::class);

        // Dynamic QR Codes (Legacy Graphic / Stored Layouts)
        Route::post('qr-codes/preview-live', [QrCodeController::class, 'previewLive'])->name('qr-codes.preview-live');
        Route::post('qr-codes/batch-zip', [QrCodeController::class, 'downloadBatchZip'])->name('qr-codes.batch-zip');
        Route::get('qr-codes/{qrCode}/download/{format}', [QrCodeController::class, 'download'])->name('qr-codes.download');
        Route::get('qr-codes/{qrCode}/composite-image', [QrCodeController::class, 'showCompositeImage'])->name('qr-codes.composite-image');
        Route::get('qr-codes/{qrCode}/card-pdf', [QrCodeController::class, 'downloadCardPdf'])->name('qr-codes.card-pdf');
        Route::put('qr-codes/{qrCode}/destination', [QrCodeController::class, 'updateDestination'])->name('qr-codes.destination');
        Route::post('qr-codes/{qrCode}/toggle-status', [QrCodeController::class, 'toggleStatus'])->name('qr-codes.toggle-status');
        Route::resource('qr-codes', QrCodeController::class);

        // Printable QR Documents & Batch Sheet Builder
        Route::get('qr-documents/{qrDocument}/print', [QrDocumentController::class, 'print'])->name('qr-documents.print');
        Route::get('qr-documents/{qrDocument}/pdf', [QrDocumentController::class, 'downloadPdf'])->name('qr-documents.pdf');
        Route::resource('qr-documents', QrDocumentController::class);

        // QR Templates
        Route::resource('templates', QrTemplateController::class);
    });

    // Analytics & Data Export
    Route::get('analytics', [AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('analytics/export', [AnalyticsController::class, 'export'])->name('analytics.export');

    // Activity Logs
    Route::get('activity-logs', [ActivityLogController::class, 'index'])->name('activity-logs.index');

    // System Settings
    Route::middleware('permission:manage_settings')->group(function () {
        Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingController::class, 'update'])->name('settings.update');
    });

    // Global Loyalty System Management
    Route::get('loyalty', [LoyaltyController::class, 'index'])->name('loyalty.index');
    Route::post('loyalty/enroll-client', [LoyaltyController::class, 'enrollClient'])->name('loyalty.enroll-client');
    Route::delete('loyalty/clients/{client}/remove', [LoyaltyController::class, 'removeClient'])->name('loyalty.remove-client');
    Route::get('loyalty/{loyaltyCard}', [LoyaltyController::class, 'show'])->name('loyalty.show');
    Route::post('loyalty/{loyaltyCard}/toggle-status', [LoyaltyController::class, 'toggleStatus'])->name('loyalty.toggle-status');
    Route::put('loyalty/clients/{client}/limit', [LoyaltyController::class, 'updateClientLimit'])->name('loyalty.update-client-limit');

    // Restaurant & Brand Menus Studio
    Route::post('menus/upload-item-image', [RestaurantMenuController::class, 'uploadItemImage'])->name('menus.upload-item-image');
    Route::post('menus/upload-cover-image', [RestaurantMenuController::class, 'uploadCoverImage'])->name('menus.upload-cover-image');
    Route::resource('menus', RestaurantMenuController::class);
    Route::post('menus/{menu}/toggle-status', [RestaurantMenuController::class, 'toggleStatus'])->name('menus.toggle-status');
    Route::get('menus/{menu}/download-qr/{format}', [RestaurantMenuController::class, 'downloadQr'])->name('menus.download-qr');
});
