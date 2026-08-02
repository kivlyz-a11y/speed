<?php

namespace App\Libraries;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Encoding\Encoding;

class QrCodeHelper
{
    /**
     * Generate QR Code as Data URI string (base64 PNG) for inline rendering in HTML/PDF
     */
    public static function generateDataUri(string $text): string
    {
        try {
            $qrCode = new QrCode(
                data: $text,
                encoding: new Encoding('UTF-8'),
                size: 200,
                margin: 10,
                foregroundColor: new Color(15, 34, 64), // Cititrans Navy Color
                backgroundColor: new Color(255, 255, 255)
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);

            return $result->getDataUri();
        } catch (\Throwable $th) {
            log_message('error', 'QR Code generation failed: ' . $th->getMessage());
            // Inline fallback SVG/PNG placeholder
            return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="200" height="200"><rect width="200" height="200" fill="%230f2240"/><text x="50%" y="50%" dominant-baseline="middle" text-anchor="middle" fill="%23ffffff" font-size="14">QR: ' . htmlspecialchars($text) . '</text></svg>';
        }
    }

    /**
     * Save QR Code PNG file to public/uploads/qrcodes/
     */
    public static function saveToFile(string $text, string $filename): string
    {
        $dir = FCPATH . 'uploads/qrcodes/';
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $filePath = $dir . $filename;

        try {
            $qrCode = new QrCode(
                data: $text,
                encoding: new Encoding('UTF-8'),
                size: 250,
                margin: 10,
                foregroundColor: new Color(15, 34, 64),
                backgroundColor: new Color(255, 255, 255)
            );

            $writer = new PngWriter();
            $result = $writer->write($qrCode);
            $result->saveToFile($filePath);

            return 'uploads/qrcodes/' . $filename;
        } catch (\Throwable $th) {
            log_message('error', 'QR Code file save failed: ' . $th->getMessage());
            return '';
        }
    }
}
