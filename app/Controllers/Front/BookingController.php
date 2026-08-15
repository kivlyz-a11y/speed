<?php

namespace App\Controllers\Front;

use App\Controllers\BaseController;
use App\Services\BookingService;
use App\Models\TripModel;

class BookingController extends BaseController
{
    protected $bookingService;

    public function __construct()
    {
        $this->bookingService = new BookingService();
    }

    /**
     * Get Valid Route Destinations for selected Origin Location (Database Linked)
     */
    public function getDestinationsByOrigin(int $originId)
    {
        $db = \Config\Database::connect();
        $destinations = $db->table('routes r')
            ->select('loc.id, loc.name, loc.city, loc.code')
            ->join('locations loc', 'loc.id = r.destination_location_id')
            ->where('r.origin_location_id', $originId)
            ->where('r.status', 'active')
            ->where('loc.status', 'active')
            ->groupBy('loc.id')
            ->get()->getResultArray();

        foreach ($destinations as &$d) {
            $d['label'] = $d['name'] . ' (' . $d['city'] . ')';
        }

        return $this->response->setJSON([
            'success'      => true,
            'destinations' => $destinations
        ]);
    }

    /**
     * Search Schedules Page / AJAX
     */
    public function search()
    {
        $originId          = (int) $this->request->getGet('origin_id');
        $destinationId     = (int) $this->request->getGet('destination_id');
        $date              = $this->request->getGet('date') ?? date('Y-m-d');
        $malePassengers    = (int) ($this->request->getGet('male_passengers') ?? 1);
        $femalePassengers  = (int) ($this->request->getGet('female_passengers') ?? 0);
        $passengers        = (int) ($this->request->getGet('passengers') ?? ($malePassengers + $femalePassengers));
        if ($passengers < 1) {
            $passengers = max(1, $malePassengers + $femalePassengers);
        }

        $trips = [];
        if ($originId && $destinationId) {
            $trips = $this->bookingService->searchTrips($originId, $destinationId, $date, $passengers);
        }

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'success' => true,
                'trips'   => $trips,
                'count'   => count($trips)
            ]);
        }

        $locationModel = new \App\Models\LocationModel();
        return view('front/search_results', [
            'title'              => 'Hasil Pencarian Jadwal Speed Boat - BMK (Benuanta Mutiara Khatulistiwa)',
            'trips'              => $trips,
            'locations'          => $locationModel->findAll(),
            'search_origin'      => $originId,
            'search_dest'        => $destinationId,
            'search_date'        => $date,
            'search_pass'        => $passengers,
            'search_male_pass'   => $malePassengers,
            'search_female_pass' => $femalePassengers
        ]);
    }

    /**
     * Get Interactive Seat Map for Trip
     */
    public function seatMap(int $tripId)
    {
        $sessionId = session_id() ?: 'SESS-' . uniqid();
        $tripData  = $this->bookingService->getTripSeatMap($tripId, $sessionId);

        if (!$tripData) {
            return $this->response->setJSON(['success' => false, 'message' => 'Jadwal tidak ditemukan.']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $tripData
        ]);
    }

    /**
     * Lock Seat temporarily
     */
    public function lockSeat()
    {
        $json       = $this->request->getJSON(true);
        $tripId     = (int) ($json['trip_id'] ?? 0);
        $seatIds    = (array) ($json['seat_ids'] ?? []);
        $sessionId  = session_id() ?: 'SESS-' . uniqid();
        $userId     = session()->get('user_id');

        if (!$tripId || empty($seatIds)) {
            return $this->response->setJSON(['success' => false, 'message' => 'Pilihlah minimal 1 kursi.']);
        }

        $res = $this->bookingService->lockSeats($tripId, $seatIds, $sessionId, $userId);
        return $this->response->setJSON(['success' => $res, 'message' => 'Kursi berhasil dikunci sementara.']);
    }

    /**
     * Apply Voucher Promo Code
     */
    public function applyVoucher()
    {
        $json        = $this->request->getJSON(true);
        $code        = trim($json['code'] ?? '');
        $totalAmount = (float) ($json['total_amount'] ?? 0);

        $res = $this->bookingService->applyVoucher($code, $totalAmount);
        return $this->response->setJSON($res);
    }

    /**
     * Submit Booking Creation
     */
    public function store()
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            $json = $this->request->getPost();
        }

        $sessionId = session_id();
        $userId    = session()->get('user_id');

        $bookingData = [
            'user_id'        => $userId,
            'trip_id'        => (int) $json['trip_id'],
            'customer_name'  => trim($json['customer_name']),
            'customer_email' => trim($json['customer_email']),
            'customer_phone' => trim($json['customer_phone']),
            'voucher_code'   => trim($json['voucher_code'] ?? ''),
            'session_id'     => $sessionId,
            'booking_type'   => 'online'
        ];

        $passengersData = [];
        foreach ($json['passengers'] as $p) {
            $passengersData[] = [
                'seat_id'         => (int) $p['seat_id'],
                'seat_number'     => $p['seat_number'],
                'passenger_name'  => trim($p['name']),
                'passenger_phone' => trim($p['phone'] ?? $json['customer_phone']),
                'price'           => (float) $p['price']
            ];
        }

        $result = $this->bookingService->createBooking($bookingData, $passengersData);

        if ($result['success']) {
            return $this->response->setJSON([
                'success'      => true,
                'booking_code' => $result['booking_code'],
                'redirect_url' => base_url('payment/checkout/' . $result['booking_code']),
                'message'      => $result['message']
            ]);
        }

        return $this->response->setJSON(['success' => false, 'message' => $result['message']]);
    }
}
