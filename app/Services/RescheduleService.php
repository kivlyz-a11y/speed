<?php

namespace App\Services;

use App\Models\BookingModel;
use App\Models\RescheduleModel;
use App\Models\TripModel;
use App\Models\TicketModel;
use App\Models\BookingPassengerModel;
use App\Models\SeatModel;

class RescheduleService
{
    protected $bookingModel;
    protected $rescheduleModel;
    protected $tripModel;
    protected $ticketModel;
    protected $passengerModel;
    protected $seatModel;

    public function __construct()
    {
        $this->bookingModel    = new BookingModel();
        $this->rescheduleModel = new RescheduleModel();
        $this->tripModel       = new TripModel();
        $this->ticketModel     = new TicketModel();
        $this->passengerModel  = new BookingPassengerModel();
        $this->seatModel       = new SeatModel();
    }

    /**
     * Reschedule Booking to a New Trip with new seats
     */
    public function processReschedule(string $bookingCode, int $newTripId, array $newSeatIds, ?int $processedByUserId = null): array
    {
        $booking = $this->bookingModel->where('booking_code', $bookingCode)->first();
        if (!$booking) {
            return ['success' => false, 'message' => 'Kode booking tidak ditemukan.'];
        }

        if ($booking['payment_status'] !== 'paid') {
            return ['success' => false, 'message' => 'Hanya tiket LUNAS yang dapat di-reschedule.'];
        }

        $newTrip = $this->tripModel->find($newTripId);
        if (!$newTrip || $newTrip['available_seats'] < count($newSeatIds)) {
            return ['success' => false, 'message' => 'Jadwal baru tidak memiliki sisa kursi yang cukup.'];
        }

        $feeAmount        = 25000.00; // Flat admin reschedule fee per transaction
        $priceDiff        = max(0, ((float)$newTrip['adult_price'] * count($newSeatIds)) - (float)$booking['total_amount']);
        $totalAddPay      = $feeAmount + $priceDiff;
        $rescheduleCode   = 'RSC-' . date('Ymd') . '-' . strtoupper(substr(md5(uniqid()), 0, 5));

        $db = \Config\Database::connect();
        $db->transStart();

        // Save Reschedule Audit
        $this->rescheduleModel->insert([
            'old_booking_id'       => $booking['id'],
            'reschedule_code'      => $rescheduleCode,
            'old_trip_id'          => $booking['trip_id'],
            'new_trip_id'          => $newTripId,
            'fee_amount'           => $feeAmount,
            'price_diff_amount'    => $priceDiff,
            'total_additional_pay' => $totalAddPay,
            'status'               => 'completed',
            'processed_by_user_id' => $processedByUserId
        ]);

        // Restore old trip seats
        $this->tripModel->where('id', $booking['trip_id'])->set('available_seats', 'available_seats + ' . $booking['total_passengers'], false)->update();

        // Deduct new trip seats
        $this->tripModel->where('id', $newTripId)->set('available_seats', 'available_seats - ' . count($newSeatIds), false)->update();

        // Update Booking Record
        $this->bookingModel->update($booking['id'], [
            'trip_id'      => $newTripId,
            'total_amount' => (float)$newTrip['adult_price'] * count($newSeatIds),
            'final_amount' => $booking['final_amount'] + $totalAddPay,
            'status'       => 'confirmed'
        ]);

        // Update Passengers & Tickets
        $passengers = $this->passengerModel->where('booking_id', $booking['id'])->findAll();
        foreach ($passengers as $idx => $p) {
            $newSeatId = $newSeatIds[$idx] ?? $newSeatIds[0];
            $seatObj   = $this->seatModel->find($newSeatId);
            $seatNum   = $seatObj ? $seatObj['seat_number'] : $p['seat_number'];

            $this->passengerModel->update($p['id'], [
                'seat_id'     => $newSeatId,
                'seat_number' => $seatNum,
                'price'       => $newTrip['adult_price']
            ]);

            $this->ticketModel->where('passenger_id', $p['id'])->set([
                'trip_id'     => $newTripId,
                'seat_number' => $seatNum,
                'status'      => 'active'
            ])->update();
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return ['success' => false, 'message' => 'Gagal memproses reschedule. Silakan coba lagi.'];
        }

        return [
            'success'              => true,
            'reschedule_code'      => $rescheduleCode,
            'total_additional_pay' => $totalAddPay,
            'message'              => 'Reschedule jadwal berhasil diperbarui!'
        ];
    }
}
