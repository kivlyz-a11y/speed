<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ExcelHelper
{
    /**
     * Export sales & manifest data arrays to Excel file stream
     */
    public static function downloadReport(string $title, array $headers, array $dataRows, string $filename = 'laporan.xlsx')
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Laporan');

        // Title Block
        $sheet->setCellValue('A1', strtoupper($title));
        $sheet->mergeCells('A1:' . self::getColLetter(count($headers)));
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('0F2240'));
        $sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        $sheet->setCellValue('A2', 'Generated Date: ' . date('d M Y H:i:s'));
        $sheet->mergeCells('A2:' . self::getColLetter(count($headers)));
        $sheet->getStyle('A2')->getFont()->setItalic(true)->setSize(10);
        $sheet->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Header Row
        $colIndex = 1;
        $rowNum = 4;
        foreach ($headers as $header) {
            $colLetter = self::getColLetter($colIndex);
            $sheet->setCellValue($colLetter . $rowNum, $header);
            $colIndex++;
        }

        $headerRange = 'A4:' . self::getColLetter(count($headers)) . '4';
        $sheet->getStyle($headerRange)->getFont()->setBold(true)->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('FFFFFF'));
        $sheet->getStyle($headerRange)->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('0F2240');
        $sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Data Rows
        $rowNum = 5;
        foreach ($dataRows as $row) {
            $colIndex = 1;
            foreach ($row as $val) {
                $colLetter = self::getColLetter($colIndex);
                $sheet->setCellValue($colLetter . $rowNum, $val);
                $colIndex++;
            }
            $rowNum++;
        }

        // Auto column width
        for ($i = 1; $i <= count($headers); $i++) {
            $sheet->getColumnDimension(self::getColLetter($i))->setAutoSize(true);
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }

    private static function getColLetter(int $index): string
    {
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($index);
    }
}
