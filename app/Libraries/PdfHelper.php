<?php

namespace App\Libraries;

use Dompdf\Dompdf;
use Dompdf\Options;

class PdfHelper
{
    public static function streamHtml(string $html, string $filename = 'document.pdf', string $paper = 'A4', string $orientation = 'portrait')
    {
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $options->setDefaultFont('Helvetica');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper($paper, $orientation);
        $dompdf->render();

        $dompdf->stream($filename, ["Attachment" => false]);
        exit;
    }

    public static function savePdf(string $html, string $destinationPath, string $paper = 'A4', string $orientation = 'portrait'): bool
    {
        try {
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            $options->setDefaultFont('Helvetica');

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper($paper, $orientation);
            $dompdf->render();

            $output = $dompdf->output();
            file_put_contents($destinationPath, $output);
            return true;
        } catch (\Throwable $e) {
            log_message('error', 'PDF Generation error: ' . $e->getMessage());
            return false;
        }
    }
}
