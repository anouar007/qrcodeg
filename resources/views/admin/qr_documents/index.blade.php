@extends('layouts.admin')

@section('title', 'Printable QR Documents')
@section('page_title', 'Printable QR Documents')
@section('page_subtitle', 'Generate high-density printable sheets (A4, A5, Letter) for physical deployment')

@section('content')
<div class="card card-custom p-4 bg-white mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h5 class="fw-bold mb-0">Printable Document Sheets</h5>
            <small class="text-muted">Multi-card sheets formatted for professional cutting and printing</small>
        </div>
        <a href="{{ route('admin.qr-documents.create') }}" class="btn btn-primary-custom rounded-pill">
            <i class="bi bi-file-earmark-plus me-1"></i> Build Print Document
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light text-uppercase tracking-wider small">
                <tr>
                    <th>Document Title</th>
                    <th>Client Organization</th>
                    <th>Format</th>
                    <th>Grid Layout</th>
                    <th>Total Cards</th>
                    <th>Created By</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($documents as $doc)
                    <tr>
                        <td>
                            <a href="{{ route('admin.qr-documents.show', $doc) }}" class="fw-bold text-dark text-decoration-none d-block">
                                {{ $doc->title }}
                            </a>
                            <small class="text-muted">{{ $doc->created_at->format('M d, Y H:i') }}</small>
                        </td>
                        <td>
                            @if($doc->client)
                                <span class="fw-semibold text-dark">{{ $doc->client->company_name }}</span>
                            @else
                                <span class="badge bg-light text-muted border">General / Multi-Client</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-secondary font-mono text-uppercase">{{ $doc->paper_size }}</span>
                        </td>
                        <td>
                            <span class="font-mono text-dark">{{ $doc->columns }} cols &times; {{ $doc->rows }} rows</span>
                        </td>
                        <td>
                            <span class="badge bg-primary rounded-pill px-3 py-1">
                                {{ $doc->items_count }} QR Cards
                            </span>
                        </td>
                        <td class="text-muted small">
                            {{ $doc->creator?->name ?? 'Admin' }}
                        </td>
                        <td class="text-end">
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.qr-documents.show', $doc) }}" class="btn btn-outline-secondary" title="Preview Grid"><i class="bi bi-eye"></i></a>
                                <a href="{{ route('admin.qr-documents.print', $doc) }}" target="_blank" class="btn btn-outline-primary" title="Print Layout"><i class="bi bi-printer"></i></a>
                                <a href="{{ route('admin.qr-documents.pdf', $doc) }}" class="btn btn-outline-danger" title="Download PDF"><i class="bi bi-file-earmark-pdf"></i></a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">
                            <i class="bi bi-file-earmark-pdf fs-2 d-block mb-2"></i>
                            No printable QR documents generated yet.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $documents->links() }}
    </div>
</div>
@endsection
