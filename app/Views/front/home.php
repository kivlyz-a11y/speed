<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<!-- BMK Hero Section with Right-Side Booking Card (Exact Match BMK Style) -->
<section class="pelni-hero-section">
    <div class="container-fluid px-lg-5">
        <div class="row g-4 align-items-center">
            
            <!-- LEFT COLUMN: Hero Artwork & Promotional Text -->
            <div class="col-lg-7">
                <div class="pe-lg-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="pelni-hero-badge">
                            <span>40kg</span>
                            <small style="font-size: 0.55rem; font-weight: 700;">BAGASI</small>
                        </div>
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold px-3 py-2 rounded-pill">
                                <i class="bi bi-shield-check me-1"></i> Standar Keselamatan Pelayaran ANDALAS
                            </span>
                        </div>
                    </div>

                    <h1 class="pelni-hero-title mb-4">
                        Nikmati <span>Perjalanan Penyeberangan Cepat</span>, Bebas Antre di Dermaga dengan E-Ticket QR Code Resmi <strong>SB ANDALAS</strong>
                    </h1>

                    <p class="lead text-secondary mb-4 max-w-600">
                        Layanan pemesanan tiket resmi speed boat online cepat, mudah, dan terpercaya dengan konfirmasi instan serta transaksi aman.
                    </p>

                    <div class="d-flex flex-wrap gap-3 align-items-center mb-4">
                        <a href="#popular-routes" class="pelni-btn-banner">LIHAT SELENGKAPNYA</a>
                        <div class="d-flex align-items-center gap-2 text-muted fw-bold small ms-2">
                            <i class="bi bi-clock-history text-primary fs-5"></i> Keberangkatan Tepat Waktu 99.8%
                        </div>
                    </div>

                    <div class="row g-3 mt-2 d-none d-md-flex">
                        <div class="col-4">
                            <div class="p-3 bg-white dark-bg-navy rounded border shadow-sm">
                                <div class="fw-extrabold text-primary fs-5"><i class="bi bi-lightning-charge-fill me-1"></i> Instant</div>
                                <div class="small text-muted">E-Ticket QR Instant</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-white dark-bg-navy rounded border shadow-sm">
                                <div class="fw-extrabold text-success fs-5"><i class="bi bi-shield-lock-fill me-1"></i> Midtrans</div>
                                <div class="small text-muted">Pembayaran Aman</div>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="p-3 bg-white dark-bg-navy rounded border shadow-sm">
                                <div class="fw-extrabold text-warning fs-5"><i class="bi bi-person-check-fill me-1"></i> Real-time</div>
                                <div class="small text-muted">Pilih Nomor Kursi</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- RIGHT COLUMN: Right-Side Ticket & Order Management Card -->
            <div class="col-lg-5">
                <div class="pelni-reservation-card">
                    <!-- Top Header Tabs: RESERVASI TIKET | KELOLA PESANAN -->
                    <div class="reservation-header-tabs">
                        <button type="button" class="tab-item-btn active" id="tabTiketBtn">
                            <i class="bi bi-ticket-perforated me-1"></i> RESERVASI TIKET
                        </button>
                        <button type="button" class="tab-item-btn" id="tabKelolaBtn" style="background: #E2E8F0; color: #334155;">
                            <i class="bi bi-receipt-cutoff me-1"></i> KELOLA PESANAN
                        </button>
                    </div>

                    <!-- PANEL 1: RESERVASI TIKET FORM -->
                    <div class="p-4" id="panelReservasiTiket">
                        <h5 class="fw-extrabold text-navy text-center mb-3" style="letter-spacing: 0.5px;">RESERVASI TIKET SPEED BOAT</h5>

                        <!-- Ticket Search Form -->
                        <form action="<?= base_url('search') ?>" method="GET" id="searchForm">
                            <input type="hidden" name="passengers" id="totalPassengersInput" value="<?= esc($search_pass ?? 1) ?>">

                            <div class="mb-2">
                                <label class="field-label-pelni">Tujuan Perjalanan</label>
                                
                                <!-- Dari (Origin) -->
                                <div class="mb-2">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted small fw-bold" style="width: 60px;">Dari</span>
                                        <select name="origin_id" id="originSelect" class="form-select pelni-select-field border-start-0" required>
                                            <?php foreach ($locations as $loc): ?>
                                                <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?> (<?= esc($loc['city']) ?>)</option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>

                                <!-- Central Swap Button (<->) -->
                                <div class="swap-button-wrapper">
                                    <button type="button" class="btn-swap-location" id="btnSwapLocations" title="Tukar Lokasi Asal & Tujuan">
                                        <i class="bi bi-arrow-down-up"></i>
                                    </button>
                                </div>

                                <!-- Ke (Destination) -->
                                <div class="mt-2 mb-3">
                                    <div class="input-group">
                                        <span class="input-group-text bg-light border-end-0 text-muted small fw-bold" style="width: 60px;">Ke</span>
                                        <select name="destination_id" id="destinationSelect" class="form-select pelni-select-field border-start-0" required>
                                            <option value="">-- Pilih Tujuan Kota/Pelabuhan --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Keberangkatan -->
                            <div class="mb-3">
                                <label class="field-label-pelni">Keberangkatan (14 hari kedepan)</label>
                                <input type="date" name="date" id="departureDateInput" class="pelni-input-field" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
                                <small class="text-muted opacity-75 d-block mt-1" style="font-size: 0.68rem;" id="dateNoteText">
                                    Pencarian ini akan menampilkan jadwal pada tanggal <strong id="dateDisplayLabel"><?= date('d/m/Y') ?></strong>
                                </small>
                            </div>

                            <!-- Jumlah Penumpang (Laki-laki & Perempuan) -->
                            <div class="mb-3">
                                <label class="field-label-pelni d-flex justify-content-between align-items-center">
                                    <span>Detail Jumlah Penumpang</span>
                                    <span class="badge bg-primary rounded-pill" id="totalPassengersBadge">Total: 1 Pnp</span>
                                </label>

                                <div class="row g-2">
                                    <!-- Laki-laki -->
                                    <div class="col-6">
                                        <div class="gender-box-pelni">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="small fw-bold text-primary"><i class="bi bi-gender-male me-1"></i> Laki-laki</div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <button type="button" class="counter-btn-pelni" id="btnMinusMale">&minus;</button>
                                                    <input type="number" name="male_passengers" id="malePassengersInput" class="counter-value-pelni" value="1" min="0" max="10" readonly>
                                                    <button type="button" class="counter-btn-pelni" id="btnPlusMale">&plus;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Perempuan -->
                                    <div class="col-6">
                                        <div class="gender-box-pelni">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div class="small fw-bold text-danger"><i class="bi bi-gender-female me-1"></i> Perempuan</div>
                                                <div class="d-flex align-items-center gap-1">
                                                    <button type="button" class="counter-btn-pelni" id="btnMinusFemale">&minus;</button>
                                                    <input type="number" name="female_passengers" id="femalePassengersInput" class="counter-value-pelni" value="0" min="0" max="10" readonly>
                                                    <button type="button" class="counter-btn-pelni" id="btnPlusFemale">&plus;</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Kelas Kapal -->
                            <div class="mb-4">
                                <label class="field-label-pelni">Kelas Kapal Speed Boat</label>
                                <select name="ship_class" class="pelni-select-field">
                                    <option value="all">Pilih Semua Kelas Kapal</option>
                                    <option value="executive">Kelas Eksekutif / VIP</option>
                                    <option value="standard">Kelas Standar</option>
                                </select>
                            </div>

                            <!-- Submit Button: CARI PELAYARAN -->
                            <button type="submit" class="btn-cari-pelayaran">
                                <i class="bi bi-search me-2"></i> CARI PELAYARAN ANDALAS
                            </button>
                        </form>
                    </div>

                    <!-- PANEL 2: KELOLA PESANAN (MANAGE BOOKING) -->
                    <div class="p-4 d-none" id="panelKelolaPesanan">
                        <h5 class="fw-extrabold text-navy text-center mb-3" style="letter-spacing: 0.5px;">KELOLA & CEK PESANAN TIKET</h5>
                        <p class="text-secondary small text-center mb-4">Masukkan Kode Booking atau E-Ticket Anda untuk mengecek status pesanan, unduh PDF tiket, atau lakukan reschedule/refund.</p>

                        <form action="<?= base_url('checkout/mock-pay/') ?>" method="GET" id="manageOrderForm">
                            <div class="mb-3">
                                <label class="field-label-pelni">Kode Booking / E-Ticket</label>
                                <input type="text" id="manageBookingCode" class="pelni-input-field text-uppercase fw-bold" placeholder="Contoh: ANDALAS-2026-XXXX" required>
                            </div>

                            <div class="mb-4">
                                <label class="field-label-pelni">Nomor WhatsApp / Kontak Pembeli</label>
                                <input type="tel" class="pelni-input-field" placeholder="08123456789">
                            </div>

                            <button type="submit" class="btn-cari-pelayaran bg-primary">
                                <i class="bi bi-receipt me-2"></i> CEK STATUS & MANAJEMEN TIKET
                            </button>
                        </form>

                        <div class="alert alert-info border-0 rounded-3 mt-4 small">
                            <i class="bi bi-info-circle-fill me-1"></i> Perlu bantuan refund atau cetak ulang e-ticket? Hubungi Contact Center ANDALAS di <strong>(021) 162</strong>.
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- BMK Statistics Counter Section -->
<section class="py-5 bg-white bg-opacity-75 border-top border-bottom">
    <div class="container">
        <div class="row g-4">
            <!-- Counter Laki-Laki -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card stat-male h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-2 text-primary"><i class="bi bi-gender-male"></i></div>
                        <div>
                            <div class="fs-4 fw-extrabold text-primary"><?= number_format($maleCount ?? 15420, 0, ',', '.') ?>+</div>
                            <div class="small fw-bold text-secondary">Penumpang Laki-laki</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Counter Perempuan -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card stat-female h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-2 text-danger"><i class="bi bi-gender-female"></i></div>
                        <div>
                            <div class="fs-4 fw-extrabold text-danger"><?= number_format($femaleCount ?? 14880, 0, ',', '.') ?>+</div>
                            <div class="small fw-bold text-secondary">Penumpang Perempuan</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Counter Total Penyeberangan -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card stat-total h-100">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-2 text-info"><i class="bi bi-ticket-perforated-fill"></i></div>
                        <div>
                            <div class="fs-4 fw-extrabold text-info"><?= number_format($totalCount ?? 30300, 0, ',', '.') ?>+</div>
                            <div class="small fw-bold text-secondary">Tiket Sukses Terbit</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Counter Ketepatan Waktu -->
            <div class="col-md-3 col-sm-6">
                <div class="stat-card h-100" style="border-left-color: #2EC4B6;">
                    <div class="d-flex align-items-center gap-3">
                        <div class="fs-2 text-success"><i class="bi bi-shield-check"></i></div>
                        <div>
                            <div class="fs-4 fw-extrabold text-success">99.8%</div>
                            <div class="small fw-bold text-secondary">Ketepatan & Safety</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Dynamic BMK Rute Populer Section -->
