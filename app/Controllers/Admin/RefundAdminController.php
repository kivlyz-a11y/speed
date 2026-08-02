<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\RefundModel;
use App\Services\RefundService;

class RefundAdminController extends BaseController
{
    protected $refundModel;
    protected $refundService;

    public function __construct()
    {
        $this->refundModel   = new RefundModel();
        $this->refundService = new RefundService();
    }

    public function index()
    {
        $db = \Config\Database::connect();
        $refunds = $db->table('refunds r')
            ->select('r.*, b.booking_code, b.customer_name, b.customer_phone, b.customer_email')
            ->join('bookings b', 'b.id = r.booking_id')
            ->orderBy('r.created_at', 'DESC')
            ->get()->getResultArray();

        return view('admin/refunds', [
            'title'   => 'Manajemen Pengajuan Refund',
            'refunds' => $refunds
        ]);
    }

    public function updateStatus(int $refundId)
    {
        $status = $this->request->getPost('status');
        $userId = session()->get('user_id');

        $res = $this->refundService->updateRefundStatus($refundId, $status, $userId);
        if ($res) {
            return redirect()->back()->with('success', 'Status refund berhasil diperbarui.');
        }
        return redirect()->back()->with('error', 'Gagal memperbarui status refund.');
    }
}
