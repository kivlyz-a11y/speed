<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Services\PaymentService;
use App\Services\TicketService;
use App\Models\BookingModel;

class PaymentController extends BaseController
{
    protected $paymentService;
    protected $ticketService;
    protected $bookingModel;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
        $this->ticketService  = new TicketService();
        $this->bookingModel   = new BookingModel();
    }

    /**
     * Render Checkout Payment Page
     */
    public function checkout(string $bookingCode)
    {
        $booking = $this->bookingModel->where('booking_code', $bookingCode)->first();
        if (!$booking) {
            return redirect()->to('/')->with('error', 'Booking tidak ditemukan.');
        }

        $payment = $this->paymentService->initiatePayment($booking['id']);
        $ticketData = $this->ticketService->getBookingTickets($bookingCode);

        return view('front/checkout_payment', [
            'title'     => 'Pembayaran Tiket - ' . $bookingCode,
            'booking'   => $booking,
            'payment'   => $payment,
            'passengers'=> $ticketData['tickets'] ?? []
        ]);
    }

    /**
     * Mock Pay Instant Confirmation (For Testing / Offline Payment)
     */
    public function mockPay(string $bookingCode)
    {
        $res = $this->paymentService->markAsPaid($bookingCode, 'Midtrans Mock QRIS/VA');
        if ($res) {
            return redirect()->to('booking/success/' . $bookingCode)->with('success', 'Pembayaran berhasil dikonfirmasi!');
        }
        return redirect()->back()->with('error', 'Gagal mengonfirmasi pembayaran.');
    }

    /**
     * Midtrans Notification Callback Webhook
     */
    public function notificationCallback()
    {
        $json = $this->request->getJSON(true);
        if (!$json) {
            $json = json_decode(file_get_contents('php://input'), true);
        }

        if (empty($json)) {
            return $this->response->setStatusCode(400)->setJSON(['status' => 'error', 'message' => 'Empty payload']);
        }

        $orderId           = $json['order_id'] ?? null;
        $transactionStatus = $json['transaction_status'] ?? null;
        $paymentType       = $json['payment_type'] ?? 'midtrans';

        if ($orderId && ($transactionStatus === 'settlement' || $transactionStatus === 'capture')) {
            $this->paymentService->markAsPaid($orderId, $paymentType);
        }

        return $this->response->setJSON(['status' => 'ok']);
    }

    /**
     * Payment Success Page
     */
    public function success(string $bookingCode)
    {
        $ticketData = $this->ticketService->getBookingTickets($bookingCode);
        if (!$ticketData) {
            return redirect()->to('/')->with('error', 'Tiket tidak ditemukan.');
        }

        return view('front/booking_success', [
            'title'   => 'E-Ticket Pemesanan Tiket Speed Boat',
            'booking' => $ticketData
        ]);
    }
}
