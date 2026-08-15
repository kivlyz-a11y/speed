<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<div class="container py-5">

    <!-- Search Summary Header -->
    <div class="cititrans-card p-4 mb-4 bg-primary text-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <span class="badge bg-warning text-dark fw-bold mb-2"><i class="bi bi-compass me-1"></i> PELAYARAN SPEED BOAT BMK</span>
                <h3 class="fw-bold mb-1">
                    <?= esc($trips[0]['origin_name'] ?? 'Kota Asal') ?> &rarr; <?= esc($trips[0]['destination_name'] ?? 'Kota Tujuan') ?>
                </h3>
                <p class="mb-0 text-white-50"><i class="bi bi-calendar3 me-1"></i> Keberangkatan: <?= date('d F Y', strtotime($search_date)) ?> | <?= esc($search_pass) ?> Penumpang (<?= esc($search_male_pass ?? 1) ?> Laki-laki, <?= esc($search_female_pass ?? 0) ?> Perempuan)</p>
            </div>
            <a href="<?= base_url('/') ?>" class="btn btn-outline-light rounded-pill px-4 btn-sm fw-bold align-self-start align-self-md-center">Ubah Pencarian</a>
        </div>
    </div>

    <div class="row g-4">
        <!-- Filter Column -->
        <div class="col-lg-3">
            <div class="cititrans-card p-4 mb-4">
                <h6 class="fw-bold mb-3"><i class="bi bi-funnel-fill text-primary me-2"></i> Filter Jadwal</h6>
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Waktu Keberangkatan</label>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked id="filterPagi">
                        <label class="form-check-label small" for="filterPagi">Pagi (06:00 - 11:59)</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" checked id="filterSiang">
                        <label class="form-check-label small" for="filterSiang">Siang / Sore (12:00 - 18:00)</label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Schedule Cards List -->
        <div class="col-lg-9">
            <h5 class="fw-bold mb-3 d-flex align-items-center">
                Daftar Jadwal Tersedia <span class="badge bg-primary ms-2 rounded-pill"><?= count($trips) ?> Jadwal</span>
            </h5>

            <?php if (empty($trips)): ?>
                <div class="cititrans-card p-5 text-center">
                    <i class="bi bi-exclamation-circle text-warning display-3 mb-3"></i>
                    <h5 class="fw-bold">Jadwal Tidak Ditemukan</h5>
                    <p class="text-secondary">Tidak ada jadwal penyeberangan yang tersedia pada tanggal ini. Silakan coba pilih tanggal atau rute lain.</p>
                </div>
            <?php else: ?>
                <?php foreach ($trips as $t): ?>
                    <div class="cititrans-card schedule-card p-4 mb-3">
                        <div class="row align-items-center g-3">
                            <div class="col-md-3">
                                <div class="time-badge d-inline-block mb-1">
                                    <i class="bi bi-clock me-1"></i> <?= date('H:i', strtotime($t['departure_time'])) ?> WITA
                                </div>
                                <div class="text-muted small">Tiba: ~<?= date('H:i', strtotime($t['arrival_time'])) ?> WITA</div>
                                <div class="small fw-semibold text-primary mt-1"><i class="bi bi-stopwatch me-1"></i> Durasi <?= esc($t['estimated_duration_minutes']) ?> mnt</div>
                            </div>

                            <div class="col-md-4">
                                <h6 class="fw-bold mb-1"><?= esc($t['boat_name']) ?> <span class="badge bg-secondary opacity-75 small"><?= esc($t['boat_code']) ?></span></h6>
                                <p class="text-secondary small mb-1"><i class="bi bi-geo-alt me-1"></i> Keberangkatan: Dermaga <?= esc($t['origin_name']) ?></p>
                                <span class="badge bg-success bg-opacity-10 text-success fw-bold"><i class="bi bi-ticket-perforated me-1"></i> Tersisa <?= esc($t['available_seats']) ?> Kursi</span>
                            </div>

                            <div class="col-md-5 text-md-end">
                                <div class="text-muted small">Mulai Dari</div>
                                <div class="fs-4 fw-extrabold text-primary mb-2">Rp <?= number_format($t['adult_price'], 0, ',', '.') ?> <span class="fs-6 fw-normal text-muted">/pnp</span></div>
                                <button type="button" class="btn btn-cititrans px-4 btn-select-trip" data-trip-id="<?= $t['id'] ?>" data-trip-code="<?= $t['trip_code'] ?>" data-boat-name="<?= esc($t['boat_name']) ?>" data-price="<?= $t['adult_price'] ?>">
                                    Pilih Kursi & Booking <i class="bi bi-arrow-right-short ms-1 fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Interactive Seat Map Selection Modal -->
