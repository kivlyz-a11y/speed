<?php

namespace App\Services;

use App\Models\TripModel;
use App\Models\SeatModel;
use App\Models\SeatLockModel;
use App\Models\BookingModel;
use App\Models\BookingPassengerModel;
use App\Models\VoucherModel;
use App\Models\RouteModel;
use App\Models\LocationModel;
use App\Models\SpeedBoatModel;

class BookingService
{
    protected $tripModel;
    protected $seatModel;
    protected $seatLockModel;
    protected $bookingModel;
    protected $passengerModel;
    protected $voucherModel;

    public function __construct()
    {
        $this->tripModel      = new TripModel();
        $this->seatModel      = new SeatModel();
        $this->seatLockModel  = new SeatLockModel();
        $this->bookingModel   = new BookingModel();
        $this->passengerModel = new BookingPassengerModel();
        $this->voucherModel   = new VoucherModel();
    }

    /**
     * Search Available Trips with Route & Speedboat Details
     */
    public function searchTrips(int $originId, int $destinationId, string $date, int $passengers = 1)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('trips t')
            ->select('t.*, r.origin_location_id, r.destination_location_id, r.distance_nautical_miles, r.estimated_duration_minutes, 
                      sb.name as boat_name, sb.code as boat_code, sb.capacity, sb.seat_layout_json,
                      loc1.name as origin_name, loc1.city as origin_city, loc2.name as destination_name, loc2.city as destination_city,
                      c.name as captain_name')
            ->join('schedules sch', 'sch.id = t.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('speed_boats sb', 'sb.id = t.speed_boat_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->join('captains c', 'c.id = t.captain_id', 'left')
            ->where('r.origin_location_id', $originId)
            ->where('r.destination_location_id', $destinationId)
            ->where('t.trip_date', $date)
            ->where('t.available_seats >=', $passengers)
            ->where('t.status', 'scheduled')
            ->orderBy('t.departure_time', 'ASC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get Trip Details with Interactive Seat Occupancy Grid
     */
    public function getTripSeatMap(int $tripId, string $sessionId)
    {
        $trip = $this->tripModel->find($tripId);
        if (!$trip) return null;

        $db = \Config\Database::connect();
        $tripDetails = $db->table('trips t')
            ->select('t.*, sb.name as boat_name, sb.total_rows, sb.total_cols, sb.seat_layout_json,
                      loc1.name as origin_name, loc1.city as origin_city, loc2.name as destination_name, loc2.city as destination_city')
            ->join('speed_boats sb', 'sb.id = t.speed_boat_id')
            ->join('schedules sch', 'sch.id = t.schedule_id')
            ->join('routes r', 'r.id = sch.route_id')
            ->join('locations loc1', 'loc1.id = r.origin_location_id')
            ->join('locations loc2', 'loc2.id = r.destination_location_id')
            ->where('t.id', $tripId)
            ->get()->getRowArray();

        // Get All Seats for Boat
        $seats = $this->seatModel->where('speed_boat_id', $trip['speed_boat_id'])->where('is_active', 1)->findAll();

        // Get Booked Seats for this trip (status confirmed or pending)
        $bookedSeats = $db->table('booking_passengers bp')
            ->select('bp.seat_id, bp.seat_number')
            ->join('bookings b', 'b.id = bp.booking_id')
            ->where('b.trip_id', $tripId)
            ->whereIn('b.status', ['pending', 'confirmed', 'completed'])
            ->whereIn('b.payment_status', ['paid', 'unpaid'])
            ->get()->getResultArray();

        $bookedSeatIds = array_column($bookedSeats, 'seat_id');

        // Clear Expired Seat Locks
        $this->seatLockModel->where('locked_until <', date('Y-m-d H:i:s'))->delete();

        // Get Active Seat Locks
        $lockedSeats = $this->seatLockModel->where('trip_id', $tripId)->findAll();
        $lockedSeatMap = [];
        foreach ($lockedSeats as $lk) {
            $lockedSeatMap[$lk['seat_id']] = ($lk['session_id'] === $sessionId) ? 'my_locked' : 'other_locked';
        }

        // Map status for each seat
        foreach ($seats as &$s) {
            if (in_array($s['id'], $bookedSeatIds)) {
                $s['status'] = 'booked';
            } elseif (isset($lockedSeatMap[$s['id']])) {
                $s['status'] = $lockedSeatMap[$s['id']];
            } else {
                $s['status'] = 'available';
            }
        }

        $tripDetails['seats'] = $seats;
        return $tripDetails;
    }

    /**
     * Lock selected seats for 10 minutes session
     */
    public function lockSeats(int $tripId, array $seatIds, string $sessionId, ?int $userId = null): bool
    {
        $this->seatLockModel->where('session_id', $sessionId)->delete();
        $lockedUntil = date('Y-m-d H:i:s', strtotime('+10 minutes'));

        foreach ($seatIds as $sId) {
            $this->seatLockModel->insert([
                'trip_id'      => $tripId,
                'seat_id'      => $sId,
                'session_id'   => $sessionId,
                'user_id'      => $userId,
                'locked_until' => $lockedUntil
            ]);
        }
        return true;
    }

    /**
     * Validate & Calculate Voucher Discount
     */
    public function applyVoucher(string $code, float $totalAmount): array
    {
        $voucher = $this->voucherModel->where('code', strtoupper($code))->where('is_active', 1)->first();
        if (!$voucher) {
            return ['valid' => false, 'message' => 'Kode promo tidak ditemukan.'];
        }

        $today = date('Y-m-d');
        if ($today < $voucher['start_date'] || $today > $voucher['end_date']) {
            return ['valid' => false, 'message' => 'Masa berlaku promo telah habis.'];
        }

        if ($voucher['used_quota'] >= $voucher['quota']) {
            return ['valid' => false, 'message' => 'Kuota voucher promo telah habis.'];
        }

        if ($totalAmount < $voucher['min_transaction']) {
            return ['valid' => false, 'message' => 'Minimal transaksi Rp ' . number_format($voucher['min_transaction'], 0, ',', '.')];
        }

        $discount = 0;
        if ($voucher['discount_type'] === 'fixed') {
            $discount = (float) $voucher['discount_value'];
        } else {
            $discount = ($totalAmount * (float) $voucher['discount_value']) / 100;
            if ($voucher['max_discount'] > 0 && $discount > $voucher['max_discount']) {
                $discount = (float) $voucher['max_discount'];
            }
        }

        return [
            'valid'           => true,
            'voucher_code'    => $voucher['code'],
            'discount_amount' => $discount,
            'message'         => 'Voucher berhasil digunakan!'
        ];
    }

    /**
     * Process Final Booking Order
     */
    public function createBooking(array $bookingData, array $passengersData): array
    {
        $db = \Config\Database::connect();
        $db->transStart();

        $trip = $this->tripModel->find($bookingData['trip_id']);
        if (!$trip || $trip['available_seats'] < count($passengersData)) {
            $db->transRollback();
            return ['success' => false, 'message' => 'Kursi tidak cukup atau jadwal tidak tersedia.'];
        }

        $bookingCode = 'CITI-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));
        $uuid        = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        $expiredAt   = date('Y-m-d H:i:s', strtotime('+30 minutes'));

        $totalPassengers = count($passengersData);
        $totalAmount     = 0;
        foreach ($passengersData as $p) {
            $totalAmount += $p['price'];
        }

        $discountAmount = 0;
        $voucherCode    = null;
        if (!empty($bookingData['voucher_code'])) {
            $vRes = $this->applyVoucher($bookingData['voucher_code'], $totalAmount);
            if ($vRes['valid']) {
                $discountAmount = $vRes['discount_amount'];
                $voucherCode    = $vRes['voucher_code'];
                $this->voucherModel->where('code', $voucherCode)->set('used_quota', 'used_quota+1', false)->update();
            }
        }

        $finalAmount = max(0, $totalAmount - $discountAmount);

        $bookingId = $this->bookingModel->insert([
            'uuid'             => $uuid,
            'booking_code'     => $bookingCode,
            'user_id'          => $bookingData['user_id'] ?? null,
            'trip_id'          => $bookingData['trip_id'],
            'booking_type'     => $bookingData['booking_type'] ?? 'online',
            'customer_name'    => $bookingData['customer_name'],
            'customer_email'   => $bookingData['customer_email'],
            'customer_phone'   => $bookingData['customer_phone'],
            'total_passengers' => $totalPassengers,
            'total_amount'     => $totalAmount,
            'discount_amount'  => $discountAmount,
            'final_amount'     => $finalAmount,
            'voucher_code'     => $voucherCode,
            'status'           => 'pending',
            'payment_status'   => 'unpaid',
            'expired_at'       => $expiredAt
        ]);

        foreach ($passengersData as $p) {
            $this->passengerModel->insert([
                'booking_id'      => $bookingId,
                'seat_id'         => $p['seat_id'],
                'passenger_name'  => $p['passenger_name'],
                'passenger_phone' => $p['passenger_phone'] ?? $bookingData['customer_phone'],
                'passenger_type'  => $p['passenger_type'] ?? 'adult',
                'seat_number'     => $p['seat_number'],
                'price'           => $p['price']
            ]);
        }

        // Deduct available seats on trip
        $this->tripModel->where('id', $bookingData['trip_id'])->set('available_seats', 'available_seats - ' . $totalPassengers, false)->update();

        // Clear locks for this session
        if (!empty($bookingData['session_id'])) {
            $this->seatLockModel->where('session_id', $bookingData['session_id'])->delete();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['success' => false, 'message' => 'Gagal membuat reservasi booking. Silakan coba lagi.'];
        }

        return [
            'success'      => true,
            'booking_id'   => $bookingId,
            'booking_code' => $bookingCode,
            'final_amount' => $finalAmount,
            'message'      => 'Booking berhasil dibuat!'
        ];
    }
}
