<?php

namespace App\Services;

use App\Models\QrCode as QrCodeModel;
use App\Models\QrContent;
use App\Models\QrDesign;
use App\Models\User;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use ZipArchive;

class QrCodeService
{
    /**
     * Render SVG string for a given QR code model or arbitrary text/options
     */
    public function renderSvg(QrCodeModel $qr, ?int $customSize = null): string
    {
        $design = $qr->design;
        $size = $customSize ?? ($design?->qr_size ?? 300);
        $fgHex = $design?->foreground_color ?? '#000000';
        $bgHex = $design?->background_color ?? '#ffffff';
        $ecLevel = strtoupper($design?->error_correction ?? 'M');

        [$fgR, $fgG, $fgB] = $this->hexToRgb($fgHex);
        [$bgR, $bgG, $bgB] = $this->hexToRgb($bgHex);

        $url = $qr->dynamic_url ?: url("/q/{$qr->public_code}");

        // Generate base SVG using BaconQrCode/SimpleSoftwareIO with error suppression for PHP 8.5 notices
        $qrGenerator = QrCode::format('svg')
            ->size($size)
            ->color($fgR, $fgG, $fgB)
            ->backgroundColor($bgR, $bgG, $bgB)
            ->errorCorrection($ecLevel)
            ->margin(1);

        $svg = @$qrGenerator->generate($url);

        // Apply visual framing if frame_style is configured
        if ($design && $design->frame_style && $design->frame_style !== 'none') {
            $svg = $this->applySvgFrame($svg, $design, $size, $qr->name);
        }

        return $svg;
    }

    /**
     * Render high-resolution PNG using PHP GD library
     */
    public function renderPng(QrCodeModel $qr, ?int $customSize = null): string
    {
        $design = $qr->design;
        $size = $customSize ?? ($design?->qr_size ?? 300);
        $fgHex = $design?->foreground_color ?? '#000000';
        $bgHex = $design?->background_color ?? '#ffffff';
        [$fgR, $fgG, $fgB] = $this->hexToRgb($fgHex);
        [$bgR, $bgG, $bgB] = $this->hexToRgb($bgHex);

        $url = $qr->dynamic_url ?: url("/q/{$qr->public_code}");

        // Generate SVG to parse path coordinates or rects
        $svg = $this->renderSvg($qr, $size);

        // Use GD to rasterize
        $img = imagecreatetruecolor($size, $size);
        imagealphablending($img, true);
        imagesavealpha($img, true);

        $bgColor = imagecolorallocate($img, $bgR, $bgG, $bgB);
        imagefill($img, 0, 0, $bgColor);
        $fgColor = imagecolorallocate($img, $fgR, $fgG, $fgB);

        // Extract SVG rect elements and draw them onto GD canvas
        if (preg_match_all('/<rect[^>]+x="([^"]+)"[^>]+y="([^"]+)"[^>]+width="([^"]+)"[^>]+height="([^"]+)"[^>]+fill="([^"]+)"/i', $svg, $matches, PREG_SET_ORDER)) {
            // Determine SVG viewBox or dimension
            $svgWidth = $size;
            $svgHeight = $size;
            if (preg_match('/viewBox="0 0 ([0-9\.]+) ([0-9\.]+)"/i', $svg, $vbox)) {
                $svgWidth = (float) $vbox[1];
                $svgHeight = (float) $vbox[2];
            }

            $scaleX = $size / max($svgWidth, 1);
            $scaleY = $size / max($svgHeight, 1);

            foreach ($matches as $rect) {
                $fill = strtolower($rect[5]);
                // Skip background rect
                if ($fill === strtolower($bgHex) || $fill === sprintf('#%02x%02x%02x', $bgR, $bgG, $bgB)) {
                    continue;
                }

                $rx1 = (int) round(((float) $rect[1]) * $scaleX);
                $ry1 = (int) round(((float) $rect[2]) * $scaleY);
                $rw = (int) ceil(((float) $rect[3]) * $scaleX);
                $rh = (int) ceil(((float) $rect[4]) * $scaleY);

                if ($design?->dot_style === 'dots' || $design?->dot_style === 'rounded') {
                    imagefilledellipse($img, (int) ($rx1 + $rw / 2), (int) ($ry1 + $rh / 2), $rw, $rh, $fgColor);
                } else {
                    imagefilledrectangle($img, $rx1, $ry1, $rx1 + $rw, $ry1 + $rh, $fgColor);
                }
            }
        } else {
            // Fallback direct simple QR rendering
            imagefilledrectangle($img, 10, 10, $size - 10, $size - 10, $fgColor);
        }

        // Overlay logo if present
        if ($design?->logo_path && Storage::disk('public')->exists($design->logo_path)) {
            $logoFile = Storage::disk('public')->path($design->logo_path);
            $logoInfo = @getimagesize($logoFile);
            if ($logoInfo) {
                $logoImg = match ($logoInfo[2]) {
                    IMAGETYPE_PNG => @imagecreatefrompng($logoFile),
                    IMAGETYPE_JPEG => @imagecreatefromjpeg($logoFile),
                    default => null,
                };
                if ($logoImg) {
                    $logoSizePercent = $design->logo_size ?: 20;
                    $targetLogoW = (int) ($size * ($logoSizePercent / 100));
                    $targetLogoH = (int) ($targetLogoW * ($logoInfo[1] / max($logoInfo[0], 1)));
                    $destX = (int) (($size - $targetLogoW) / 2);
                    $destY = (int) (($size - $targetLogoH) / 2);

                    // Draw white background backing for logo
                    $pad = $design->logo_padding ?: 4;
                    imagefilledrectangle($img, $destX - $pad, $destY - $pad, $destX + $targetLogoW + $pad, $destY + $targetLogoH + $pad, $bgColor);
                    imagecopyresampled($img, $logoImg, $destX, $destY, 0, 0, $targetLogoW, $targetLogoH, $logoInfo[0], $logoInfo[1]);
                    imagedestroy($logoImg);
                }
            }
        }

        ob_start();
        imagepng($img, null, 9);
        $pngData = ob_get_clean();
        imagedestroy($img);

        return $pngData;
    }

