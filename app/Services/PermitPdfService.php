<?php

namespace App\Services;

use App\Models\Permit;
use Barryvdh\DomPDF\Facade\Pdf;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use setasign\Fpdi\Fpdi;

class PermitPdfService
{
    public function generatePdf(Permit $permit): string
    {
        $booking = $permit->booking()->with(['room', 'organization'])->first();
        $verifyUrl = url('/verifikasi/' . $permit->verification_token);

        // 1. Generate QR code dengan logo di tengah
        $qrResult = (new Builder(
            writer: new PngWriter(),
            data: $verifyUrl,
            errorCorrectionLevel: ErrorCorrectionLevel::Medium,
            size: 300,
            margin: 10,
            logoPath: storage_path('app/private/logo.png'),
            logoResizeToWidth: 40,
            logoPunchoutBackground: true,
            validateResult: false,
        ))->build();

        $qrBase64 = base64_encode($qrResult->getString());

        // 2. Render HTML view → PDF (content only)
        $html = view('pages.bookings.permit-print', compact('permit', 'booking', 'qrBase64', 'verifyUrl'))->render();

        $contentPdf = Pdf::loadHtml($html)
            ->setPaper('a4', 'portrait')
            ->output();

        // 3. FPDI: import template (polosan.pdf) sebagai background, lalu overlay content
        $pdf = new Fpdi();

        $templatePath = storage_path('app/private/polosan.pdf');
        $pdf->setSourceFile($templatePath);
        $pageId = $pdf->importPage(1);
        $templateSize = $pdf->getTemplateSize($pageId);

        $pdf->AddPage();
        $pdf->useTemplate($pageId, 0, 0, $templateSize['width'], $templateSize['height']);

        // Import content PDF overlay
        $tmpContent = tempnam(sys_get_temp_dir(), 'permit_content_') . '.pdf';
        file_put_contents($tmpContent, $contentPdf);

        $pdf->setSourceFile($tmpContent);
        $contentPageId = $pdf->importPage(1);
        $pdf->useTemplate($contentPageId, 0, 0, $templateSize['width'], $templateSize['height']);

        @unlink($tmpContent);

        return $pdf->Output('', 'S');
    }
}
