<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1"><i class="bi bi-graph-up-arrow text-primary me-2"></i> Laporan Penjualan Tiket & Pendapatan</h4>
            <p class="text-secondary small mb-0">Periode: <?= date('d M Y', strtotime($start_date)) ?> s/d <?= date('d M Y', strtotime($end_date)) ?></p>
        </div>

        <div class="d-flex gap-2">
            <a href="<?= base_url('admin/reports/sales/excel?start_date=' . $start_date . '&end_date=' . $end_date) ?>" class="btn btn-success fw-bold">
                <i class="bi bi-file-earmark-excel-fill me-1"></i> Export Excel
            </a>
            <a href="<?= base_url('admin/reports/sales/pdf?start_date=' . $start_date . '&end_date=' . $end_date) ?>" class="btn btn-danger fw-bold" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Export PDF
            </a>
        </div>
    </div>

    <!-- Filter Form -->
    <form action="<?= base_url('admin/reports/sales') ?>" method="GET" class="bg-light p-3 rounded-3 mb-4">
        <div class="row g-2 align-items-end">
            <div class="col-md-4">
                <label class="form-label small fw-bold">Tanggal Mulai</label>
                <input type="date" name="start_date" class="form-control" value="<?= esc($start_date) ?>">
            </div>
            <div class="col-md-4">
                <label class="form-label small fw-bold">Tanggal Selesai</label>
                <input type="date" name="end_date" class="form-control" value="<?= esc($end_date) ?>">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="bi bi-filter me-1"></i> Filter Laporan</button>
            </div>
        </div>
    </form>

    <!-- Table List -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>Kode Booking</th>
                    <th>Tgl Transaksi</th>
                    <th>Nama Pemesan</th>
                    <th>No. HP</th>
                    <th>Jml Pnp</th>
                    <th>Total Bayar</th>
                    <th>Status Bayar</th>
                    <th>Rute</th>
                    <th>Kapal</th>
                </tr>
            </thead>
            <tbody>
                <?php $totalRev = 0; ?>
                <?php foreach ($sales as $s): ?>
                    <?php if ($s['payment_status'] === 'paid') $totalRev += $s['final_amount']; ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= esc($s['booking_code']) ?></span></td>
                        <td><?= date('d/m/Y H:i', strtotime($s['created_at'])) ?></td>
                        <td><strong class="text-dark"><?= esc($s['customer_name']) ?></strong></td>
                        <td><?= esc($s['customer_phone']) ?></td>
                        <td><span class="badge bg-primary"><?= esc($s['total_passengers']) ?> Pnp</span></td>
                        <td><strong class="text-success">Rp <?= number_format($s['final_amount'], 0, ',', '.') ?></strong></td>
                        <td>
                            <span class="badge bg-<?= $s['payment_status'] === 'paid' ? 'success' : 'danger' ?>">
                                <?= strtoupper($s['payment_status']) ?>
                            </span>
                        </td>
                        <td><small><?= esc($s['origin_name']) ?> &rarr; <?= esc($s['destination_name']) ?></small></td>
                        <td><small><?= esc($s['boat_name']) ?></small></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot class="table-light fs-6 fw-bold">
                <tr>
                    <td colspan="5" class="text-end">Total Pendapatan Tiket Lunas:</td>
                    <td class="text-success fs-5">Rp <?= number_format($totalRev, 0, ',', '.') ?></td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