    /**
     * Wrap SVG inside custom frame with header/footer text banner
     */
    protected function applySvgFrame(string $innerSvg, QrDesign $design, int $qrSize, string $qrName): string
    {
        $frameColor = $design->frame_color ?: '#1e3a8a';
        $frameText = $design->frame_text ?: 'SCAN ME';
        $padding = 24;
        $frameHeight = 50;

        $totalWidth = $qrSize + ($padding * 2);
        $totalHeight = $qrSize + ($padding * 2) + $frameHeight;
        $bannerY = $totalHeight - $frameHeight;
        $textY = $totalHeight - 16;

        $cleanSvg = preg_replace('/<\?xml[^>]*\?>/i', '', $innerSvg);
        $cleanSvg = preg_replace('/<!DOCTYPE[^>]*>/i', '', $cleanSvg);

        $framedSvg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="{$totalWidth}" height="{$totalHeight}" viewBox="0 0 {$totalWidth} {$totalHeight}">
    <rect width="100%" height="100%" rx="16" fill="#ffffff" stroke="{$frameColor}" stroke-width="4"/>
    <g transform="translate({$padding}, {$padding})">
        {$cleanSvg}
    </g>
    <rect x="0" y="{$bannerY}" width="{$totalWidth}" height="{$frameHeight}" rx="0" fill="{$frameColor}"/>
    <text x="50%" y="{$textY}" font-family="system-ui, -apple-system, sans-serif" font-size="16" font-weight="bold" fill="#ffffff" text-anchor="middle">
        {$frameText}
    </text>
</svg>
SVG;

        return $framedSvg;
    }

