<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Services\CheckInService;
use App\Models\TripModel;

class CheckInController extends BaseController
{
    protected $checkInService;
    protected $tripModel;

    public function __construct()
    {
        $this->checkInService = new CheckInService();
        $this->tripModel      = new TripModel();
    }

    /**
     * Render Scanner Check-in Interface
     */
    public function scanner()
    {
        $today = date('Y-m-d');
        $db = \Config\Database::connect();
        $todayTrips = $db->table('trips t')
            ->select('t.*, sb.name as boat_name, loc1.city as origin_city, loc2.city as destination_city')
            ->join('speed_boats sb', 'sb.id = t.speed_boat_id')
            ->join('schedules sch', 'sch.id = t.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->where('t.trip_date', $today)
            ->get()->getResultArray();

        return view('admin/checkin_scanner', [
            'title' => 'Scan QR Code Check-In & Boarding Dermaga',
            'trips' => $todayTrips
        ]);
    }

    /**
     * Process Scan Ticket via AJAX
     */
    public function scanTicket()
    {
        $json       = $this->request->getJSON(true);
        $ticketCode = trim($json['ticket_code'] ?? '');
        $userId     = session()->get('user_id');

        if (empty($ticketCode)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Kode tiket tidak boleh kosong.']);
        }

        $res = $this->checkInService->processScan($ticketCode, $userId);
        return $this->response->setJSON($res);
    }

    /**
     * View Passenger Boarding Manifest for a Trip
     */
    public function manifest(int $tripId)
    {
        $manifest = $this->checkInService->getTripManifest($tripId);
        if (!$manifest) {
            return redirect()->to('admin/checkin/scanner')->with('error', 'Trip tidak ditemukan.');
        }

        return view('admin/boarding_manifest', [
            'title'    => 'Manifest Penumpang Keberangkatan - ' . $manifest['trip_code'],
            'manifest' => $manifest
        ]);
    }
}
