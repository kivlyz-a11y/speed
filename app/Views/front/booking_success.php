<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<?php 
$isPaid = ($booking['payment_status'] ?? 'unpaid') === 'paid'; 
?>

<div class="container py-5">
    <?php if ($isPaid): ?>
        <!-- Success / Paid Banner -->
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center bg-success bg-opacity-10 text-success rounded-circle p-3 mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-check-lg display-4"></i>
            </div>
            <h2 class="fw-extrabold text-success mb-1">Pembayaran Berhasil & E-Ticket Terbit!</h2>
            <p class="text-secondary mb-1">Kode Booking: <strong class="text-dark fs-5"><?= esc($booking['booking_code']) ?></strong></p>
            <?php if (!empty($booking['transaction_time'])): ?>
                <p class="small text-muted mb-0"><i class="bi bi-clock-check-fill text-success me-1"></i> Waktu Pembayaran Lunas: <strong class="text-dark"><?= date('d M Y, H:i', strtotime($booking['transaction_time'])) ?> WITA</strong></p>
            <?php endif; ?>
            
            <div class="d-flex flex-wrap justify-content-center gap-2 mt-3">
                <button type="button" class="btn btn-primary px-4 shadow rounded-3 fw-bold" id="btnOpenSeatModal">
                    <i class="bi bi-grid-3x3-gap-fill me-2"></i> Pilih / Ganti Kursi Speed Boat
                </button>

                <a href="<?= base_url('ticket/pdf/' . $booking['booking_code']) ?>" class="btn btn-cititrans px-4 shadow rounded-3 fw-bold" target="_blank">
                    <i class="bi bi-file-earmark-pdf-fill me-2"></i> Unduh E-Ticket (PDF)
                </a>

                <button type="button" class="btn btn-outline-warning rounded-3 fw-bold" data-bs-toggle="modal" data-bs-target="#refundModal">
                    <i class="bi bi-arrow-counterclockwise me-1"></i> Ajukan Refund
                </button>
            </div>
        </div>

        <?php 
        $hasUnassignedSeats = false;
        foreach ($booking['tickets'] as $t) {
            if (empty($t['seat_number']) || $t['seat_number'] === 'Belum Dipilih') {
                $hasUnassignedSeats = true;
                break;
            }
        }
        ?>

        <?php if ($hasUnassignedSeats): ?>
            <div class="row justify-content-center mb-4">
                <div class="col-lg-10">
                    <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                        <div>
                            <h6 class="fw-bold text-dark mb-1"><i class="bi bi-exclamation-circle-fill text-warning me-2 fs-5"></i> Kursi Belum Dipilih!</h6>
                            <p class="small text-secondary mb-0">Silakan tentukan nomor kursi posisi favorit Anda dan rombongan di denah kapal sebelum keberangkatan.</p>
                        </div>
                        <button type="button" class="btn btn-warning fw-bold rounded-3 text-dark px-4 py-2" id="btnAlertSelectSeat">
                            <i class="bi bi-cursor-fill me-1"></i> Pilih Kursi Sekarang
                        </button>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    <?php else: ?>

        <!-- Unpaid / Pending Payment Banner -->
        <div class="text-center mb-5">
            <div class="d-inline-flex align-items-center justify-content-center bg-warning bg-opacity-10 text-warning rounded-circle p-3 mb-3" style="width: 80px; height: 80px;">
                <i class="bi bi-clock-history display-4 text-warning"></i>
            </div>
            <h2 class="fw-extrabold text-warning mb-1">Status Pembayaran Belum Selesai</h2>
            <p class="text-secondary mb-2">Kode Pesanan: <strong class="text-dark fs-5"><?= esc($booking['booking_code']) ?></strong></p>
            <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill"><i class="bi bi-exclamation-triangle-fill me-1"></i> MENUNGGU PEMBAYARAN</span>

            <div class="bg-white p-4 rounded-4 shadow-sm max-w-md mx-auto mt-4 border border-warning text-start" style="max-width: 520px;">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="text-muted fw-bold">Total Yang Harus Dibayar:</span>
                    <strong class="fs-3 fw-extrabold text-primary">Rp <?= number_format($booking['final_amount'], 0, ',', '.') ?></strong>
                </div>
                
                <?php if (!empty($booking['expired_at'])): ?>
                    <div class="small text-muted mb-3 pb-2 border-bottom">
                        <i class="bi bi-stopwatch me-1 text-danger"></i> Batas Pembayaran: <strong class="text-dark"><?= date('d M Y, H:i', strtotime($booking['expired_at'])) ?> WITA</strong>
                    </div>
                <?php endif; ?>

                <div class="d-grid gap-2">
                    <a href="<?= base_url('payment/checkout/' . $booking['booking_code']) ?>" class="btn btn-cititrans btn-lg fw-bold shadow">
                        <i class="bi bi-credit-card-2-back-fill me-2"></i> Lanjutkan Pembayaran Sekarang
                    </a>
                </div>
            </div>
        </div>

        <div class="row justify-content-center mb-4">
            <div class="col-lg-10">
                <div class="alert alert-warning border-0 shadow-sm rounded-4 p-4 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h6 class="fw-bold text-dark mb-1"><i class="bi bi-info-circle-fill text-warning me-2 fs-5"></i> Pembayaran Belum Terverifikasi</h6>
                        <p class="small text-secondary mb-0">Tiket dan fitur pemilihan kursi akan otomatis aktif setelah pembayaran dikonfirmasi lunas.</p>
                    </div>
                    <a href="<?= base_url('payment/checkout/' . $booking['booking_code']) ?>" class="btn btn-warning fw-bold text-dark px-4 py-2 rounded-3 shadow-sm">
                        <i class="bi bi-wallet2 me-1"></i> Bayar Sekarang
                    </a>
                </div>
            </div>
        </div>

    <?php endif; ?>

    <!-- E-Tickets List Cards -->
    <div class="row justify-content-center g-4">
        <div class="col-lg-10">
            <h5 class="fw-bold mb-3"><i class="bi bi-ticket-detailed-fill text-primary me-2"></i> Rincian Pesanan Tiket Penumpang</h5>

            <?php foreach ($booking['tickets'] as $t): ?>
                <div class="ticket-container p-4 mb-4 shadow-sm rounded-4 border bg-white">
                    <div class="row align-items-center g-4">
                        <div class="col-md-8 border-end-md">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <span class="badge bg-primary rounded-pill mb-1">E-TICKET CITITRANS</span>
                                    <h5 class="fw-bold mb-0 text-dark"><?= esc($booking['origin_name']) ?> &rarr; <?= esc($booking['destination_name']) ?></h5>
                                </div>
                                <?php if ($isPaid): ?>
                                    <span class="badge bg-success bg-opacity-10 text-success fw-bold px-3 py-2">
                                        <i class="bi bi-check-circle-fill me-1"></i> LUNAS / <?= strtoupper($t['status']) ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning bg-opacity-25 text-dark fw-bold px-3 py-2">
                                        <i class="bi bi-clock me-1"></i> MENUNGGU PEMBAYARAN
                                    </span>
                                <?php endif; ?>
                            </div>

                            <div class="row g-3">
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Nama Penumpang</small>
                                    <strong class="text-dark"><?= esc($t['passenger_name']) ?></strong>
                                    <span class="badge bg-light text-secondary border ms-1" style="font-size: 0.72rem;">
                                        <i class="bi bi-gender-<?= ($t['passenger_gender'] ?? 'male') === 'female' ? 'female text-danger' : 'male text-primary' ?> me-1"></i>
                                        <?= ($t['passenger_gender'] ?? 'male') === 'female' ? 'Perempuan' : 'Laki-laki' ?>
                                    </span>
                                    <?php if (!empty($t['passenger_nik'])): ?>
                                        <small class="d-block text-muted">NIK: <?= esc($t['passenger_nik']) ?></small>
                                    <?php endif; ?>
                                </div>
                                <div class="col-6 col-sm-4">
                                    <small class="text-muted d-block">Nomor Kursi</small>
                                    <?php if (!$isPaid): ?>
                                        <span class="badge bg-secondary text-white fw-bold px-2 py-1"><i class="bi bi-lock me-1"></i> Pilih Setelah Bayar</span>
                                    <?php elseif (empty($t['seat_number']) || $t['seat_number'] === 'Belum Dipilih'): ?>
                                        <span class="badge bg-warning text-dark fw-bold px-2 py-1"><i class="bi bi-dash-circle me-1"></i> Belum Dipilih</span>
                                    <?php else: ?>
                                        <span class="fs-5 fw-extrabold text-primary"><i class="bi bi-journal-bookmark-fill me-1"></i> SEAT <?= esc($t['seat_number']) ?></span>
                                    <?php endif; ?>
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
                            <img src="<?= $t['qr_data_uri'] ?>" alt="QR Code" class="img-fluid mb-2 border p-2 rounded-3 <?= !$isPaid ? 'opacity-50' : '' ?>" style="max-width: 150px;">
                            <small class="d-block text-muted">
                                <?= $isPaid ? 'Tunjukkan QR Code ini di dermaga saat check-in' : 'QR Code aktif setelah pembayaran lunas' ?>
                            </small>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php if ($isPaid): ?>