    /**
     * Batch generate unique QR codes
     */
    public function batchGenerate(array $data, ?User $creator = null): array
    {
        $quantity = (int) ($data['quantity'] ?? 1);
        $clientId = $data['client_id'] ?? null;
        $type = $data['type'] ?? 'website';
        $namePrefix = $data['name_prefix'] ?? 'QR Code';
        $baseUrl = config('app.url', 'http://localhost:8000');

        $createdQrs = [];

        DB::transaction(function () use (&$createdQrs, $quantity, $clientId, $type, $namePrefix, $baseUrl, $creator, $data) {
            for ($i = 1; $i <= $quantity; $i++) {
                $publicCode = QrCodeModel::generateUniquePublicCode();
                $accessCode = QrCodeModel::generateUniqueAccessCode();
                $name = $quantity === 1 ? $namePrefix : "{$namePrefix} #".str_pad((string) $i, 3, '0', STR_PAD_LEFT);

                $targetUrl = $data['target_redirect_url'] ?? ($data['url'] ?? null);
                $cardTemplate = $data['card_template'] ?? 'google_review';

                $qr = QrCodeModel::create([
                    'public_code' => $publicCode,
                    'access_code' => $accessCode,
                    'name' => $name,
                    'description' => $data['description'] ?? null,
                    'client_id' => $clientId,
                    'type' => $type,
                    'card_template' => $cardTemplate,
                    'status' => 'active',
                    'dynamic_url' => "{$baseUrl}/q/{$publicCode}",
                    'target_redirect_url' => $targetUrl,
                    'created_by' => $creator?->id,
                ]);

                // Default content based on type
                $contentData = $this->getDefaultContentForType($type, $data);
                QrContent::create([
                    'qr_code_id' => $qr->id,
                    'type' => $type,
                    'title' => $name,
                    'content_data' => $contentData,
                ]);

                // Default design
                QrDesign::create([
                    'qr_code_id' => $qr->id,
                    'foreground_color' => $data['foreground_color'] ?? '#000000',
                    'background_color' => $data['background_color'] ?? '#ffffff',
                    'dot_style' => $data['dot_style'] ?? 'square',
                    'corner_style' => $data['corner_style'] ?? 'square',
                    'eye_style' => $data['eye_style'] ?? 'square',
                    'frame_style' => $data['frame_style'] ?? 'none',
                    'frame_text' => $data['frame_text'] ?? null,
                    'frame_color' => $data['frame_color'] ?? '#000000',
                    'error_correction' => $data['error_correction'] ?? 'M',
                    'qr_size' => (int) ($data['qr_size'] ?? 300),
                    'template_image_path' => $data['template_image_path'] ?? null,
                    'custom_template_config' => $data['custom_template_config'] ?? null,
                ]);

                $createdQrs[] = $qr;
            }
        });

        return $createdQrs;
    }

