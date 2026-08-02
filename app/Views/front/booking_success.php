<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <!-- Success Banner -->
    <div class="text-center mb-5">
        <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3" style="width: 80px; height: 80px;">
            <i class="bi bi-check-lg display-4"></i>
        </div>
        <h2 class="fw-extrabold text-success mb-1">Pembayaran Berhasil & E-Ticket Terbit!</h2>
        <p class="text-secondary">Kode Booking: <strong class="text-dark fs-5"><?= esc($booking['booking_code']) ?></strong></p>
        <div class="d-flex justify-content-center gap-2 mt-3">
            <a href="<?= base_url('ticket/pdf/' . $booking['booking_code']) ?>" class="btn btn-cititrans px-4 shadow" target="_blank">
                <i class="bi bi-file-earmark-pdf-fill me-2"></i> Unduh E-Ticket (PDF)
            </a>
            <button type="button" class="btn btn-outline-warning rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#refundModal">
                <i class="bi bi-arrow-counterclockwise me-1"></i> Ajukan Refund
            </button>
        </div>
    </div>

    <!-- E-Tickets List Cards -->
    <div class="row justify-content-center g-4">
        <div class="col-lg-10">
            <h5 class="fw-bold mb-3"><i class="bi bi-ticket-detailed-fill text-primary me-2"></i> E-Ticket Boarding Penumpang</h5>

            <?php foreach ($booking['tickets'] as $t): ?>
                <div class="ticket-container p-4 mb-4 shadow-sm">
                    <div class="row align-items-center g-4">
                        <div class="col-md-8 border-end-md">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-primary rounded-pill mb-1">E-TICKET CITITRANS</span>
                                    <h5 class="fw-bold mb-0 text-dark"><?= esc($booking['origin_name']) ?> &rarr; <?= esc($booking['destination_name']) ?></h5>
                                </div>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2">
                                    <i class="bi bi-check-circle-fill me-1"></i> <?= strtoupper($t['status']) ?>
                                </span>
                            </div>

                            <div class="row g-3">
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Nama Penumpang</small>
                                    <strong class="text-dark"><?= esc($t['passenger_name']) ?></strong>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Nomor Kursi</small>
                                    <span class="fs-5 fw-extrabold text-primary">SEAT <?= esc($t['seat_number']) ?></span>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Kapal / Trip</small>
                                    <strong class="text-dark"><?= esc($booking['boat_name']) ?></strong>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Tgl Keberangkatan</small>
                                    <strong class="text-dark"><?= date('d M Y', strtotime($booking['trip_date'])) ?></strong>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Jam Keberangkatan</small>
                                    <strong class="text-dark"><?= date('H:i', strtotime($booking['departure_time'])) ?> WITA</strong>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Kode Tiket</small>
                                    <strong class="text-dark"><?= esc($t['ticket_code']) ?></strong>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 text-center">
                            <img src="<?= $t['qr_data_uri'] ?>" alt="QR Code" class="img-fluid mb-2 border p-2 rounded-3" style="max-width: 150px;">
                            <small class="d-block text-muted">Tunjukkan QR Code ini di dermaga saat check-in</small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- Refund Request Modal -->
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title fw-bold"><i class="bi bi-arrow-counterclockwise me-2"></i> Pengajuan Refund Tiket</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="<?= base_url('ticket/refund/submit') ?>" method="POST">
                <div class="modal-body p-4">
                    <input type="hidden" name="booking_code" value="<?= esc($booking['booking_code']) ?>">
                    
                    <div class="alert alert-warning small rounded-3 mb-3">
                        <i class="bi bi-exclamation-triangle-fill me-1"></i> Biaya administrasi refund sebesar <strong>10%</strong>. Dana akan ditransfer ke rekening bank Anda setelah disetujui.
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alasan Pengajuan Refund</label>
                        <textarea name="reason" class="form-control" rows="3" required placeholder="Contoh: Perubahan rencana perjalanan"></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Bank</label>
                        <select name="bank_name" class="form-select" required>
                            <option value="BCA">BCA</option>
                            <option value="BNI">BNI</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">No. Rekening</label>
                            <input type="text" name="account_number" class="form-control" required placeholder="1234567890">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Atas Nama Rekening</label>
                            <input type="text" name="account_holder" class="form-control" required placeholder="Sesuai buku tabungan">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-warning fw-bold">Kirim Pengajuan Refund</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