<section id="popular-routes" class="py-5 bg-light">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold mb-2"><i class="bi bi-compass me-1"></i> RUTE JADWAL PELAYARAN</span>
            <h3 class="fw-bold text-navy">Jadwal Penyeberangan Speed Boat ANDALAS Aktif</h3>
            <p class="text-secondary">Pilihan rute penyeberangan cepat resmi ANDALAS yang beroperasi secara rutin setiap hari</p>
        </div>

        <div class="row g-4">
            <?php if (empty($popularRoutes)): ?>
                <div class="col-12 text-center py-4">
                    <div class="pelni-card p-4">
                        <p class="text-muted mb-0">Belum ada rute aktif yang tersedia di database.</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($popularRoutes as $pr): ?>
                    <div class="col-md-6">
                        <div class="pelni-card overflow-hidden h-100">
                            <div class="position-relative">
                                <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=500&auto=format&fit=crop&q=80" class="card-img-top" alt="<?= esc($pr['origin_name']) ?>" style="height: 180px; object-fit: cover;">
                                <div class="position-absolute top-0 end-0 bg-warning text-dark font-extrabold px-3 py-1 m-3 rounded-pill small shadow">
                                    <i class="bi bi-lightning-fill me-1"></i> Speed Boat ANDALAS
                                </div>
                            </div>
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary rounded-pill"><i class="bi bi-clock me-1"></i> <?= esc($pr['estimated_duration_minutes']) ?> Menit</span>
                                    <span class="fw-extrabold text-primary fs-4">Rp <?= number_format($pr['base_price'], 0, ',', '.') ?></span>
                                </div>
                                <h4 class="fw-bold mb-2 text-navy"><?= esc($pr['origin_name']) ?> &rarr; <?= esc($pr['destination_name']) ?></h4>
                                <p class="text-secondary small mb-3">Penyeberangan cepat <?= esc($pr['origin_city']) ?> menuju <?= esc($pr['destination_city']) ?> dengan jarak tempuh laut <?= esc($pr['distance_nautical_miles']) ?> NM.</p>
                                <a href="<?= base_url('search?origin_id=' . $pr['origin_location_id'] . '&destination_id=' . $pr['destination_location_id']) ?>" class="btn btn-outline-primary w-100 rounded-pill fw-bold">Pesan Tiket Pelayaran Ini <i class="bi bi-arrow-right-short ms-1 fs-5"></i></a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- BMK FAQ Accordion -->
