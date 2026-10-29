<?php

use App\Http\Controllers\Admin\ActivityLogController;
use App\Http\Controllers\Admin\AnalyticsController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DigitalCardController;
use App\Http\Controllers\Admin\NfcTagController;
use App\Http\Controllers\Admin\QrCodeController;
use App\Http\Controllers\Admin\QrDocumentController;
use App\Http\Controllers\Admin\QrTemplateController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\AdminAuthController;
use App\Http\Controllers\Auth\ClientAuthController;
use App\Http\Controllers\Client\ClientAnalyticsController;
use App\Http\Controllers\Client\ClientDashboardController;
use App\Http\Controllers\Client\ClientQrController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DynamicQrCodeController;
use App\Http\Controllers\DynamicQrController;
use App\Http\Controllers\Public\PublicController;
use App\Http\Controllers\Public\PublicDigitalCardController;
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

// Carte Visite Digitale (Public View & vCard Download)
Route::get('/c/{slug}', [PublicDigitalCardController::class, 'show'])->name('digital-cards.public.show');
Route::get('/c/{slug}/vcard', [PublicDigitalCardController::class, 'downloadVCard'])->name('digital-cards.public.vcard');

// NFC Touch & Telemetry Redirection Endpoint
Route::get('/nfc/{id_or_uid}', [NfcTagController::class, 'redirect'])->name('nfc.redirect');
Route::get('/n/{uid}', [PublicNfcController::class, 'handle'])->name('nfc.public.handle');

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

    // Client QR Codes
    Route::get('/qr-codes', [ClientQrController::class, 'index'])->name('qr-codes.index');
    Route::get('/qr-codes/{qrCode}/customize', [ClientQrController::class, 'customize'])->name('qr-codes.customize');
    Route::put('/qr-codes/{qrCode}/customize', [ClientQrController::class, 'update'])->name('qr-codes.update');
    Route::get('/qr-codes/{qrCode}/download/{format}', [ClientQrController::class, 'download'])->name('qr-codes.download');

    // Client Analytics
    Route::get('/analytics', [ClientAnalyticsController::class, 'index'])->name('analytics');
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
        Route::resource('users', UserController::class);
        Route::post('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
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
    });

    // Carte Visite Digitale
    Route::middleware('permission:view_digital_cards')->group(function () {
        Route::get('digital-cards/export', [DigitalCardController::class, 'exportCsv'])->name('digital-cards.export');
        Route::resource('digital-cards', DigitalCardController::class);
    });

    // NFC Tags (Chip Management, Quick Destination Swap & Telemetry)
    Route::middleware('permission:view_nfc_tags')->group(function () {
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
});