    /**
     * Create a ZIP file containing PNG, SVG and composite template files for given QR codes
     */
    public function createZipArchive(array $qrCodes, string $zipName = 'qr_codes.zip'): string
    {
        $tempPath = storage_path("app/{$zipName}");
        $zip = new ZipArchive;

        if ($zip->open($tempPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Cannot create zip file at {$tempPath}");
        }

        foreach ($qrCodes as $qr) {
            $safeName = preg_replace('/[^a-zA-Z0-9_-]/', '_', $qr->name ?: $qr->public_code);
            $svgData = $this->renderSvg($qr);
            $pngData = $this->renderPng($qr);

            $zip->addFromString("{$safeName}_{$qr->public_code}.svg", $svgData);
            $zip->addFromString("{$safeName}_{$qr->public_code}.png", $pngData);

            if ($qr->design?->template_image_path) {
                $composite = $this->renderTemplateComposite($qr);
                if ($composite) {
                    $zip->addFromString("{$safeName}_{$qr->public_code}_template.png", $composite);
                }
            }
        }

        $zip->close();

        return $tempPath;
    }

    /**
     * Render high-resolution composite plaque/poster image combining template image, QR code, corner brackets, and unique code.
     */
    public function renderTemplateComposite(QrCodeModel $qr, ?int $targetWidth = null): ?string
    {
        $design = $qr->design;
        $templatePath = $design?->template_image_path;
        if (! $templatePath) {
            return null;
        }

        $fullPath = null;
        if (Storage::disk('public')->exists($templatePath)) {
            $fullPath = Storage::disk('public')->path($templatePath);
        } elseif (file_exists(public_path($templatePath))) {
            $fullPath = public_path($templatePath);
        } elseif (file_exists($templatePath)) {
            $fullPath = $templatePath;
        }

        if (! $fullPath || ! file_exists($fullPath)) {
            return null;
        }

        $fileContent = @file_get_contents($fullPath);
        if (! $fileContent) {
            return null;
        }

        $baseImg = @imagecreatefromstring($fileContent);
        if (! $baseImg) {
            return null;
        }

        imagealphablending($baseImg, true);
        imagesavealpha($baseImg, true);

        $origW = imagesx($baseImg);
        $origH = imagesy($baseImg);

        $config = $design->custom_template_config ?? [];
        $qrXPercent = (float) ($config['qr_x'] ?? 65.5);
        $qrYPercent = (float) ($config['qr_y'] ?? 66.0);
        $qrSizePercent = (float) ($config['qr_size_percent'] ?? 24.0);

        $destW = max(30, (int) round(($qrSizePercent / 100) * $origW));
        $destH = $destW;
        $destX = (int) round(($qrXPercent / 100) * $origW);
        $destY = (int) round(($qrYPercent / 100) * $origH);

        // Render high-res QR code PNG
        $qrPng = $this->renderPng($qr, $destW);
        $qrImg = @imagecreatefromstring($qrPng);
        if ($qrImg) {
            imagecopyresampled($baseImg, $qrImg, $destX, $destY, 0, 0, $destW, $destH, imagesx($qrImg), imagesy($qrImg));
            imagedestroy($qrImg);
        }

        $pad = max(4, (int) round($destW * 0.08));

        // Draw Corner Brackets if enabled
        if (! isset($config['show_brackets']) || $config['show_brackets']) {
            $bracketHex = $config['bracket_color'] ?? '#d9a74a';
            [$brR, $brG, $brB] = $this->hexToRgb($bracketHex);
            $bracketColor = imagecolorallocate($baseImg, $brR, $brG, $brB);

            $arm = max(10, (int) round($destW * 0.22));
            $thick = max(2, (int) round($destW * 0.024));

            // Top-Left corner
            imagefilledrectangle($baseImg, $destX - $pad, $destY - $pad, $destX - $pad + $arm, $destY - $pad + $thick, $bracketColor);
            imagefilledrectangle($baseImg, $destX - $pad, $destY - $pad, $destX - $pad + $thick, $destY - $pad + $arm, $bracketColor);

            // Top-Right corner
            imagefilledrectangle($baseImg, $destX + $destW + $pad - $arm, $destY - $pad, $destX + $destW + $pad, $destY - $pad + $thick, $bracketColor);
            imagefilledrectangle($baseImg, $destX + $destW + $pad - $thick, $destY - $pad, $destX + $destW + $pad, $destY - $pad + $arm, $bracketColor);

            // Bottom-Left corner
            imagefilledrectangle($baseImg, $destX - $pad, $destY + $destH + $pad - $thick, $destX - $pad + $arm, $destY + $destH + $pad, $bracketColor);
            imagefilledrectangle($baseImg, $destX - $pad, $destY + $destH + $pad - $arm, $destX - $pad + $thick, $destY + $destH + $pad, $bracketColor);

            // Bottom-Right corner
            imagefilledrectangle($baseImg, $destX + $destW + $pad - $arm, $destY + $destH + $pad - $thick, $destX + $destW + $pad, $destY + $destH + $pad, $bracketColor);
            imagefilledrectangle($baseImg, $destX + $destW + $pad - $thick, $destY + $destH + $pad - $arm, $destX + $destW + $pad, $destY + $destH + $pad, $bracketColor);
        }

        $fontBold = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans-Bold.ttf');
        $fontRegular = base_path('vendor/dompdf/dompdf/lib/fonts/DejaVuSans.ttf');

        // Draw Top Label (e.g. "Qr Code")
        if (! empty($config['show_top_label'])) {
            $topLabelText = $config['top_label_text'] ?? 'Qr Code';
            $topColorHex = $config['top_label_color'] ?? '#334155';
            [$tlR, $tlG, $tlB] = $this->hexToRgb($topColorHex);
            $topColor = imagecolorallocate($baseImg, $tlR, $tlG, $tlB);
            $topFontSize = max(10, (int) round(($config['top_label_size'] ?? 14) * ($origW / 1000)));

            if (file_exists($fontRegular)) {
                $bbox = imagettfbbox($topFontSize, 0, $fontRegular, $topLabelText);
                $textW = abs($bbox[4] - $bbox[0]);
                $topX = (int) round($destX + ($destW / 2) - ($textW / 2));
                $topY = (int) round($destY - $pad - 10);
                imagettftext($baseImg, $topFontSize, 0, $topX, $topY, $topColor, $fontRegular, $topLabelText);
            }
        }

        // Draw Unique Tracking Code (e.g., "VPM 013" or $qr->public_code)
        $codePrefix = $config['code_prefix'] ?? '';
        $codeText = trim($codePrefix.' '.$qr->public_code);
        $codeColorHex = $config['code_color'] ?? '#1e293b';
        [$cR, $cG, $cB] = $this->hexToRgb($codeColorHex);
        $codeColor = imagecolorallocate($baseImg, $cR, $cG, $cB);
        $codeFontSize = max(12, (int) round(($config['code_size'] ?? 16) * ($origW / 1000)));

        $codePixelX = isset($config['code_x']) ? (int) round(($config['code_x'] / 100) * $origW) : null;
        $codePixelY = isset($config['code_y']) ? (int) round(($config['code_y'] / 100) * $origH) : null;

        if (file_exists($fontBold)) {
            $bbox = imagettfbbox($codeFontSize, 0, $fontBold, $codeText);
            $textW = abs($bbox[4] - $bbox[0]);
            $textH = abs($bbox[5] - $bbox[1]);

            if ($codePixelX === null || $codePixelY === null) {
                $codePixelX = (int) round($destX + ($destW / 2) - ($textW / 2));
                $codePixelY = (int) round($destY + $destH + $pad + $textH + 12);
            } else {
                $codePixelX = (int) round($codePixelX - ($textW / 2));
            }

            imagettftext($baseImg, $codeFontSize, 0, $codePixelX, $codePixelY, $codeColor, $fontBold, $codeText);
        } else {
            $defX = $codePixelX ?? (int) round($destX + ($destW / 2) - 30);
            $defY = $codePixelY ?? (int) round($destY + $destH + 20);
            imagestring($baseImg, 5, $defX, $defY, $codeText, $codeColor);
        }

        // Resample if target width specified
        if ($targetWidth && $targetWidth !== $origW) {
            $scaledH = (int) round($origH * ($targetWidth / $origW));
            $scaledImg = imagecreatetruecolor($targetWidth, $scaledH);
            imagealphablending($scaledImg, true);
            imagesavealpha($scaledImg, true);
            imagecopyresampled($scaledImg, $baseImg, 0, 0, 0, 0, $targetWidth, $scaledH, $origW, $origH);
            imagedestroy($baseImg);
            $baseImg = $scaledImg;
        }

        ob_start();
        imagepng($baseImg, null, 9);
        $compositePng = ob_get_clean();
        imagedestroy($baseImg);

        return $compositePng;
    }

    protected function getDefaultContentForType(string $type, array $data): array
    {
        return match ($type) {
            'website' => ['url' => $data['url'] ?? 'https://example.com'],
            'text' => ['text' => $data['text'] ?? 'Welcome! This is a dynamic QR Code.'],
            'contact' => [
                'name' => $data['contact_name'] ?? 'Contact Name',
                'phone' => $data['phone'] ?? '',
                'email' => $data['email'] ?? '',
                'company' => $data['company'] ?? '',
                'job_title' => $data['job_title'] ?? '',
                'website' => $data['website'] ?? '',
                'address' => $data['address'] ?? '',
            ],
            'whatsapp' => [
                'phone' => $data['whatsapp_phone'] ?? '',
                'message' => $data['whatsapp_message'] ?? 'Hello!',
            ],
            'wifi' => [
                'ssid' => $data['wifi_ssid'] ?? 'MyNetwork',
                'password' => $data['wifi_password'] ?? '',
                'encryption' => $data['wifi_encryption'] ?? 'WPA',
                'hidden' => false,
            ],
            'business' => [
                'business_name' => $data['business_name'] ?? 'My Business',
                'description' => $data['business_desc'] ?? '',
                'phone' => $data['business_phone'] ?? '',
                'email' => $data['business_email'] ?? '',
                'website' => $data['business_website'] ?? '',
                'address' => $data['business_address'] ?? '',
                'opening_hours' => $data['business_hours'] ?? '9:00 AM - 6:00 PM',
            ],
            'product' => [
                'product_name' => $data['product_name'] ?? 'Product Name',
                'description' => $data['product_desc'] ?? '',
                'price' => $data['product_price'] ?? '$0.00',
                'website' => $data['product_website'] ?? '',
            ],
            'social' => [
                'instagram' => $data['instagram'] ?? '',
                'facebook' => $data['facebook'] ?? '',
                'tiktok' => $data['tiktok'] ?? '',
                'linkedin' => $data['linkedin'] ?? '',
                'youtube' => $data['youtube'] ?? '',
                'x' => $data['x'] ?? '',
            ],
            'custom_landing' => [
                'heading' => $data['landing_heading'] ?? 'Welcome to Our Page',
                'paragraph' => $data['landing_paragraph'] ?? 'Scan and explore our offerings.',
                'blocks' => [],
            ],
            default => ['content' => 'Dynamic QR content'],
        };
    }

    /**
     * Generate standalone printable card PDF in one of the 3 templates: google_review, social_media, menu
     */
    public function generateCardPdf(QrCodeModel $qr, string $template = 'google_review'): PDF
    {
        $pngRaw = $this->renderPng($qr, 400);
        $qrPngBase64 = 'data:image/png;base64,'.base64_encode($pngRaw);

        $validTemplates = ['google_review', 'social_media', 'menu'];
        if (! in_array($template, $validTemplates)) {
            $template = 'google_review';
        }

        // 105mm x 148mm = ~297.6pt x 419.5pt (A6 Card)
        return \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.card-templates', [
            'qr' => $qr,
            'qrPngBase64' => $qrPngBase64,
            'template' => $template,
        ])->setPaper([0, 0, 297.64, 419.53], 'portrait');
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