<!-- Modal Select Seat Post Payment -->
<div class="modal fade" id="selectSeatModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-grid-3x3-gap-fill text-cyan me-2"></i> Pilih Kursi Speed Boat (Kelola Pesanan)</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Left Column: Interactive Seat Grid Layout -->
                    <div class="col-lg-6">
                        <div class="text-center mb-3">
                            <h6 class="fw-bold mb-1"><?= esc($booking['boat_name']) ?></h6>
                            <div class="d-flex justify-content-center gap-3 small my-2">
                                <span><i class="bi bi-square-fill text-white border rounded me-1"></i> Tersedia</span>
                                <span><i class="bi bi-square-fill text-success me-1"></i> Dipilih</span>
                                <span><i class="bi bi-square-fill text-secondary opacity-50 me-1"></i> Terisi (Pnp Lain)</span>
                            </div>
                        </div>

                        <div class="boat-layout-container">
                            <div class="boat-cockpit"><i class="bi bi-compass me-2"></i> ANJUNGAN KAPTEN / DEPAN</div>
                            <div id="seatGridContainer" class="py-2">
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">Memuat denah denah kursi...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Column: Passenger Assignment Controls -->
                    <div class="col-lg-6">
                        <div class="cititrans-card p-4 h-100 bg-light">
                            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-person-lines-fill text-primary me-2"></i> Penetapan Kursi Penumpang</h6>
                            
                            <p class="small text-muted mb-3">Pilihlah salah satu penumpang di bawah ini, kemudian klik posisi kursi yang diinginkan pada denah kapal sebelah kiri.</p>

                            <div id="passengerSeatAssignmentList" class="mb-4">
                                <?php foreach ($booking['tickets'] as $idx => $t): ?>
                                    <div class="p-3 bg-white rounded-3 mb-3 border passenger-seat-card" data-passenger-id="<?= $t['passenger_id'] ?>" data-passenger-index="<?= $idx ?>">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <small class="text-muted d-block">Penumpang <?= $idx + 1 ?></small>
                                                <strong class="text-dark fs-6"><?= esc($t['passenger_name']) ?></strong>
                                            </div>
                                            <div>
                                                <span class="badge bg-primary fs-6 assigned-seat-badge" id="badgePnpSeat_<?= $t['passenger_id'] ?>">
                                                    <?= !empty($t['seat_number']) && $t['seat_number'] !== 'Belum Dipilih' ? 'SEAT ' . esc($t['seat_number']) : 'Belum Dipilih' ?>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>

                            <button type="button" class="btn btn-cititrans btn-lg w-100 py-3 fw-bold" id="btnSaveSeatAssignments">
                                <i class="bi bi-check-circle-fill me-2"></i> Simpan Pilihan Kursi
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

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

