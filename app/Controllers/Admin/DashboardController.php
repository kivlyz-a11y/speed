<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\ReportService;

class DashboardController extends BaseController
{
    protected $reportService;

    public function __construct()
    {
        $this->reportService = new ReportService();
    }

    public function index()
    {
        $metrics = $this->reportService->getDashboardMetrics();

        $data = [
            'title'   => 'Dashboard Operasional Speed Boat',
            'metrics' => $metrics
        ];

        return view('admin/dashboard', $data);
    }
}
