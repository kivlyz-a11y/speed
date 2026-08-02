<?php

namespace App\Libraries;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class MidtransLibrary
{
    public function __construct()
    {
        Config::$serverKey    = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-DUMMY_KEY_FOR_TESTING');
        Config::$clientKey    = env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-DUMMY_KEY_FOR_TESTING');
        Config::$isProduction = (bool) env('MIDTRANS_IS_PRODUCTION', false);
        Config::$isSanitized  = (bool) env('MIDTRANS_IS_SANITIZED', true);
        Config::$is3ds        = (bool) env('MIDTRANS_IS_3DS', true);
    }

    /**
     * Create Midtrans Snap Token & Payment URL
     */
    public function createSnapTransaction(array $booking, array $passengers = [])
    {
        // Fallback for local testing or when server key is not real Midtrans key
        if (str_contains(Config::$serverKey, 'DUMMY_KEY')) {
            $mockToken = 'SNAP-MOCK-' . strtoupper(substr(md5(uniqid()), 0, 16));
            return [
                'token'        => $mockToken,
                'redirect_url' => base_url('checkout/mock-pay/' . $booking['booking_code']),
                'is_mock'      => true
            ];
        }

        try {
            $items = [];
            foreach ($passengers as $p) {
                $items[] = [
                    'id'       => 'SEAT-' . $p['seat_number'],
                    'price'    => (int) $p['price'],
                    'quantity' => 1,
                    'name'     => 'Tiket Speedboat Seat ' . $p['seat_number'] . ' (' . $p['passenger_name'] . ')'
                ];
            }

            if (!empty($booking['discount_amount']) && $booking['discount_amount'] > 0) {
                $items[] = [
                    'id'       => 'DISCOUNT',
                    'price'    => -1 * (int) $booking['discount_amount'],
                    'quantity' => 1,
                    'name'     => 'Diskon Voucher (' . ($booking['voucher_code'] ?? 'PROMO') . ')'
                ];
            }

            $params = [
                'transaction_details' => [
                    'order_id'     => $booking['booking_code'],
                    'gross_amount' => (int) $booking['final_amount'],
                ],
                'customer_details' => [
                    'first_name' => $booking['customer_name'],
                    'email'      => $booking['customer_email'],
                    'phone'      => $booking['customer_phone'],
                ],
                'item_details' => $items,
                'callbacks'    => [
                    'finish' => base_url('booking/success/' . $booking['booking_code'])
                ]
            ];

            $snapToken   = Snap::getSnapToken($params);
            $redirectUrl = "https://app.sandbox.midtrans.com/snap/v2/vtweb/" . $snapToken;

            return [
                'token'        => $snapToken,
                'redirect_url' => $redirectUrl,
                'is_mock'      => false
            ];
        } catch (\Exception $e) {
            log_message('error', 'Midtrans Snap Error: ' . $e->getMessage());
            // Return mock fallback on exception to ensure application flow is non-blocking during demo
            return [
                'token'        => 'SNAP-MOCK-' . strtoupper(substr(md5(uniqid()), 0, 16)),
                'redirect_url' => base_url('checkout/mock-pay/' . $booking['booking_code']),
                'is_mock'      => true
            ];
        }
    }
}
