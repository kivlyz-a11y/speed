<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket <?= esc($booking['booking_code']) ?></title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; color: #1E293B; margin: 0; padding: 20px; font-size: 12px; }
        .header { background: #0F2240; color: #FFFFFF; padding: 15px; border-radius: 8px; margin-bottom: 20px; }
        .header h2 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0 0; opacity: 0.8; font-size: 11px; }
        .ticket-box { border: 2px dashed #CBD5E1; border-radius: 8px; padding: 15px; margin-bottom: 15px; background: #F8FAFC; }
        .table-info { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .table-info td { padding: 6px; vertical-align: top; }
        .label { font-size: 10px; color: #64748B; text-transform: uppercase; font-weight: bold; }
        .val { font-size: 12px; font-weight: bold; color: #0F2240; }
        .qr-cell { text-align: center; width: 140px; }
        .qr-cell img { width: 120px; height: 120px; }
        .footer-note { font-size: 10px; color: #64748B; text-align: center; margin-top: 30px; border-top: 1px solid #E2E8F0; padding-top: 10px; }
    </style>
</head>
<body>

    <div class="header">
        <h2>SpeedExpress - Official E-Ticket</h2>
        <p>Standar Penyeberangan Speed Boat Cititrans | Kode Booking: <strong><?= esc($booking['booking_code']) ?></strong></p>
    </div>

    <?php foreach ($booking['tickets'] as $t): ?>
        <div class="ticket-box">
            <table class="table-info">
                <tr>
                    <td style="width: 70%;">
                        <div style="font-size: 16px; font-weight: bold; color: #0F2240; margin-bottom: 10px;">
                            <?= esc($booking['origin_name']) ?> &rarr; <?= esc($booking['destination_name']) ?>
                        </div>
                        
                        <table style="width: 100%;">
                            <tr>
                                <td style="width: 50%;">
                                    <div class="label">Nama Penumpang</div>
                                    <div class="val"><?= esc($t['passenger_name']) ?></div>
                                </td>
                                <td style="width: 50%;">
                                    <div class="label">Nomor Kursi</div>
                                    <div class="val" style="font-size: 16px; color: #0D6EFD;">SEAT <?= esc($t['seat_number']) ?></div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="label">Tanggal Keberangkatan</div>
                                    <div class="val"><?= date('d M Y', strtotime($booking['trip_date'])) ?></div>
                                </td>
                                <td>
                                    <div class="label">Jam Keberangkatan</div>
                                    <div class="val"><?= date('H:i', strtotime($booking['departure_time'])) ?> WITA</div>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <div class="label">Kapal Speed Boat</div>
                                    <div class="val"><?= esc($booking['boat_name']) ?></div>
                                </td>
                                <td>
                                    <div class="label">Kode Tiket</div>
                                    <div class="val"><?= esc($t['ticket_code']) ?></div>
                                </td>
                            </tr>
                        </table>
                    </td>

                    <td class="qr-cell">
                        <img src="<?= $t['qr_data_uri'] ?>" alt="QR Code">
                        <div style="font-size: 9px; color: #64748B; margin-top: 4px;">SCAN UNTUK CHECK-IN</div>
                    </td>
                </tr>
            </table>
        </div>
    <?php endforeach; ?>

    <div class="footer-note">
        Tunjukkan E-Ticket ini (cetak atau digital di smartphone) kepada petugas di dermaga 30 menit sebelum keberangkatan.<br>
        &copy; <?= date('Y') ?> SpeedExpress Ocean Transit. All rights reserved.
    </div>

</body>
</html>
