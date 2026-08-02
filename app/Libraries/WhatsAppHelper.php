<?php

namespace App\Libraries;

class WhatsAppHelper
{
    /**
     * Send simulated / API WhatsApp Notification message
     */
    public static function sendNotification(string $phone, string $message): bool
    {
        // Sanitize phone number (Convert 08... to 628...)
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        log_message('info', "Simulated WhatsApp Dispatch to [{$phone}]: {$message}");

        // Store dispatch log in session or temporary system logger for UI feedback
        $logs = session()->get('wa_dispatch_logs') ?? [];
        $logs[] = [
            'phone'      => $phone,
            'message'    => $message,
            'sent_at'    => date('Y-m-d H:i:s'),
            'status'     => 'SENT_SIMULATED'
        ];
        session()->set('wa_dispatch_logs', array_slice($logs, -10));

        return true;
    }
}
