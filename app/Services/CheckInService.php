<?php

namespace App\Services;

use App\Models\TicketModel;
use App\Models\CheckInLogModel;
use App\Models\BoardingManifestModel;
use App\Models\TripModel;

class CheckInService
{
    protected $ticketModel;
    protected $checkInLogModel;
    protected $boardingManifestModel;
    protected $tripModel;

    public function __construct()
    {
        $this->ticketModel           = new TicketModel();
        $this->checkInLogModel      = new CheckInLogModel();
        $this->boardingManifestModel = new BoardingManifestModel();
        $this->tripModel            = new TripModel();
    }

    /**
     * Process Scan QR Ticket Check-In
     */
    public function processScan(string $ticketCode, ?int $scannedByUserId = null): array
    {
        $db = \Config\Database::connect();
        $ticket = $db->table('tickets t')
            ->select('t.*, bp.passenger_name, bp.passenger_phone, b.booking_code,
                      tr.trip_code, tr.trip_date, tr.departure_time, sb.name as boat_name,
                      loc1.name as origin_name, loc2.name as destination_name')
            ->join('booking_passengers bp', 'bp.id = t.passenger_id')
            ->join('bookings b', 'b.id = t.booking_id')
            ->join('trips tr', 'tr.id = t.trip_id')
            ->join('speed_boats sb', 'sb.id = tr.speed_boat_id')
            ->join('schedules sch', 'sch.id = tr.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->where('t.ticket_code', $ticketCode)
            ->get()->getRowArray();

        if (!$ticket) {
            return ['success' => false, 'message' => 'Tiket tidak ditemukan dalam sistem!'];
        }

        if ($ticket['status'] === 'checked_in') {
            return [
                'success'        => false,
                'already_scanned'=> true,
                'ticket'         => $ticket,
                'message'        => 'TIKET SUDAH DI-CHECKIN SEBELUMNYA pada ' . date('d M Y H:i', strtotime($ticket['checked_in_at']))
            ];
        }

        if ($ticket['status'] !== 'active') {
            return ['success' => false, 'message' => 'Tiket tidak aktif (Status: ' . strtoupper($ticket['status']) . ')'];
        }

        $now = date('Y-m-d H:i:s');
        // Mark Ticket Checked In
        $this->ticketModel->update($ticket['id'], [
            'status'        => 'checked_in',
            'checked_in_at' => $now
        ]);

        // Insert Check In Log
        $this->checkInLogModel->insert([
            'ticket_id'          => $ticket['id'],
            'trip_id'            => $ticket['trip_id'],
            'scanned_by_user_id' => $scannedByUserId,
            'scan_time'          => $now,
            'status'             => 'success'
        ]);

        $ticket['checked_in_at'] = $now;
        return [
            'success' => true,
            'ticket'  => $ticket,
            'message' => 'CHECK-IN BERHASIL! Penumpang: ' . $ticket['passenger_name'] . ' (Seat ' . $ticket['seat_number'] . ')'
        ];
    }

    /**
     * Get Boarding Manifest Summary for a Trip
     */
    public function getTripManifest(int $tripId)
    {
        $db = \Config\Database::connect();
        $trip = $db->table('trips t')
            ->select('t.*, sb.name as boat_name, sb.code as boat_code, sb.capacity,
                      c.name as captain_name,
                      loc1.name as origin_name, loc1.city as origin_city, loc2.name as destination_name, loc2.city as destination_city')
            ->join('speed_boats sb', 'sb.id = t.speed_boat_id')
            ->join('schedules sch', 'sch.id = t.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->join('captains c', 'c.id = t.captain_id', 'left')
            ->where('t.id', $tripId)
            ->get()->getRowArray();

        if (!$trip) return null;

        $passengers = $db->table('tickets t')
            ->select('t.ticket_code, t.seat_number, t.status as ticket_status, t.checked_in_at,
                      bp.passenger_name, bp.passenger_phone, bp.passenger_type,
                      b.booking_code, b.customer_phone')
            ->join('booking_passengers bp', 'bp.id = t.passenger_id')
            ->join('bookings b', 'b.id = t.booking_id')
            ->where('t.trip_id', $tripId)
            ->orderBy('t.seat_number', 'ASC')
            ->get()->getResultArray();

        $totalCheckedIn = 0;
        $totalAbsent    = 0;

        foreach ($passengers as $p) {
            if ($p['ticket_status'] === 'checked_in') {
                $totalCheckedIn++;
            } else {
                $totalAbsent++;
            }
        }

        $trip['passengers']       = $passengers;
        $trip['total_checked_in'] = $totalCheckedIn;
        $trip['total_absent']     = $totalAbsent;
        $trip['total_booked']     = count($passengers);

        return $trip;
    }
}
