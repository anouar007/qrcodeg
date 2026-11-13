@extends('layouts.client')

@section('title', 'Scanner & Identifier un Membre Fidélité')

@section('content')
<div class="container-fluid px-3 px-md-4 py-4">

    <div class="mb-4">
        <a href="{{ route('client.loyalty.index') }}" class="btn btn-sm btn-link text-decoration-none p-0 text-muted mb-1">
            <i class="bi bi-arrow-left me-1"></i> Retour aux cartes de fidélité
        </a>
        <h1 class="h3 fw-bold text-dark mb-0">Identifier &amp; Tamponner un Client</h1>
        <p class="text-muted small mb-0">Recherchez instantanément une carte par code, numéro de téléphone, UID NFC ou scan QR.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-6">

            <!-- Manual Search Card -->
            <div class="card border rounded-4 shadow-sm bg-white mb-4">
                <div class="card-body p-4 text-center">
                    <div class="rounded-circle d-inline-flex p-3 mb-3 text-white" style="background: #5736e9;">
                        <i class="bi bi-search fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Recherche Rapide en Caisse</h5>
                    <p class="text-muted small mb-4">Entrez le code de la carte (ex: <code>LC-XXXX-XXXX</code>), le téléphone ou l'identifiant NFC.</p>

                    <form action="{{ route('client.loyalty.scan') }}" method="GET" class="mb-3">
                        <div class="input-group input-group-lg mb-3">
                            <span class="input-group-text bg-light text-muted border-end-0">
                                <i class="bi bi-upc-scan"></i>
                            </span>
                            <input type="text" name="query" value="{{ request('query') }}" class="form-control border-start-0 ps-0 font-mono" placeholder="Code carte, tél ou UID NFC..." autofocus required>
                            <button type="submit" class="btn text-white px-4 fw-bold" style="background: #5736e9;">
                                Valider
                            </button>
                        </div>
                    </form>

                    <div class="d-flex flex-wrap justify-content-center gap-2 small text-muted">
                        <span><i class="bi bi-check2 text-success me-1"></i>Recherche automatique</span>
                        <span>&bull;</span>
                        <span><i class="bi bi-phone me-1"></i>Compatible WhatsApp</span>
                        <span>&bull;</span>
                        <span><i class="bi bi-broadcast me-1"></i>Lecteur NFC USB/Bluetooth</span>
                    </div>
                </div>
            </div>

            <!-- NFC Tap Instructions Box -->
            <div class="card border rounded-4 p-4 bg-white shadow-xs mb-4">
                <div class="d-flex align-items-start gap-3">
                    <div class="rounded-3 p-3 bg-warning-subtle text-warning flex-shrink-0">
                        <i class="bi bi-broadcast fs-3"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Vous utilisez des Cartes ou Puces NFC ?</h6>
                        <p class="text-muted small mb-0">
                            Si vous disposez d'un lecteur NFC ou d'un smartphone NFC, approchez la carte du client du lecteur. L'identifiant UID sera immédiatement reconnu et ouvrira la console de tamponnage en 1 seconde.
                        </p>
                    </div>
                </div>
            </div>

            <div class="text-center">
                <a href="{{ route('client.loyalty.index') }}" class="btn btn-outline-secondary rounded-pill px-4">
                    Voir toutes les cartes de fidélité
                </a>
            </div>

        </div>
    </div>

</div>
@endsection

