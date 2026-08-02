<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 border-bottom pb-3 mb-4">
        <div>
            <span class="badge bg-primary rounded-pill mb-1">MANIFEST KEBERANGKATAN</span>
            <h4 class="fw-bold mb-1"><?= esc($manifest['origin_city']) ?> &rarr; <?= esc($manifest['destination_city']) ?></h4>
            <p class="text-secondary small mb-0">Trip Code: <strong><?= esc($manifest['trip_code']) ?></strong> | Tgl: <?= date('d M Y', strtotime($manifest['trip_date'])) ?> | Departure: <?= date('H:i', strtotime($manifest['departure_time'])) ?> WITA</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-secondary btn-sm rounded-3 fw-bold" onclick="window.print()"><i class="bi bi-printer me-1"></i> Cetak Manifest</button>
            <a href="<?= base_url('admin/checkin/scanner') ?>" class="btn btn-primary btn-sm rounded-3 fw-bold"><i class="bi bi-arrow-left me-1"></i> Kembali ke Scanner</a>
        </div>
    </div>

    <!-- Summary Stats -->
    <div class="row g-3 mb-4 text-center">
        <div class="col-4">
            <div class="bg-light p-3 rounded-3 border">
                <small class="text-muted d-block">Total Terpesan</small>
                <strong class="fs-4 text-dark"><?= $manifest['total_booked'] ?> / <?= $manifest['capacity'] ?></strong>
            </div>
        </div>
        <div class="col-4">
            <div class="bg-success bg-opacity-10 p-3 rounded-3 border border-success">
                <small class="text-success fw-bold d-block">Checked-In / Boarding</small>
                <strong class="fs-4 text-success"><?= $manifest['total_checked_in'] ?> Pnp</strong>
            </div>
        </div>
        <div class="col-4">
            <div class="bg-warning bg-opacity-10 p-3 rounded-3 border border-warning">
                <small class="text-warning fw-bold d-block">Belum Boarding</small>
                <strong class="fs-4 text-warning"><?= $manifest['total_absent'] ?> Pnp</strong>
            </div>
        </div>
    </div>

    <!-- Passenger Table -->
    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>No. Kursi</th>
                    <th>Nama Penumpang</th>
                    <th>No. Telepon / WA</th>
                    <th>Kode Booking</th>
                    <th>Kode Tiket</th>
                    <th>Status Boarding</th>
                    <th>Waktu Check-In</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($manifest['passengers'] as $p): ?>
                    <tr>
                        <td><span class="badge bg-primary fs-6">SEAT <?= esc($p['seat_number']) ?></span></td>
                        <td><strong class="text-dark"><?= esc($p['passenger_name']) ?></strong></td>
                        <td><?= esc($p['passenger_phone'] ?: $p['customer_phone']) ?></td>
                        <td><span class="badge bg-secondary"><?= esc($p['booking_code']) ?></span></td>
                        <td><code><?= esc($p['ticket_code']) ?></code></td>
                        <td>
                            <?php if ($p['ticket_status'] === 'checked_in'): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle-fill me-1"></i> HADIR / BOARDING</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark"><i class="bi bi-clock me-1"></i> BELUM HADIR</span>
                            <?php endif; ?>
                        </td>
                        <td><?= $p['checked_in_at'] ? date('H:i:s', strtotime($p['checked_in_at'])) : '-' ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
