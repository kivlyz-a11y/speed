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
                                <button type="button" class="btn btn-cititrans px-4 btn-select-trip" 
                                    data-trip-id="<?= $t['id'] ?>" 
                                    data-trip-code="<?= $t['trip_code'] ?>" 
                                    data-boat-name="<?= esc($t['boat_name']) ?>" 
                                    data-origin="<?= esc($t['origin_name']) ?>" 
                                    data-dest="<?= esc($t['destination_name']) ?>" 
                                    data-time="<?= date('H:i', strtotime($t['departure_time'])) ?>" 
                                    data-price="<?= $t['adult_price'] ?>"
                                    data-available="<?= $t['available_seats'] ?>">
                                    Pesan Tiket <i class="bi bi-arrow-right-short ms-1 fs-5"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Fast Booking & Customer Details Modal -->
<div class="modal fade" id="seatModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <div class="modal-header bg-primary text-white rounded-top-4">
                <h5 class="modal-title fw-bold" id="modalTitle"><i class="bi bi-ticket-perforated-fill me-2"></i> Pemesanan Tiket Speed Boat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="bookingForm">
                <div class="modal-body p-4">
                    <input type="hidden" id="formTripId">
                    
                    <div class="row g-4">
                        <!-- Left: Trip Summary & Seat Selection Rule Notice -->
                        <div class="col-md-5">
                            <div class="bg-light p-3 rounded-4 mb-3 border">
                                <span class="badge bg-primary rounded-pill mb-2">DETAIL PERJALANAN</span>
                                <h6 class="fw-bold mb-1 text-dark" id="summaryRoute">Bulungan &rarr; Tarakan</h6>
                                <p class="small text-muted mb-2"><i class="bi bi-water text-cyan me-1"></i> <span id="summaryBoat">Ocean Express 01</span></p>
                                <div class="d-flex justify-content-between small border-top pt-2 mt-2">
                                    <span class="text-muted">Jam Keberangkatan</span>
                                    <strong class="text-primary" id="summaryTime">07:30 WITA</strong>
                                </div>
                                <div class="d-flex justify-content-between small mt-1">
                                    <span class="text-muted">Harga / Penumpang</span>
                                    <strong class="text-success" id="summaryPricePerPnp">Rp 150.000</strong>
                                </div>
                            </div>

                            <!-- Post Payment Seat Selection Info Card -->
                            <div class="alert alert-info border-0 shadow-sm rounded-4 p-3 mb-0">
                                <div class="d-flex align-items-start">
                                    <i class="bi bi-info-circle-fill fs-4 text-info me-2 mt-1"></i>
                                    <div>
                                        <h6 class="fw-bold mb-1 text-dark">Pilih Kursi Setelah Pembayaran</h6>
                                        <p class="small mb-0 text-secondary" style="font-size: 0.85rem; line-height: 1.4;">
                                            Penentuan nomor kursi dilakukan <strong>setelah pembayaran selesai</strong> melalui menu <strong>Kelola Pesanan</strong>.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Customer Info, Passenger Count & Passengers Input -->
                        <div class="col-md-7">
                            <h6 class="fw-bold mb-3"><i class="bi bi-person-vcard text-primary me-2"></i> Data Pemesan (Kontak Utama)</h6>
                            
                            <div class="row g-2 mb-3">
                                <div class="col-md-7">
                                    <label class="form-label small fw-bold">Nama Pemesan / Pemegang Tiket</label>
                                    <input type="text" class="form-control" id="customerName" placeholder="Contoh: Budi Santoso" required>
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label small fw-bold">Jenis Kelamin</label>
                                    <select class="form-select" id="customerGender" required>
                                        <option value="male">Laki-laki</option>
                                        <option value="female">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row g-2 mb-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">NIK Pemesan (Opsional)</label>
                                    <input type="text" class="form-control" id="customerNik" placeholder="657101xxxxxx (Opsional)">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Nomor WhatsApp / HP</label>
                                    <input type="tel" class="form-control" id="customerPhone" placeholder="08123456789" required>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Email (E-Ticket)</label>
                                <input type="email" class="form-control" id="customerEmail" placeholder="budi@gmail.com" required>
                            </div>

                            <!-- Selector Jumlah Penumpang -->
                            <div class="p-3 bg-light rounded-3 mb-3 border">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <label class="form-label small fw-bold mb-0 text-dark">
                                        <i class="bi bi-people-fill text-primary me-1"></i> Jumlah Penumpang (Tiket)
                                    </label>
                                    <select id="passengerCountSelect" class="form-select form-select-sm w-auto fw-bold text-primary border-primary">
                                        <!-- Options populated dynamically -->
                                    </select>
                                </div>
                                <small class="text-muted d-block" style="font-size: 0.78rem;">Pilih berapa banyak tiket yang ingin Anda pesan sekaligus.</small>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-4 mb-2">
                                <h6 class="fw-bold small text-uppercase text-muted mb-0"><i class="bi bi-card-checklist me-1"></i> Detail Data Penumpang</h6>
                            </div>

                            <!-- Checkbox Samakan Penumpang 1 dengan Data Pemesan -->
                            <div class="form-check form-switch p-3 bg-light rounded-3 border mb-3 ms-0">
                                <input class="form-check-input ms-0 me-2" type="checkbox" id="copyCustomerToPnp1">
                                <label class="form-check-label fw-bold small text-primary" for="copyCustomerToPnp1">
                                    <i class="bi bi-person-check-fill me-1"></i> Penumpang 1 Sama Dengan Data Pemesan
                                </label>
                            </div>

                            <div id="passengerInputsContainer" class="mb-3">
                                <!-- Passenger Name, Gender & NIK Inputs -->
                            </div>

                            <div class="bg-light p-3 rounded-3 mb-3 border">
                                <div class="d-flex justify-content-between mb-1 small text-muted">
                                    <span>Total Penumpang</span>
                                    <span id="summarySeatCount">1 Orang</span>
                                </div>
                                <hr class="my-2">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="fw-bold">Total Tagihan</span>
                                    <span class="fs-4 fw-extrabold text-primary" id="summaryTotal">Rp 0</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer bg-light rounded-bottom-4">
                    <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-cititrans fw-bold px-4 rounded-3" id="btnSubmitBooking">
                        Lanjut ke Pembayaran Midtrans <i class="bi bi-credit-card-2-back-fill ms-2"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modal = new bootstrap.Modal(document.getElementById('seatModal'));
    let currentTrip = null;
    let selectedPassengerCount = <?= (int) ($search_pass ?? 1) ?>;

    document.querySelectorAll('.btn-select-trip').forEach(btn => {
        btn.addEventListener('click', function() {
            const tripId = this.dataset.tripId;
            const available = parseInt(this.dataset.available) || 10;

            currentTrip = {
                id: tripId,
                code: this.dataset.tripCode,
                boatName: this.dataset.boatName,
                origin: this.dataset.origin,
                dest: this.dataset.dest,
                time: this.dataset.time,
                price: parseFloat(this.dataset.price),
                available: available
            };

            document.getElementById('formTripId').value = tripId;
            document.getElementById('summaryRoute').innerText = currentTrip.origin + ' → ' + currentTrip.dest;
            document.getElementById('summaryBoat').innerText = currentTrip.boatName + ' (' + currentTrip.code + ')';
            document.getElementById('summaryTime').innerText = currentTrip.time + ' WITA';
            document.getElementById('summaryPricePerPnp').innerText = 'Rp ' + currentTrip.price.toLocaleString('id-ID');

            populatePassengerCountSelect(available);
            renderPassengerInputs(selectedPassengerCount);
            modal.show();
        });
    });

    function populatePassengerCountSelect(availableSeats) {
        const select = document.getElementById('passengerCountSelect');
        select.innerHTML = '';
        const maxOptions = Math.min(availableSeats, 10); // max 10 passengers per order

        for (let i = 1; i <= maxOptions; i++) {
            const opt = document.createElement('option');
            opt.value = i;
            opt.innerText = i + ' Penumpang';
            if (i === selectedPassengerCount) opt.selected = true;
            select.appendChild(opt);
        }
    }

    document.getElementById('passengerCountSelect').addEventListener('change', function() {
        selectedPassengerCount = parseInt(this.value);
        renderPassengerInputs(selectedPassengerCount);
    });

    function renderPassengerInputs(count) {
        const container = document.getElementById('passengerInputsContainer');
        
        // Preserve existing values if any
        let existingNames = [];
        let existingGenders = [];
        let existingNiks = [];
        container.querySelectorAll('.passenger-name-input').forEach(inp => existingNames.push(inp.value));
        container.querySelectorAll('.passenger-gender-select').forEach(inp => existingGenders.push(inp.value));
        container.querySelectorAll('.passenger-nik-input').forEach(inp => existingNiks.push(inp.value));

        container.innerHTML = '';

        for (let i = 1; i <= count; i++) {
            const valName = existingNames[i - 1] || '';
            const valGender = existingGenders[i - 1] || 'male';
            const valNik = existingNiks[i - 1] || '';

            const card = document.createElement('div');
            card.className = 'p-3 bg-light rounded-3 mb-2 border';
            card.innerHTML = `
                <div class="fw-bold small text-primary mb-2 d-flex justify-content-between">
                    <span><i class="bi bi-person-fill me-1"></i> Penumpang ${i}</span>
                    <span class="text-muted fw-normal" style="font-size: 0.8rem;">Rp ${currentTrip ? currentTrip.price.toLocaleString('id-ID') : '0'}</span>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-7">
                        <input type="text" class="form-control form-control-sm passenger-name-input" data-index="${i}" value="${valName}" placeholder="Nama Lengkap Penumpang ${i}" required>
                    </div>
                    <div class="col-md-5">
                        <select class="form-select form-select-sm passenger-gender-select" data-index="${i}">
                            <option value="male" ${valGender === 'male' ? 'selected' : ''}>Laki-laki</option>
                            <option value="female" ${valGender === 'female' ? 'selected' : ''}>Perempuan</option>
                        </select>
                    </div>
                </div>
                <div>
                    <input type="text" class="form-control form-control-sm passenger-nik-input" data-index="${i}" value="${valNik}" placeholder="NIK / No. KTP / Paspor (Opsional)">
                </div>
            `;
            container.appendChild(card);
        }

        const totalCost = (currentTrip ? currentTrip.price : 0) * count;
        document.getElementById('summarySeatCount').innerText = count + ' Orang';
        document.getElementById('summaryTotal').innerText = 'Rp ' + totalCost.toLocaleString('id-ID');

        // Re-apply sync if checkbox is checked
        syncCustomerToPnp1();
    }

    function syncCustomerToPnp1() {
        const copyCheck = document.getElementById('copyCustomerToPnp1');
        if (!copyCheck || !copyCheck.checked) return;

        const pnp1Name = document.querySelector('.passenger-name-input[data-index="1"]');
        const pnp1Gender = document.querySelector('.passenger-gender-select[data-index="1"]');
        const pnp1Nik = document.querySelector('.passenger-nik-input[data-index="1"]');

        if (pnp1Name) pnp1Name.value = document.getElementById('customerName').value;
        if (pnp1Gender) pnp1Gender.value = document.getElementById('customerGender').value;
        if (pnp1Nik) pnp1Nik.value = document.getElementById('customerNik').value;
    }

    document.getElementById('copyCustomerToPnp1').addEventListener('change', function() {
        if (this.checked) {
            syncCustomerToPnp1();
        }
    });

    ['customerName', 'customerGender', 'customerNik'].forEach(id => {
        const el = document.getElementById(id);
        if (el) {
            el.addEventListener('input', syncCustomerToPnp1);
            el.addEventListener('change', syncCustomerToPnp1);
        }
    });

    // Form Submit Handler
    document.getElementById('bookingForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const nameInputs = document.querySelectorAll('.passenger-name-input');
        const genderSelects = document.querySelectorAll('.passenger-gender-select');
        const nikInputs = document.querySelectorAll('.passenger-nik-input');
        let passengersPayload = [];

        nameInputs.forEach((input, idx) => {
            const pName = input.value.trim() || 'Penumpang ' + (idx + 1);
            const pGender = genderSelects[idx] ? genderSelects[idx].value : 'male';
            const pNik = nikInputs[idx] ? nikInputs[idx].value.trim() : '';
            
            passengersPayload.push({
                seat_id: 0,
                seat_number: 'Belum Dipilih',
                name: pName,
                gender: pGender,
                nik: pNik,
                price: currentTrip.price
            });
        });

        const payload = {
            trip_id: currentTrip.id,
            customer_name: document.getElementById('customerName').value.trim(),
            customer_gender: document.getElementById('customerGender').value,
            customer_nik: document.getElementById('customerNik').value.trim(),
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
        })
        .catch(err => {
            alert('Terjadi kesalahan jaringan.');
            btn.disabled = false;
            btn.innerHTML = 'Lanjut ke Pembayaran Midtrans <i class="bi bi-credit-card-2-back-fill ms-2"></i>';
        });
    });
});
</script>
<?= $this->endSection() ?>
