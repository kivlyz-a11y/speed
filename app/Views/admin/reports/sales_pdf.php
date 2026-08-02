<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan Tiket Speed Boat</title>
    <style>
        body { font-family: Helvetica, Arial, sans-serif; font-size: 10px; color: #1E293B; margin: 0; padding: 15px; }
        h2 { font-size: 16px; margin-bottom: 4px; color: #0F2240; }
        p { font-size: 10px; color: #64748B; margin-top: 0; margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #CBD5E1; padding: 6px; text-align: left; }
        th { background: #0F2240; color: #FFFFFF; font-weight: bold; }
        .text-right { text-align: right; }
        .total-row { background: #F1F5F9; font-weight: bold; font-size: 11px; }
    </style>
</head>
<body>

    <h2>Laporan Penjualan Tiket & Pendapatan Speed Boat</h2>
    <p>Periode: <?= date('d M Y', strtotime($start_date)) ?> s/d <?= date('d M Y', strtotime($end_date)) ?> | Generated: <?= date('d/m/Y H:i:s') ?></p>

    <table>
        <thead>
            <tr>
                <th>Kode Booking</th>
                <th>Tgl Transaksi</th>
                <th>Nama Pemesan</th>
                <th>No. HP</th>
                <th>Jml Pnp</th>
                <th class="text-right">Total (Rp)</th>
                <th>Status</th>
                <th>Rute Penyeberangan</th>
                <th>Armada</th>
            </tr>
        </thead>
        <tbody>
            <?php $total = 0; ?>
            <?php foreach ($sales as $s): ?>
                <?php if ($s['payment_status'] === 'paid') $total += $s['final_amount']; ?>
                <tr>
                    <td><?= esc($s['booking_code']) ?></td>
                    <td><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                    <td><?= esc($s['customer_name']) ?></td>
                    <td><?= esc($s['customer_phone']) ?></td>
                    <td><?= esc($s['total_passengers']) ?></td>
                    <td class="text-right"><?= number_format($s['final_amount'], 0, ',', '.') ?></td>
                    <td><?= strtoupper($s['payment_status']) ?></td>
                    <td><?= esc($s['origin_name']) ?> - <?= esc($s['destination_name']) ?></td>
                    <td><?= esc($s['boat_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr class="total-row">
                <td colspan="5" class="text-right">TOTAL PENDAPATAN LUNAS:</td>
                <td class="text-right" style="color: #059669;">Rp <?= number_format($total, 0, ',', '.') ?></td>
                <td colspan="3"></td>
            </tr>
        </tfoot>
    </table>

</body>
</html>
