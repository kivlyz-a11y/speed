<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Services\BookingService;
use App\Services\PaymentService;
use App\Services\TicketService;
use App\Services\CheckInService;

class ApiController extends BaseController
{
    protected $userModel;
    protected $bookingService;
    protected $paymentService;
    protected $ticketService;
    protected $checkInService;

    public function __construct()
    {
        $this->userModel      = new UserModel();
        $this->bookingService = new BookingService();
        $this->paymentService = new PaymentService();
        $this->ticketService  = new TicketService();
        $this->checkInService = new CheckInService();
    }

    /**
     * POST /api/v1/auth/login
     */
    public function login()
    {
        $json     = $this->request->getJSON(true) ?? $this->request->getPost();
        $email    = trim($json['email'] ?? '');
        $password = trim($json['password'] ?? '');

        $user = $this->userModel->where('email', $email)->first();
        if (!$user || !password_verify($password, $user['password_hash'])) {
            return $this->response->setStatusCode(401)->setJSON(['success' => false, 'message' => 'Email atau password salah.']);
        }

        $token = base64_encode($user['uuid'] . ':' . time());

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Login berhasil',
            'token'   => $token,
            'user'    => [
                'id'    => $user['id'],
                'uuid'  => $user['uuid'],
                'name'  => $user['name'],
                'email' => $user['email'],
                'phone' => $user['phone']
            ]
        ]);
    }

    /**
     * POST /api/v1/auth/register
     */
    public function register()
    {
        $json     = $this->request->getJSON(true) ?? $this->request->getPost();
        $name     = trim($json['name'] ?? '');
        $email    = trim($json['email'] ?? '');
        $phone    = trim($json['phone'] ?? '');
        $password = trim($json['password'] ?? '');

        if ($this->userModel->where('email', $email)->first()) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Email sudah terdaftar']);
        }

        $uuid = sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
        
        $userId = $this->userModel->insert([
            'uuid'          => $uuid,
            'role_id'       => 7, // Member
            'name'          => $name,
            'email'         => $email,
            'phone'         => $phone,
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'is_active'     => 1
        ]);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Pendaftaran akun member berhasil',
            'user_id' => $userId
        ]);
    }

    /**
     * GET /api/v1/schedules
     */
    public function schedules()
    {
        $originId      = (int) $this->request->getGet('origin_id');
        $destinationId = (int) $this->request->getGet('destination_id');
        $date          = $this->request->getGet('date') ?? date('Y-m-d');
        $passengers    = (int) ($this->request->getGet('passengers') ?? 1);

        $trips = $this->bookingService->searchTrips($originId, $destinationId, $date, $passengers);

        return $this->response->setJSON([
            'success' => true,
            'count'   => count($trips),
            'data'    => $trips
        ]);
    }

    /**
     * GET /api/v1/seats/{trip_id}
     */
    public function seats(int $tripId)
    {
        $sessionId = session_id() ?: 'API-SESS-' . uniqid();
        $tripData  = $this->bookingService->getTripSeatMap($tripId, $sessionId);

        if (!$tripData) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Trip tidak ditemukan']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $tripData
        ]);
    }

    /**
     * POST /api/v1/booking/create
     */
    public function createBooking()
    {
        $json = $this->request->getJSON(true);
        if (empty($json)) {
            return $this->response->setStatusCode(400)->setJSON(['success' => false, 'message' => 'Payload JSON tidak valid']);
        }

        $bookingData = [
            'user_id'        => $json['user_id'] ?? null,
            'trip_id'        => (int) $json['trip_id'],
            'customer_name'  => trim($json['customer_name']),
            'customer_email' => trim($json['customer_email']),
            'customer_phone' => trim($json['customer_phone']),
            'voucher_code'   => trim($json['voucher_code'] ?? ''),
            'session_id'     => session_id()
        ];

        $passengersData = [];
        foreach (($json['passengers'] ?? []) as $p) {
            $passengersData[] = [
                'seat_id'         => (int) $p['seat_id'],
                'seat_number'     => $p['seat_number'],
                'passenger_name'  => trim($p['name']),
                'passenger_phone' => trim($p['phone'] ?? $json['customer_phone']),
                'price'           => (float) $p['price']
            ];
        }

        $res = $this->bookingService->createBooking($bookingData, $passengersData);
        return $this->response->setJSON($res);
    }

    /**
     * GET /api/v1/tickets/{code}
     */
    public function getTicket(string $code)
    {
        $ticketData = $this->ticketService->getBookingTickets($code);
        if (!$ticketData) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Tiket tidak ditemukan']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $ticketData
        ]);
    }

    /**
     * POST /api/v1/checkin/scan
     */
    public function scanCheckin()
    {
        $json       = $this->request->getJSON(true);
        $ticketCode = trim($json['ticket_code'] ?? '');

        $res = $this->checkInService->processScan($ticketCode, session()->get('user_id'));
        return $this->response->setJSON($res);
    }

    /**
     * GET /api/v1/manifest/{trip_id}
     */
    public function getManifest(int $tripId)
    {
        $manifest = $this->checkInService->getTripManifest($tripId);
        if (!$manifest) {
            return $this->response->setStatusCode(404)->setJSON(['success' => false, 'message' => 'Manifest trip tidak ditemukan']);
        }

        return $this->response->setJSON([
            'success' => true,
            'data'    => $manifest
        ]);
    }
}