<div class="modal fade" id="seatModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content rounded-4 border-0">
            <div class="modal-header bg-dark text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-grid-3x3-gap-fill text-cyan me-2"></i> Pilih Kursi Speed Boat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-4">
                    <!-- Left: Speedboat Interactive Seat Map -->
                    <div class="col-lg-6">
                        <div class="text-center mb-3">
                            <h6 class="fw-bold mb-1" id="boatNameHeading">Speed Boat Layout</h6>
                            <div class="d-flex justify-content-center gap-3 small my-2">
                                <span><i class="bi bi-square-fill text-white border rounded me-1"></i> Tersedia</span>
                                <span><i class="bi bi-square-fill text-success me-1"></i> Dipilih</span>
                                <span><i class="bi bi-square-fill text-secondary opacity-50 me-1"></i> Terisi</span>
                            </div>
                        </div>

                        <div class="boat-layout-container">
                            <div class="boat-cockpit"><i class="bi bi-compass me-2"></i> ANJUNGAN KAPTEN / DEPAN</div>
                            <div id="seatGridContainer" class="py-2">
                                <!-- Dynamic Seat Grid Loaded Via AJAX -->
                                <div class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status"></div>
                                    <p class="mt-2 text-muted">Memuat denah kursi...</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right: Passenger Form & Order Summary -->
                    <div class="col-lg-6">
                        <div class="cititrans-card p-4 h-100">
                            <h6 class="fw-bold mb-3 border-bottom pb-2"><i class="bi bi-person-lines-fill text-primary me-2"></i> Data Penumpang</h6>
                            <form id="bookingForm">
                                <input type="hidden" id="formTripId">
                                
                                <div class="mb-3">
                                    <label class="form-label small fw-bold">Nama Pemesan / Kontak Utama</label>
                                    <input type="text" class="form-control fw-bold" id="customerName" placeholder="Contoh: Budi Santoso" required>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Nomor WhatsApp / HP</label>
                                        <input type="tel" class="form-control" id="customerPhone" placeholder="08123456789" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label small fw-bold">Email (E-Ticket)</label>
                                        <input type="email" class="form-control" id="customerEmail" placeholder="budi@gmail.com" required>
                                    </div>
                                </div>

                                <h6 class="fw-bold mt-4 mb-2 small text-uppercase text-muted">Detail Kursi Penumpang</h6>
                                <div id="passengerInputsContainer" class="mb-3">
                                    <div class="alert alert-info small rounded-3"><i class="bi bi-info-circle me-1"></i> Silakan klik kursi di denah sebelah kiri untuk menentukan nomor kursi.</div>
                                </div>

                                <div class="bg-light p-3 rounded-3 mb-4">
                                    <div class="d-flex justify-content-between mb-1 small text-muted">
                                        <span>Total Kursi</span>
                                        <span id="summarySeatCount">0 Kursi</span>
                                    </div>
                                    <hr class="my-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="fw-bold">Total Tagihan</span>
                                        <span class="fs-4 fw-extrabold text-primary" id="summaryTotal">Rp 0</span>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-cititrans btn-lg w-100 py-3" id="btnSubmitBooking" disabled>
                                    Lanjut ke Pembayaran Midtrans <i class="bi bi-credit-card-2-back-fill ms-2"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = new bootstrap.Modal(document.getElementById('seatModal'));
    let currentTrip = null;
    let selectedSeats = []; // [{ id, number, price }]

    document.querySelectorAll('.btn-select-trip').forEach(btn => {
        btn.addEventListener('click', function() {
            const tripId = this.dataset.tripId;
            currentTrip = {
                id: tripId,
                code: this.dataset.tripCode,
                boatName: this.dataset.boatName,
                price: parseFloat(this.dataset.price)
            };
            document.getElementById('formTripId').value = tripId;
            document.getElementById('boatNameHeading').innerText = currentTrip.boatName + ' (' + currentTrip.code + ')';
            
            selectedSeats = [];
            renderPassengerForm();
            fetchSeatMap(tripId);
            modal.show();
        });
    });

    function fetchSeatMap(tripId) {
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
                seatBtn.className = `seat-item ${s.status} ${s.seat_class}`;
                seatBtn.innerText = s.seat_number;
                seatBtn.dataset.seatId = s.id;
                seatBtn.dataset.seatNum = s.seat_number;

                if (s.status === 'available') {
                    seatBtn.addEventListener('click', () => toggleSeatSelection(s, seatBtn));
                }
                rowDiv.appendChild(seatBtn);

                if (idx === 1 && totalCols > 2) {
                    const aisle = document.createElement('div');
                    aisle.className = 'aisle-gap';
                    aisle.innerText = '';
                    rowDiv.appendChild(aisle);
                }
            });

            container.appendChild(rowDiv);
        }
    }

    function toggleSeatSelection(seat, element) {
        const existingIdx = selectedSeats.findIndex(item => item.id === seat.id);
        if (existingIdx >= 0) {
            selectedSeats.splice(existingIdx, 1);
            element.classList.remove('selected');
            element.classList.add('available');
        } else {
            selectedSeats.push({
                id: seat.id,
                number: seat.seat_number,
                price: currentTrip.price
            });
            element.classList.remove('available');
            element.classList.add('selected');
        }
        renderPassengerForm();
    }

    function renderPassengerForm() {
        const container = document.getElementById('passengerInputsContainer');
        const submitBtn = document.getElementById('btnSubmitBooking');
        
        if (selectedSeats.length === 0) {
            container.innerHTML = '<div class="alert alert-info small rounded-3"><i class="bi bi-info-circle me-1"></i> Silakan klik kursi di denah sebelah kiri untuk menentukan nomor kursi.</div>';
            submitBtn.disabled = true;
            updateSummary();
            return;
        }

        submitBtn.disabled = false;
        container.innerHTML = '';
        selectedSeats.forEach((s, idx) => {
            const div = document.createElement('div');
            div.className = 'p-3 bg-light rounded-3 mb-2 border';
            div.innerHTML = `
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <span class="badge bg-primary">Kursi ${s.number}</span>
                    <small class="fw-bold text-muted">Rp ${s.price.toLocaleString('id-ID')}</small>
                </div>
                <input type="text" class="form-control form-control-sm passenger-name-input" data-index="${idx}" placeholder="Nama Lengkap Penumpang ${idx + 1}" required>
            `;
            container.appendChild(div);
        });

        updateSummary();
    }

    function updateSummary() {
        const seatCount = selectedSeats.length;
        const subtotal = selectedSeats.reduce((acc, curr) => acc + curr.price, 0);

        document.getElementById('summarySeatCount').innerText = seatCount + ' Kursi';
        document.getElementById('summaryTotal').innerText = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    // Form Submit
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nameInputs = document.querySelectorAll('.passenger-name-input');
        let passengersPayload = [];

        selectedSeats.forEach((s, idx) => {
            const pName = nameInputs[idx] ? nameInputs[idx].value.trim() : document.getElementById('customerName').value.trim();
            passengersPayload.push({
                seat_id: s.id,
                seat_number: s.number,
                name: pName || 'Penumpang ' + (idx + 1),
                price: s.price
            });
        });

        const payload = {
            trip_id: currentTrip.id,
            customer_name: document.getElementById('customerName').value.trim(),
            customer_phone: document.getElementById('customerPhone').value.trim(),
            customer_email: document.getElementById('customerEmail').value.trim(),
            passengers: passengersPayload
        };

        const btn = document.getElementById('btnSubmitBooking');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Memproses Booking...';

        fetch('<?= base_url("booking/store") ?>', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        })
        .then(res => res.json())
        .then(res => {
            if (res.success) {
                window.location.href = res.redirect_url;
            } else {
                alert(res.message);
                btn.disabled = false;
                btn.innerHTML = 'Lanjut ke Pembayaran Midtrans <i class="bi bi-credit-card-2-back-fill ms-2"></i>';
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
