<?php

namespace App\Services;

use App\Models\BookingModel;
use App\Models\RefundModel;
use App\Models\TicketModel;
use App\Models\TripModel;

class RefundService
{
    protected $bookingModel;
    protected $refundModel;
    protected $ticketModel;
    protected $tripModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->refundModel  = new RefundModel();
        $this->ticketModel  = new TicketModel();
        $this->tripModel    = new TripModel();
    }

    /**
     * Request a Refund
     */
    public function requestRefund(array $data): array
    {
        $booking = $this->bookingModel->where('booking_code', $data['booking_code'])->first();
        if (!$booking) {
            return ['success' => false, 'message' => 'Kode booking tidak ditemukan.'];
        }

        if ($booking['payment_status'] !== 'paid') {
            return ['success' => false, 'message' => 'Hanya booking yang sudah LUNAS yang dapat mengajukan refund.'];
        }

        if (in_array($booking['status'], ['cancelled', 'refunded'])) {
            return ['success' => false, 'message' => 'Booking ini sudah dibatalkan atau direfund sebelumnya.'];
        }

        $existingRefund = $this->refundModel->where('booking_id', $booking['id'])->first();
        if ($existingRefund) {
            return ['success' => false, 'message' => 'Pengajuan refund untuk booking ini sudah diproses sebelumnya (Status: ' . strtoupper($existingRefund['status']) . ')'];
        }

        $totalPaid = (float) $booking['final_amount'];
        $deductionPercentage = 10.00; // 10% fee
        $deductionAmount = ($totalPaid * $deductionPercentage) / 100;
        $refundAmount    = $totalPaid - $deductionAmount;

        $refundCode = 'RFD-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));

        $refundId = $this->refundModel->insert([
            'booking_id'           => $booking['id'],
            'refund_code'          => $refundCode,
            'reason'               => $data['reason'],
            'total_paid'           => $totalPaid,
            'deduction_percentage' => $deductionPercentage,
            'deduction_amount'     => $deductionAmount,
            'refund_amount'        => $refundAmount,
            'bank_name'            => $data['bank_name'],
            'account_number'       => $data['account_number'],
            'account_holder'       => $data['account_holder'],
            'status'               => 'requested'
        ]);

        return [
            'success'        => true,
            'refund_code'    => $refundCode,
            'refund_amount'  => $refundAmount,
            'message'        => 'Pengajuan refund berhasil dibuat dan menunggu verifikasi supervisor/manajer.'
        ];
    }

    /**
     * Approve or Reject Refund Request
     */
    public function updateRefundStatus(int $refundId, string $status, ?int $approvedByUserId = null): bool
    {
        $refund = $this->refundModel->find($refundId);
        if (!$refund) return false;

        $db = \Config\Database::connect();
        $db->transStart();

        $this->refundModel->update($refundId, [
            'status'              => $status,
            'approved_by_user_id' => $approvedByUserId,
            'processed_at'        => date('Y-m-d H:i:s')
        ]);

        if ($status === 'approved' || $status === 'completed') {
            // Update booking status
            $this->bookingModel->update($refund['booking_id'], ['status' => 'refunded']);
            // Update tickets status
            $this->ticketModel->where('booking_id', $refund['booking_id'])->set('status', 'refunded')->update();

            // Restore trip available seats
            $booking = $this->bookingModel->find($refund['booking_id']);
            if ($booking) {
                $this->tripModel->where('id', $booking['trip_id'])->set('available_seats', 'available_seats + ' . $booking['total_passengers'], false)->update();
            }
        }

        $db->transComplete();
        return $db->transStatus() !== false;
    }
}