<?= $this->section('scripts') ?>
<?php if ($isPaid): ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const seatModalElement = document.getElementById('selectSeatModal');
    if (!seatModalElement) return;

    const seatModal = new bootstrap.Modal(seatModalElement);
    const tripId = <?= (int) $booking['trip_id'] ?>;
    const bookingCode = '<?= esc($booking['booking_code']) ?>';

    // Store passenger list & assignments { passenger_id: seat_id }
    let passengersList = <?= json_encode($booking['tickets']) ?>;
    let activePassengerId = passengersList[0] ? passengersList[0].passenger_id : null;
    let selectedAssignments = {}; // { passenger_id: { seat_id, seat_number } }

    // Init existing seat assignments
    passengersList.forEach(p => {
        if (p.seat_number && p.seat_number !== 'Belum Dipilih') {
            selectedAssignments[p.passenger_id] = {
                seat_id: p.seat_id || 0,
                seat_number: p.seat_number
            };
        }
    });

    const openBtn = document.getElementById('btnOpenSeatModal');
    const alertBtn = document.getElementById('btnAlertSelectSeat');

    if (openBtn) openBtn.addEventListener('click', openModal);
    if (alertBtn) alertBtn.addEventListener('click', openModal);

    function openModal() {
        fetchSeatMap();
        highlightActivePassengerCard(activePassengerId);
        seatModal.show();
    }

    // Handle passenger card click to make active
    document.querySelectorAll('.passenger-seat-card').forEach(card => {
        card.addEventListener('click', function() {
            activePassengerId = parseInt(this.dataset.passengerId);
            highlightActivePassengerCard(activePassengerId);
        });
    });

    function highlightActivePassengerCard(pId) {
        document.querySelectorAll('.passenger-seat-card').forEach(card => {
            if (parseInt(card.dataset.passengerId) === pId) {
                card.classList.add('border-primary', 'shadow-sm', 'bg-white');
                card.classList.remove('bg-light');
            } else {
                card.classList.remove('border-primary', 'shadow-sm', 'bg-white');
                card.classList.add('bg-light');
            }
        });
    }

    function fetchSeatMap() {
        const container = document.getElementById('seatGridContainer');
        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary"></div></div>';

        fetch('<?= base_url("booking/seat-map/") ?>' + tripId)
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    container.innerHTML = '<div class="alert alert-danger">' + data.message + '</div>';
                    return;
                }
                renderSeatGrid(data.data.seats, data.data.total_rows || 8, data.data.total_cols || 4);
            });
    }

    function renderSeatGrid(seats, totalRows, totalCols) {
        const container = document.getElementById('seatGridContainer');
        container.innerHTML = '';

        let seatMapByRow = {};
        seats.forEach(s => {
            if (!seatMapByRow[s.row_num]) seatMapByRow[s.row_num] = [];
            seatMapByRow[s.row_num].push(s);
        });

        for (let r = 1; r <= totalRows; r++) {
            const rowDiv = document.createElement('div');
            rowDiv.className = 'seat-grid-row';
            
            const rowSeats = seatMapByRow[r] || [];
            rowSeats.sort((a, b) => a.col_num - b.col_num);

            rowSeats.forEach((s, idx) => {
                const seatBtn = document.createElement('div');
                
                // Check if this seat is assigned to current booking passengers
                let assignedToPassenger = null;
                for (let pId in selectedAssignments) {
                    if (selectedAssignments[pId].seat_id === s.id || selectedAssignments[pId].seat_number === s.seat_number) {
                        assignedToPassenger = pId;
                        break;
                    }
                }

                let statusClass = s.status;
                if (assignedToPassenger) {
                    statusClass = 'selected';
                }

                seatBtn.className = `seat-item ${statusClass} ${s.seat_class}`;
                seatBtn.innerText = s.seat_number;
                seatBtn.dataset.seatId = s.id;
                seatBtn.dataset.seatNum = s.seat_number;

                if (s.status === 'available' || assignedToPassenger) {
                    seatBtn.addEventListener('click', () => selectSeatForActivePassenger(s));
                }

                rowDiv.appendChild(seatBtn);

                if (idx === 1 && totalCols > 2) {
                    const aisle = document.createElement('div');
                    aisle.className = 'aisle-gap';
                    rowDiv.appendChild(aisle);
                }
            });

            container.appendChild(rowDiv);
        }
    }

    function selectSeatForActivePassenger(seat) {
        if (!activePassengerId) return;

        // Assign seat to active passenger
        selectedAssignments[activePassengerId] = {
            seat_id: seat.id,
            seat_number: seat.seat_number
        };

        // Update badge
        const badge = document.getElementById('badgePnpSeat_' + activePassengerId);
        if (badge) {
            badge.innerText = 'SEAT ' + seat.seat_number;
            badge.className = 'badge bg-success fs-6 assigned-seat-badge';
        }

        // Re-render grid to reflect selection
        fetchSeatMap();

        // Move active focus to next unassigned passenger if any
        const nextPnp = passengersList.find(p => !selectedAssignments[p.passenger_id]);
        if (nextPnp) {
            activePassengerId = nextPnp.passenger_id;
            highlightActivePassengerCard(activePassengerId);
        }
    }

    // Save assignments button click
    document.getElementById('btnSaveSeatAssignments').addEventListener('click', function() {
        let payloadAssignments = [];
        for (let pId in selectedAssignments) {
            payloadAssignments.push({
                passenger_id: parseInt(pId),
                seat_id: selectedAssignments[pId].seat_id
            });
        }

        if (payloadAssignments.length === 0) {
            alert('Pilihlah minimal 1 kursi.');
            return;
        }

        const btn = this;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Menyimpan Pilihan Kursi...';

        fetch('<?= base_url("booking/assign-seats") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                booking_code: bookingCode,
                assignments: payloadAssignments
            })
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                location.reload();
            } else {
                alert(res.message);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Simpan Pilihan Kursi';
            }
        })
        .catch(err => {
            alert('Terjadi kesalahan koneksi.');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i> Simpan Pilihan Kursi';
        });
    });
});
</script>
<?php endif; ?>
<?= $this->endSection() ?>