<section id="faq" class="py-5">
    <div class="container max-w-800">
        <div class="text-center mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold mb-2">BANTUAN & INFORMASI</span>
            <h3 class="fw-bold text-navy">Pertanyaan Sering Diajukan (FAQ)</h3>
        </div>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item border-0 mb-3 pelni-card">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        <i class="bi bi-qr-code me-2 text-primary"></i> Bagaimana alur pemesanan tiket online dan check-in di dermaga ANDALAS?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Cukup cari rute pelayaran pada form di sebelah kanan, tentukan jumlah penumpang Laki-laki dan Perempuan, pilih nomor kursi favorit Anda di denah kapal, lalu selesaikan pembayaran via Midtrans. E-Ticket QR Code akan otomatis terbit untuk di-scan petugas boarding ANDALAS.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 mb-3 pelni-card">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        <i class="bi bi-arrow-counterclockwise me-2 text-primary"></i> Apakah pemesanan dapat dibatalkan (Refund) atau diubah jadwalnya (Reschedule)?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Ya! Pengajuan refund atau ubah jadwal (reschedule) dapat dilakukan secara mandiri via tab Kelola Pesanan selambat-lambatnya 2 jam sebelum waktu keberangkatan kapal.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 mb-3 pelni-card">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq3">
                        <i class="bi bi-headset me-2 text-primary"></i> Siapa yang bisa dihubungi jika terjadi kendala pembayaran atau E-Ticket?
                    </button>
                </h2>
                <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Anda dapat menghubungi Contact Center ANDALAS melalui <strong>(021) 162</strong> atau WhatsApp CS di <strong>0811-162-1-162</strong> (layanan 24 jam).
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const originSelect = document.getElementById('originSelect');
    const destSelect = document.getElementById('destinationSelect');
    const btnSwap = document.getElementById('btnSwapLocations');
    const maleInput = document.getElementById('malePassengersInput');
    const femaleInput = document.getElementById('femalePassengersInput');
    const totalPassengersInput = document.getElementById('totalPassengersInput');
    const totalBadge = document.getElementById('totalPassengersBadge');
    const departureDateInput = document.getElementById('departureDateInput');
    const dateDisplayLabel = document.getElementById('dateDisplayLabel');

    // Tab Switch: Reservasi Tiket vs Kelola Pesanan
    const tabTiketBtn = document.getElementById('tabTiketBtn');
    const tabKelolaBtn = document.getElementById('tabKelolaBtn');
    const panelReservasi = document.getElementById('panelReservasiTiket');
    const panelKelola = document.getElementById('panelKelolaPesanan');

    if (tabTiketBtn && tabKelolaBtn && panelReservasi && panelKelola) {
        tabTiketBtn.addEventListener('click', () => {
            tabTiketBtn.classList.add('active');
            tabTiketBtn.style.background = '';
            tabTiketBtn.style.color = '';
            
            tabKelolaBtn.classList.remove('active');
            tabKelolaBtn.style.background = '#E2E8F0';
            tabKelolaBtn.style.color = '#334155';

            panelReservasi.classList.remove('d-none');
            panelKelola.classList.add('d-none');
        });

        tabKelolaBtn.addEventListener('click', () => {
            tabKelolaBtn.classList.add('active');
            tabKelolaBtn.style.background = '#FFFFFF';
            tabKelolaBtn.style.color = 'var(--pelni-navy)';

            tabTiketBtn.classList.remove('active');
            tabTiketBtn.style.background = '#E2E8F0';
            tabTiketBtn.style.color = '#334155';

            panelKelola.classList.remove('d-none');
            panelReservasi.classList.add('d-none');
        });
    }

    // Manage order submit redirect
    const manageForm = document.getElementById('manageOrderForm');
    if (manageForm) {
        manageForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const code = document.getElementById('manageBookingCode').value.trim();
            if (code) {
                window.location.href = '<?= base_url("payment/checkout/") ?>' + encodeURIComponent(code);
            }
        });
    }

    // Update total passengers calculation
    function updateTotalPassengers() {
        let male = parseInt(maleInput.value) || 0;
        let female = parseInt(femaleInput.value) || 0;
        let total = male + female;

        if (total < 1) {
            male = 1;
            maleInput.value = 1;
            total = 1;
        }

        totalPassengersInput.value = total;
        totalBadge.innerText = `Total: ${total} Pnp`;
    }

    // Counter buttons logic
    document.getElementById('btnMinusMale').addEventListener('click', () => {
        let val = parseInt(maleInput.value) || 0;
        if (val > 0) {
            maleInput.value = val - 1;
            updateTotalPassengers();
        }
    });

    document.getElementById('btnPlusMale').addEventListener('click', () => {
        let val = parseInt(maleInput.value) || 0;
        if (val < 10) {
            maleInput.value = val + 1;
            updateTotalPassengers();
        }
    });

    document.getElementById('btnMinusFemale').addEventListener('click', () => {
        let val = parseInt(femaleInput.value) || 0;
        if (val > 0) {
            femaleInput.value = val - 1;
            updateTotalPassengers();
        }
    });

    document.getElementById('btnPlusFemale').addEventListener('click', () => {
        let val = parseInt(femaleInput.value) || 0;
        if (val < 10) {
            femaleInput.value = val + 1;
            updateTotalPassengers();
        }
    });

    // Date change label update
    if (departureDateInput) {
        departureDateInput.addEventListener('change', function() {
            if (this.value) {
                const parts = this.value.split('-');
                dateDisplayLabel.innerText = `${parts[2]}/${parts[1]}/${parts[0]}`;
            }
        });
    }

    // Dynamic destination dropdown based on origin
    function updateDestinations(originId, targetDestId = null) {
        destSelect.innerHTML = '<option value="">-- Memuat Tujuan... --</option>';

        fetch('<?= base_url("api/routes/destinations/") ?>' + originId)
            .then(res => res.json())
            .then(data => {
                destSelect.innerHTML = '';
                if (data.success && data.destinations.length > 0) {
                    data.destinations.forEach(d => {
                        const opt = document.createElement('option');
                        opt.value = d.id;
                        opt.innerText = d.label || `${d.name} (${d.city})`;
                        if (targetDestId && parseInt(d.id) === parseInt(targetDestId)) {
                            opt.selected = true;
                        }
                        destSelect.appendChild(opt);
                    });
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.innerText = '-- Tidak ada rute aktif --';
                    destSelect.appendChild(opt);
                }
            })
            .catch(err => {
                console.error('Error fetching destinations:', err);
            });
    }

    if (originSelect && originSelect.value) {
        updateDestinations(originSelect.value);
    }

    originSelect.addEventListener('change', function() {
        updateDestinations(this.value);
    });

    // Circular Swap Locations Button (<->)
    if (btnSwap) {
        btnSwap.addEventListener('click', () => {
            const currentOrigin = originSelect.value;
            const currentDest = destSelect.value;

            if (!currentDest) return;

            // Find if destination option exists in origin select
            let originOptionExists = Array.from(originSelect.options).some(opt => opt.value == currentDest);
            if (originOptionExists) {
                originSelect.value = currentDest;
                updateDestinations(currentDest, currentOrigin);
            }
        });
    }

    updateTotalPassengers();
});
</script>
<?= $this->endSection() ?>
