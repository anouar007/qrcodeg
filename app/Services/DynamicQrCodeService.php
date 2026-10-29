<?php

namespace App\Services;

use App\Models\DynamicQrCode;
use BaconQrCode\Common\ErrorCorrectionLevel;
use BaconQrCode\Encoder\Encoder;
use Barryvdh\DomPDF\Facade\Pdf;
use Barryvdh\DomPDF\PDF as DomPdfInstance;
use Illuminate\Support\Collection;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DynamicQrCodeService
{
    /**
     * Render vector SVG string encoding the permanent redirect route
     */
    public function renderSvg(DynamicQrCode $qr, int $size = 300, string $fgHex = '#000000', string $bgHex = '#ffffff'): string
    {
        [$fgR, $fgG, $fgB] = $this->hexToRgb($fgHex);
        [$bgR, $bgG, $bgB] = $this->hexToRgb($bgHex);

        $url = $qr->encoded_url;

        $qrGenerator = QrCode::format('svg')
            ->size($size)
            ->color($fgR, $fgG, $fgB)
            ->backgroundColor($bgR, $bgG, $bgB)
            ->errorCorrection('H')
            ->margin(1);

        return @$qrGenerator->generate($url);
    }

    /**
     * Render SVG as Base64 Data URI for inline HTML / PDF embedding
     */
    public function renderSvgDataUri(DynamicQrCode $qr, int $size = 300, string $fgHex = '#000000', string $bgHex = '#ffffff'): string
    {
        $svg = $this->renderSvg($qr, $size, $fgHex, $bgHex);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    /**
     * Render high-resolution PNG using GD canvas directly from matrix
     */
    public function renderPng(DynamicQrCode $qr, int $size = 300, string $fgHex = '#000000', string $bgHex = '#ffffff'): string
    {
        [$fgR, $fgG, $fgB] = $this->hexToRgb($fgHex);
        $isTransparent = ($bgHex === 'transparent');

        $img = imagecreatetruecolor($size, $size);

        if ($isTransparent) {
            imagealphablending($img, false);
            imagesavealpha($img, true);
            $bgColor = imagecolorallocatealpha($img, 0, 0, 0, 127);
            imagefill($img, 0, 0, $bgColor);
            imagealphablending($img, true);
        } else {
            [$bgR, $bgG, $bgB] = $this->hexToRgb($bgHex);
            $bgColor = imagecolorallocate($img, $bgR, $bgG, $bgB);
            imagefill($img, 0, 0, $bgColor);
        }

        $fgColor = imagecolorallocate($img, $fgR, $fgG, $fgB);

        $url = $qr->encoded_url;
        $qrData = Encoder::encode($url, ErrorCorrectionLevel::H(), 'UTF-8');
        $matrix = $qrData->getMatrix();
        $matrixW = $matrix->getWidth();
        $matrixH = $matrix->getHeight();
        $margin = 1;
        $totalModules = $matrixW + ($margin * 2);
        $moduleSize = $size / $totalModules;

        for ($y = 0; $y < $matrixH; $y++) {
            for ($x = 0; $x < $matrixW; $x++) {
                if ($matrix->get($x, $y) === 1) {
                    $x1 = (int) round(($x + $margin) * $moduleSize);
                    $y1 = (int) round(($y + $margin) * $moduleSize);
                    $x2 = (int) round(($x + $margin + 1) * $moduleSize) - 1;
                    $y2 = (int) round(($y + $margin + 1) * $moduleSize) - 1;
                    imagefilledrectangle($img, $x1, $y1, $x2, $y2, $fgColor);
                }
            }
        }

        ob_start();
        imagepng($img, null, 9);
        $pngData = ob_get_clean();

        return $pngData;
    }

    /**
     * Render PNG as Base64 Data URI for inline embedding in PDF
     */
    public function renderPngDataUri(DynamicQrCode $qr, int $size = 300, string $fgHex = '#000000', string $bgHex = '#ffffff'): string
    {
        $png = $this->renderPng($qr, $size, $fgHex, $bgHex);

        return 'data:image/png;base64,'.base64_encode($png);
    }

    /**
     * Generate standalone printable card PDF for the chosen template & dimension
     *
     * @param  string  $template  'google_review' | 'social_media' | 'menu'
     * @param  string  $dimension  '85x55' | 'a6'
     */
    public function generateCardPdf(DynamicQrCode $qr, string $template = 'google_review', string $dimension = '85x55'): DomPdfInstance
    {
        $validTemplates = ['google_review', 'social_media', 'menu'];
        if (! in_array($template, $validTemplates, true)) {
            $template = in_array($qr->template_type, $validTemplates, true) ? $qr->template_type : 'google_review';
        }

        // Generate base64 data URIs
        $qrPngBase64 = $this->renderPngDataUri($qr, 360);
        $qrSvgBase64 = $this->renderSvgDataUri($qr, 360);

        $viewName = "pdf.cards.{$template}";
        if (! view()->exists($viewName)) {
            $viewName = 'pdf.card-templates';
        }

        $pdf = Pdf::loadView($viewName, [
            'qr' => $qr,
            'qrPngBase64' => $qrPngBase64,
            'qrSvgBase64' => $qrSvgBase64,
            'template' => $template,
            'dimension' => $dimension,
        ]);

        if ($dimension === 'a6') {
            // 105mm x 148mm in points: 297.64pt x 419.53pt
            $pdf->setPaper([0, 0, 297.64, 419.53], 'portrait');
        } else {
            // Standard print business card dimension: 85mm x 55mm in points: 240.94pt x 155.91pt
            $pdf->setPaper([0, 0, 240.94, 155.91], 'landscape');
        }

        return $pdf;
    }

    /**
     * Generate multi-page printable PDF where each page contains one Dynamic QR Code
     * with the chosen canvas size, uploaded background design, positioned QR code, and unique code.
     *
     * @param  Collection|array  $qrCodes
     */
    public function generateBatchDesignPdf(iterable $qrCodes, array $designConfig, ?string $templateImagePath = null): DomPdfInstance
    {
        $unit = $designConfig['canvas_unit'] ?? 'px';
        $rawW = max(1, (float) ($designConfig['canvas_width'] ?? 300));
        $rawH = max(1, (float) ($designConfig['canvas_height'] ?? 300));

        // Convert dimensions to PDF points (1 in = 72 pt = 25.4 mm = 2.54 cm)
        switch ($unit) {
            case 'cm':
                $widthPt = $rawW * 28.3465;
                $heightPt = $rawH * 28.3465;
                break;
            case 'mm':
                $widthPt = $rawW * 2.83465;
                $heightPt = $rawH * 2.83465;
                break;
            case 'px':
            default:
                // 96 DPI CSS px to 72 DPI PDF pt (1 px = 0.75 pt)
                $widthPt = $rawW * 0.75;
                $heightPt = $rawH * 0.75;
                break;
        }

        // Base64 encode the template image for reliable embedding
        $bgImageBase64 = null;
        if ($templateImagePath && file_exists($templateImagePath)) {
            $mime = @mime_content_type($templateImagePath) ?: 'image/jpeg';
            $bgImageBase64 = 'data:'.$mime.';base64,'.base64_encode(file_get_contents($templateImagePath));
        }

        // Coordinates & sizes (percentages 0 - 100)
        $qrX = (float) ($designConfig['qr_x'] ?? 50);
        $qrY = (float) ($designConfig['qr_y'] ?? 47.5);
        $qrSizePct = (float) ($designConfig['qr_size'] ?? 35);
        $qrFg = $designConfig['qr_fg'] ?? '#000000';
        $qrBg = $designConfig['qr_bg'] ?? '#ffffff';

        $showCode = ! empty($designConfig['show_code']);
        $codeX = (float) ($designConfig['code_x'] ?? 50);
        $codeY = (float) ($designConfig['code_y'] ?? 74);
        $stageH = max(50, (float) ($designConfig['stage_height'] ?? 460));
        $stageW = max(50, (float) ($designConfig['stage_width'] ?? 460));

        // Proportional font sizing matching the visual studio stage exactly
        $rawFontSize = (float) ($designConfig['code_font_size'] ?? 15);
        $rawLetterSpacing = (float) ($designConfig['code_letter_spacing'] ?? 1.5);
        $codeFontSizePt = $heightPt * ($rawFontSize / $stageH);
        $codeLetterSpacingPt = $widthPt * ($rawLetterSpacing / $stageW);

        $codeColor = $designConfig['code_color'] ?? '#000000';
        $codeBg = $designConfig['code_bg'] ?? 'transparent';
        $codeFontWeight = $designConfig['code_font_weight'] ?? 'bold';
        $codeFontFamily = $designConfig['code_font_family'] ?? 'monospace';

        // Calculate absolute point values from percentages
        $qrSizePt = ($widthPt * $qrSizePct) / 100;
        $qrLeftPt = ($widthPt * $qrX / 100) - ($qrSizePt / 2);
        $qrTopPt = ($heightPt * $qrY / 100) - ($qrSizePt / 2);

        $codeWidthPt = max(60, min($widthPt * 0.8, $widthPt * 0.5));
        $codeLeftPt = ($widthPt * $codeX / 100) - ($codeWidthPt / 2);
        $codePad = ($codeBg && $codeBg !== 'transparent') ? ($codeFontSizePt * 0.25) : 0;
        $codeTopPt = ($heightPt * $codeY / 100) - ($codeFontSizePt / 2) - $codePad;

        // Pre-render QR codes as SVG vector data URI for each item
        $items = [];
        foreach ($qrCodes as $qr) {
            $svgBase64 = $this->renderSvgDataUri($qr, max(250, (int) round($qrSizePt * 3)), $qrFg, $qrBg);
            $items[] = [
                'code' => $qr->code,
                'label' => $qr->label,
                'qrPng' => $svgBase64,
            ];
        }

        $pdf = Pdf::loadView('pdf.batch-design', [
            'items' => $items,
            'bgImageBase64' => $bgImageBase64,
            'widthPt' => $widthPt,
            'heightPt' => $heightPt,
            'qrLeftPt' => $qrLeftPt,
            'qrTopPt' => $qrTopPt,
            'qrSizePt' => $qrSizePt,
            'qrBg' => $qrBg,
            'showCode' => $showCode,
            'codeWidthPt' => $codeWidthPt,
            'codeLeftPt' => $codeLeftPt,
            'codeTopPt' => $codeTopPt,
            'codePad' => $codePad,
            'codeX' => $codeX,
            'codeY' => $codeY,
            'codeFontSizePt' => $codeFontSizePt,
            'codeColor' => $codeColor,
            'codeBg' => $codeBg,
            'codeFontWeight' => $codeFontWeight,
            'codeFontFamily' => $codeFontFamily,
            'codeLetterSpacingPt' => $codeLetterSpacingPt,
        ]);

        $pdf->setPaper([0, 0, $widthPt, $heightPt], 'portrait');
        $pdf->setOption('isHtml5ParserEnabled', true);
        $pdf->setOption('isRemoteEnabled', true);

        return $pdf;
    }

    protected function hexToRgb(string $hex): array
    {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }

        if (strlen($hex) !== 6) {
            return [0, 0, 0];
        }

        return [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ];
    }
}
