<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ReportService;
use App\Libraries\ExcelHelper;
use App\Libraries\PdfHelper;

class ReportAdminController extends BaseController
{
    protected $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
    }

    public function sales()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $sales = $this->reportService->getSalesReport($startDate, $endDate);

        return view('admin/reports/sales', [
            'title'      => 'Laporan Penjualan Tiket & Pendapatan',
            'sales'      => $sales,
            'start_date' => $startDate,
            'end_date'   => $endDate
        ]);
    }

    public function exportExcel()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $sales = $this->reportService->getSalesReport($startDate, $endDate);

        $headers = ['Kode Booking', 'Tgl Transaksi', 'Nama Pemesan', 'No. HP', 'Jumlah Pnp', 'Total Bayar (Rp)', 'Status Bayar', 'Rute', 'Kapal'];
        $rows = [];

        foreach ($sales as $s) {
            $rows[] = [
                $s['booking_code'],
                date('d/m/Y H:i', strtotime($s['created_at'])),
                $s['customer_name'],
                $s['customer_phone'],
                $s['total_passengers'],
                number_format($s['final_amount'], 0, ',', '.'),
                strtoupper($s['payment_status']),
                $s['origin_name'] . ' -> ' . $s['destination_name'],
                $s['boat_name']
            ];
        }

        ExcelHelper::downloadReport('Laporan Penjualan Tiket Speed Boat (' . $startDate . ' s/d ' . $endDate . ')', $headers, $rows, 'Laporan_Penjualan_' . $startDate . '_sd_' . $endDate . '.xlsx');
    }

    public function exportPdf()
    {
        $startDate = $this->request->getGet('start_date') ?? date('Y-m-01');
        $endDate   = $this->request->getGet('end_date') ?? date('Y-m-t');

        $sales = $this->reportService->getSalesReport($startDate, $endDate);

        $html = view('admin/reports/sales_pdf', [
            'sales'      => $sales,
            'start_date' => $startDate,
            'end_date'   => $endDate
        ]);

        PdfHelper::streamHtml($html, 'Laporan_Penjualan_' . $startDate . '_sd_' . $endDate . '.pdf', 'A4', 'landscape');
    }
}
