<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Services\TicketService;
use App\Services\RefundService;
use App\Services\RescheduleService;
use App\Libraries\PdfHelper;

class TicketController extends BaseController
{
    protected $ticketService;
    protected $refundService;
    protected $rescheduleService;

    public function __construct()
    {
        $this->ticketService     = new TicketService();
        $this->refundService     = new RefundService();
        $this->rescheduleService = new RescheduleService();
    }

    /**
     * Download PDF E-Ticket
     */
    public function downloadPdf(string $bookingCode)
    {
        $ticketData = $this->ticketService->getBookingTickets($bookingCode);
        if (!$ticketData) {
            return redirect()->to('/')->with('error', 'Tiket tidak ditemukan.');
        }

        $html = view('front/pdf_eticket', ['booking' => $ticketData]);
        PdfHelper::streamHtml($html, 'E-Ticket-' . $bookingCode . '.pdf');
    }

    /**
     * Process Public Refund Request
     */
    public function submitRefund()
    {
        $data = [
            'booking_code'   => trim($this->request->getPost('booking_code')),
            'reason'         => trim($this->request->getPost('reason')),
            'bank_name'      => trim($this->request->getPost('bank_name')),
            'account_number' => trim($this->request->getPost('account_number')),
            'account_holder' => trim($this->request->getPost('account_holder')),
        ];

        $res = $this->refundService->requestRefund($data);
        if ($res['success']) {
            return redirect()->back()->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }

    /**
     * Process Public Reschedule Request
     */
    public function submitReschedule()
    {
        $bookingCode = trim($this->request->getPost('booking_code'));
        $newTripId   = (int) $this->request->getPost('new_trip_id');
        $seatIds     = (array) $this->request->getPost('seat_ids');

        $res = $this->rescheduleService->processReschedule($bookingCode, $newTripId, $seatIds, session()->get('user_id'));
        if ($res['success']) {
            return redirect()->to('booking/success/' . $bookingCode)->with('success', $res['message']);
        }
        return redirect()->back()->with('error', $res['message']);
    }
}
