<?php

namespace App\Services;

use App\Models\QrCode;
use App\Models\QrDocument;
use App\Models\QrDocumentItem;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

class QrDocumentService
{
    public function __construct(protected QrCodeService $qrCodeService) {}

    /**
     * Create a new QR Document with associated items
     */
    public function createDocument(array $data, ?User $creator = null): QrDocument
    {
        return DB::transaction(function () use ($data, $creator) {
            $document = QrDocument::create([
                'title' => $data['title'] ?? 'QR Print Sheet',
                'client_id' => $data['client_id'] ?? null,
                'created_by' => $creator?->id,
                'paper_size' => $data['paper_size'] ?? 'a4',
                'columns' => (int) ($data['columns'] ?? 2),
                'rows' => (int) ($data['rows'] ?? 4),
                'qr_size_mm' => (int) ($data['qr_size_mm'] ?? 45),
                'show_access_code' => (bool) ($data['show_access_code'] ?? true),
                'show_public_code' => (bool) ($data['show_public_code'] ?? true),
                'show_logo' => (bool) ($data['show_logo'] ?? true),
                'custom_text' => $data['custom_text'] ?? null,
                'logo_path' => $data['logo_path'] ?? null,
                'status' => 'ready',
            ]);

            $qrCodeIds = $data['qr_code_ids'] ?? [];
            foreach ($qrCodeIds as $order => $qrId) {
                $qr = QrCode::find($qrId);
                if ($qr) {
                    QrDocumentItem::create([
                        'qr_document_id' => $document->id,
                        'qr_code_id' => $qr->id,
                        'position_order' => $order + 1,
                        'label' => $qr->name,
                    ]);
                }
            }

            return $document;
        });
    }

    /**
     * Generate printable PDF using DomPDF
     */
    public function generatePdf(QrDocument $document)
    {
        $document->load(['items.qrCode.design', 'client', 'creator']);

        // Prepare item cards with base64 embedded QR code SVG or PNG
        $itemsWithQrs = [];
        foreach ($document->items as $item) {
            $qr = $item->qrCode;
            if ($qr) {
                $svg = $this->qrCodeService->renderSvg($qr, 180);
                $svgBase64 = 'data:image/svg+xml;base64,'.base64_encode($svg);
                $itemsWithQrs[] = [
                    'item' => $item,
                    'qr' => $qr,
                    'qr_data_uri' => $svgBase64,
                ];
            }
        }

        $paper = match ($document->paper_size) {
            'a5' => 'a5',
            'letter' => 'letter',
            default => 'a4',
        };

        $pdf = Pdf::loadView('admin.qr_documents.pdf_template', [
            'document' => $document,
            'itemsWithQrs' => $itemsWithQrs,
        ])->setPaper($paper, 'portrait')
            ->setOption('isHtml5ParserEnabled', true)
            ->setOption('isRemoteEnabled', true);

        return $pdf;
    }
}
