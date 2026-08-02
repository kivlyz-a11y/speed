<?php

namespace App\Services;

use App\Models\BookingModel;
use App\Models\PaymentModel;
use App\Models\BookingPassengerModel;
use App\Libraries\MidtransLibrary;
use App\Libraries\WhatsAppHelper;
use App\Services\TicketService;

class PaymentService
{
    protected $bookingModel;
    protected $paymentModel;
    protected $passengerModel;
    protected $midtrans;
    protected $ticketService;

    public function __construct()
    {
        $this->bookingModel   = new BookingModel();
        $this->paymentModel   = new PaymentModel();
        $this->passengerModel = new BookingPassengerModel();
        $this->midtrans       = new MidtransLibrary();
        $this->ticketService  = new TicketService();
    }

    /**
     * Create or Get Payment Transaction for Booking
     */
    public function initiatePayment(int $bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) return null;

        $existingPayment = $this->paymentModel->where('booking_id', $bookingId)->first();
        if ($existingPayment) {
            return $existingPayment;
        }

        $passengers = $this->passengerModel->where('booking_id', $bookingId)->findAll();
        $snapResult = $this->midtrans->createSnapTransaction($booking, $passengers);

        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        $paymentCode = 'PAY-' . $booking['booking_code'];

        $paymentId = $this->paymentModel->insert([
            'uuid'               => $uuid,
            'booking_id'         => $bookingId,
            'payment_code'       => $paymentCode,
            'gateway_type'       => 'midtrans',
            'payment_method'     => 'snap_qris_va',
            'gross_amount'       => $booking['final_amount'],
            'transaction_status' => 'pending',
            'snap_token'         => $snapResult['token'],
            'redirect_url'       => $snapResult['redirect_url']
        ]);

        return $this->paymentModel->find($paymentId);
    }

    /**
     * Confirm Payment Success (Midtrans Webhook / Mock Pay)
     */
    public function markAsPaid(string $bookingCode, string $paymentMethod = 'midtrans_qris'): bool
    {
        $booking = $this->bookingModel->where('booking_code', $bookingCode)->first();
        if (!$booking) return false;

        $db = \Config\Database::connect();
        $db->transStart();

        // Update Booking status
        $this->bookingModel->update($booking['id'], [
            'status'         => 'confirmed',
            'payment_status' => 'paid'
        ]);

        // Update Payment status
        $this->paymentModel->where('booking_id', $booking['id'])->set([
            'transaction_status' => 'settlement',
            'payment_method'     => $paymentMethod,
            'transaction_time'   => date('Y-m-d H:i:s')
        ])->update();

        // Auto Generate E-Tickets with QR Code for each passenger
        $this->ticketService->generateTicketsForBooking($booking['id']);

        $db->transComplete();

        if ($db->transStatus() !== false) {
            // Dispatch simulated WhatsApp Ticket Notification
            $waMsg = "Halo {$booking['customer_name']},\nPembayaran tiket Speed Boat Anda berhasil! Kode Booking: *{$booking['booking_code']}*.\nSilakan unduh E-Ticket Anda di: " . base_url("booking/ticket/{$booking['booking_code']}");
            WhatsAppHelper::sendNotification($booking['customer_phone'], $waMsg);
            return true;
        }

        return false;
    }
}
