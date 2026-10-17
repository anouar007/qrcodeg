<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Client;
use App\Models\QrCode;
use App\Models\QrDocument;
use App\Services\QrCodeService;
use App\Services\QrDocumentService;
use Illuminate\Http\Request;

class QrDocumentController extends Controller
{
    public function __construct(
        protected QrDocumentService $qrDocumentService,
        protected QrCodeService $qrCodeService
    ) {}

    public function index()
    {
        $documents = QrDocument::with(['client', 'creator'])
            ->withCount('items')
            ->latest()
            ->paginate(15);

        return view('admin.qr_documents.index', compact('documents'));
    }

    public function create(Request $request)
    {
        $clients = Client::where('status', 'active')->orderBy('company_name')->get();
        $selectedClientId = $request->input('client_id');

        $query = QrCode::with('client');
        if ($selectedClientId) {
            $query->where('client_id', $selectedClientId);
        }
        $availableQrs = $query->latest()->take(100)->get();

        return view('admin.qr_documents.create', compact('clients', 'availableQrs', 'selectedClientId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:150',
            'client_id' => 'nullable|exists:clients,id',
            'paper_size' => 'required|in:a4,a5,letter',
            'columns' => 'required|integer|min:1|max:6',
            'rows' => 'required|integer|min:1|max:10',
            'qr_size_mm' => 'required|integer|min:20|max:100',
            'show_access_code' => 'nullable|boolean',
            'show_public_code' => 'nullable|boolean',
            'show_logo' => 'nullable|boolean',
            'custom_text' => 'nullable|string|max:255',
            'qr_code_ids' => 'required|array|min:1',
            'qr_code_ids.*' => 'exists:qr_codes,id',
        ]);

        $document = $this->qrDocumentService->createDocument($validated, auth('web')->user());

        ActivityLog::log(
            action: 'document_generated',
            resourceType: 'QrDocument',
            resourceId: $document->id,
            details: ['title' => $document->title, 'item_count' => count($validated['qr_code_ids'])]
        );

        return redirect()->route('admin.qr-documents.show', $document)->with('success', "Printable QR Document '{$document->title}' prepared successfully.");
    }

    public function show(QrDocument $qrDocument)
    {
        $qrDocument->load(['items.qrCode.design', 'client', 'creator']);

        $itemsWithSvg = [];
        foreach ($qrDocument->items as $item) {
            $svg = $item->qrCode ? $this->qrCodeService->renderSvg($item->qrCode, 180) : '';
            $itemsWithSvg[] = [
                'item' => $item,
                'qr' => $item->qrCode,
                'svg' => $svg,
            ];
        }

        return view('admin.qr_documents.show', compact('qrDocument', 'itemsWithSvg'));
    }

    public function print(QrDocument $qrDocument)
    {
        $qrDocument->load(['items.qrCode.design', 'client']);

        $itemsWithSvg = [];
        foreach ($qrDocument->items as $item) {
            $svg = $item->qrCode ? $this->qrCodeService->renderSvg($item->qrCode, 200) : '';
            $itemsWithSvg[] = [
                'item' => $item,
                'qr' => $item->qrCode,
                'svg' => $svg,
            ];
        }

        return view('admin.qr_documents.print', compact('qrDocument', 'itemsWithSvg'));
    }

    public function downloadPdf(QrDocument $qrDocument)
    {
        ActivityLog::log(
            action: 'document_pdf_downloaded',
            resourceType: 'QrDocument',
            resourceId: $qrDocument->id,
            details: ['title' => $qrDocument->title]
        );

        $pdf = $this->qrDocumentService->generatePdf($qrDocument);
        $filename = 'qr_document_'.$qrDocument->id.'_'.preg_replace('/[^a-zA-Z0-9_-]/', '_', $qrDocument->title).'.pdf';

        return $pdf->download($filename);
    }

    public function destroy(QrDocument $qrDocument)
    {
        $title = $qrDocument->title;
        $qrDocument->delete();

        ActivityLog::log(
            action: 'document_deleted',
            resourceType: 'QrDocument',
            resourceId: $qrDocument->id,
            details: ['title' => $title]
        );

        return redirect()->route('admin.qr-documents.index')->with('success', "Document {$title} removed.");
    }
}
