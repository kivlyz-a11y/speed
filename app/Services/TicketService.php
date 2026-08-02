<?php

namespace App\Services;

use App\Models\BookingModel;
use App\Models\BookingPassengerModel;
use App\Models\TicketModel;
use App\Libraries\QrCodeHelper;
use App\Libraries\PdfHelper;

class TicketService
{
    protected $bookingModel;
    protected $passengerModel;
    protected $ticketModel;

    public function __construct()
    {
        $this->bookingModel   = new BookingModel();
        $this->passengerModel = new BookingPassengerModel();
        $this->ticketModel    = new TicketModel();
    }

    /**
     * Generate E-Tickets for all passengers in confirmed booking
     */
    public function generateTicketsForBooking(int $bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking) return;

        $passengers = $this->passengerModel->where('booking_id', $bookingId)->findAll();

        foreach ($passengers as $p) {
            $existing = $this->ticketModel->where('passenger_id', $p['id'])->first();
            if (!$existing) {
                $ticketCode = 'TIX-' . strtoupper(substr(md5($booking['booking_code'] . $p['id']), 0, 8));
                $uuid       = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));

                $qrFilename = 'ticket_' . $ticketCode . '.png';
                $qrPath     = QrCodeHelper::saveToFile($ticketCode, $qrFilename);

                $this->ticketModel->insert([
                    'uuid'          => $uuid,
                    'ticket_code'   => $ticketCode,
                    'booking_id'    => $bookingId,
                    'passenger_id'  => $p['id'],
                    'trip_id'       => $booking['trip_id'],
                    'seat_number'   => $p['seat_number'],
                    'qr_code_path'  => $qrPath,
                    'status'        => 'active'
                ]);
            }
        }
    }

    /**
     * Get Full E-Ticket Details for Booking
     */
    public function getBookingTickets(string $bookingCode)
    {
        $db = \Config\Database::connect();
        $booking = $db->table('bookings b')
            ->select('b.*, t.trip_code, t.trip_date, t.departure_time, t.arrival_time, 
                      sb.name as boat_name, sb.code as boat_code,
                      loc1.name as origin_name, loc1.city as origin_city, loc2.name as destination_name, loc2.city as destination_city')
            ->join('trips t', 't.id = b.trip_id')
            ->join('speed_boats sb', 'sb.id = t.speed_boat_id')
            ->join('schedules sch', 'sch.id = t.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->where('b.booking_code', $bookingCode)
            ->get()->getRowArray();

        if (!$booking) return null;

        $tickets = $db->table('tickets t')
            ->select('t.*, bp.passenger_name, bp.passenger_phone, bp.passenger_type, bp.price')
            ->join('booking_passengers bp', 'bp.id = t.passenger_id')
            ->where('t.booking_id', $booking['id'])
            ->get()->getResultArray();

        foreach ($tickets as &$t) {
            $t['qr_data_uri'] = QrCodeHelper::generateDataUri($t['ticket_code']);
        }

        $booking['tickets'] = $tickets;
        return $booking;
    }
}
