@extends('layouts.client')

@section('title', 'Modifier Destination — ' . $dynamicQr->code)
@section('page_title', 'Modifier la Cible du QR Code ' . $dynamicQr->code)
@section('page_subtitle', 'Mettez à jour le lien de destination sans réimprimer vos supports physiques')

@section('content')
<div class="container-fluid px-3 px-lg-4 py-3">

    <div class="mb-3">
        <a href="{{ route('client.dynamic-qrs.index') }}" class="btn btn-sm btn-outline-secondary rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i>Retour à la liste des QR dynamiques
        </a>
    </div>

    <div class="row g-4">
        <!-- Edit Form -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white">
                <div class="d-flex align-items-center justify-content-between pb-3 border-bottom mb-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Configuration de Destination</h5>
                        <small class="text-muted">Code permanent : <strong class="font-mono text-primary">{{ $dynamicQr->code }}</strong></small>
                    </div>
                    <span class="badge {{ $dynamicQr->status === 'active' ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill font-mono px-3 py-1.5">
                        ● {{ ucfirst($dynamicQr->status) }}
                    </span>
                </div>

                <form method="POST" action="{{ route('client.dynamic-qrs.update', $dynamicQr) }}">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="label" class="form-label fw-semibold text-dark small">Libellé / Nom du QR Code</label>
                        <input type="text" name="label" id="label" class="form-control @error('label') is-invalid @enderror" value="{{ old('label', $dynamicQr->label) }}" required>
                        <small class="text-muted">Ex: Chevalet Table 4, Comptoir Accueil, Flyer Promo, Carte Visite</small>
                        @error('label')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="target_url" class="form-label fw-semibold text-dark small">URL de Destination Active (Lien vers lequel rediriger)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light text-primary"><i class="bi bi-link-45deg fs-5"></i></span>
                            <input type="url" name="target_url" id="target_url" class="form-control font-mono @error('target_url') is-invalid @enderror" value="{{ old('target_url', $dynamicQr->target_url) }}" required placeholder="https://votresite.com/page">
                        </div>
                        <small class="text-muted mt-1 d-block">Dès l'enregistrement, toute personne qui scanne votre QR code physique sera instantanément envoyée vers ce lien.</small>
                        @error('target_url')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Quick Preset Switcher Chips -->
                    <div class="mb-4 p-3 rounded-3 bg-light border">
                        <small class="fw-bold text-dark d-block mb-2"><i class="bi bi-lightning-charge-fill text-warning me-1"></i>Modèles de redirection rapide :</small>
                        <div class="d-flex flex-wrap gap-1.5">
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="setTarget('https://g.page/r/etablissement/review')">⭐ Avis Google 5★</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="setTarget('https://wa.me/212600000000?text=Bonjour')">💬 WhatsApp Direct</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="setTarget('https://instagram.com/mon_etablissement')">📸 Instagram</button>
                            <button type="button" class="btn btn-xs btn-outline-secondary rounded-pill" onclick="setTarget('https://monetablissement.com/menu.pdf')">🍽️ Menu PDF</button>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="template_type" class="form-label fw-semibold text-dark small">Type d'expérience / Catégorie</label>
                        <select name="template_type" id="template_type" class="form-select">
                            <option value="google_review" {{ old('template_type', $dynamicQr->template_type) === 'google_review' ? 'selected' : '' }}>Avis Google (Google Review)</option>
                            <option value="social_media" {{ old('template_type', $dynamicQr->template_type) === 'social_media' ? 'selected' : '' }}>Réseaux Sociaux / WhatsApp</option>
                            <option value="menu" {{ old('template_type', $dynamicQr->template_type) === 'menu' ? 'selected' : '' }}>Menu Restaurant / Catalogue</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <a href="{{ route('client.dynamic-qrs.index') }}" class="btn btn-sm btn-light border rounded-pill px-4">Annuler</a>
                        <button type="submit" class="btn btn-sm btn-primary-custom rounded-pill px-4 py-2 fw-bold shadow-xs">
                            <i class="bi bi-check-circle-fill me-1"></i>Enregistrer et Publier la Cible
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- QR Live Preview -->
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 p-4 text-center bg-white sticky-top" style="top: 20px;">
                <h6 class="fw-bold text-dark mb-1">Aperçu du QR Code Imprimé</h6>
                <p class="text-muted small mb-3">Scannable avec n'importe quel smartphone iOS &amp; Android</p>

                <div class="p-3 bg-white rounded-3 border d-inline-block shadow-xs mx-auto mb-3">
                    {!! $svgPreview !!}
                </div>

                <div class="font-mono text-dark fw-extrabold fs-5 mb-1">{{ $dynamicQr->code }}</div>
                <div class="text-muted small font-mono mb-3">
                    <span class="badge bg-light text-dark border font-mono">Total scans : {{ number_format($dynamicQr->scans_count) }}</span>
                </div>

                <div class="d-flex gap-2 justify-content-center">
                    <a href="{{ route('client.dynamic-qrs.download', ['dynamicQr' => $dynamicQr, 'format' => 'png']) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                        <i class="bi bi-download me-1"></i>PNG
                    </a>
                    <a href="{{ route('client.dynamic-qrs.download', ['dynamicQr' => $dynamicQr, 'format' => 'svg']) }}" class="btn btn-sm btn-outline-info rounded-pill px-3">
                        <i class="bi bi-bezier2 me-1"></i>SVG
                    </a>
                    <a href="{{ route('client.dynamic-qrs.download', ['dynamicQr' => $dynamicQr, 'format' => 'pdf']) }}" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                        <i class="bi bi-file-pdf me-1"></i>PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function setTarget(url) {
    const input = document.getElementById('target_url');
    if (input) {
        input.value = url;
        input.style.transition = 'background-color 0.3s ease';
        input.style.backgroundColor = '#faf8ff';
        setTimeout(() => { input.style.backgroundColor = '#ffffff'; }, 300);
    }
}
</script>
@endpush
@endsection

