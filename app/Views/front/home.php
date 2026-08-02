<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<!-- Hero Banner -->
<section class="hero-banner text-center text-lg-start">
    <div class="container">
        <div class="row align-items-center py-4">
            <div class="col-lg-7">
                <span class="badge bg-info bg-opacity-20 text-cyan rounded-pill px-3 py-2 fw-semibold mb-3">
                    <i class="bi bi-shield-check me-1"></i> Pengalaman Pemesanan Berstandar Cititrans
                </span>
                <h1 class="display-4 fw-extrabold mb-3">Perjalanan Speed Boat <br><span class="text-cyan">Cepat, Nyaman & Aman</span></h1>
                <p class="lead text-light opacity-75 mb-4">Pesan tiket penyeberangan speed boat secara instan. Bebas antre, pilih kursi favorit, e-ticket QR code otomatis.</p>
                <div class="d-flex flex-wrap gap-3">
                    <div class="d-flex align-items-center text-white"><i class="bi bi-lightning-charge-fill text-warning me-2 fs-5"></i> Instant E-Ticket</div>
                    <div class="d-flex align-items-center text-white"><i class="bi bi-qr-code text-cyan me-2 fs-5"></i> Quick QR Check-In</div>
                    <div class="d-flex align-items-center text-white"><i class="bi bi-shield-lock-fill text-success me-2 fs-5"></i> Midtrans Secure Payment</div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600&auto=format&fit=crop&q=80" alt="Speed Boat Cititrans" class="img-fluid rounded-4 shadow-lg border border-3 border-white border-opacity-20" style="transform: rotate(2deg);">
            </div>
        </div>
    </div>
</section>

<!-- Floating Cititrans Search Box -->
<div class="container search-card-container">
    <div class="cititrans-card p-4 p-md-4">
        <form action="<?= base_url('search') ?>" method="GET" id="searchForm">
            <div class="row g-3 align-items-end">
                <div class="col-md-3">
                    <div class="search-input-group">
                        <label><i class="bi bi-geo-alt-fill text-primary me-1"></i> Kota / Lokasi Asal</label>
                        <select name="origin_id" id="originSelect" class="form-select border-0 bg-transparent fw-bold" required>
                            <?php foreach ($locations as $loc): ?>
                                <option value="<?= $loc['id'] ?>"><?= esc($loc['name']) ?> (<?= esc($loc['city']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="search-input-group">
                        <label><i class="bi bi-pin-map-fill text-danger me-1"></i> Kota / Lokasi Tujuan</label>
                        <select name="destination_id" id="destinationSelect" class="form-select border-0 bg-transparent fw-bold" required>
                            <option value="">-- Memuat Tujuan... --</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="search-input-group">
                        <label><i class="bi bi-calendar-date-fill text-warning me-1"></i> Tanggal Keberangkatan</label>
                        <input type="date" name="date" class="form-control border-0 bg-transparent fw-bold" value="<?= date('Y-m-d') ?>" min="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="search-input-group">
                        <label><i class="bi bi-people-fill text-info me-1"></i> Jumlah Penumpang</label>
                        <select name="passengers" class="form-select border-0 bg-transparent fw-bold">
                            <option value="1">1 Penumpang</option>
                            <option value="2">2 Penumpang</option>
                            <option value="3">3 Penumpang</option>
                            <option value="4">4 Penumpang</option>
                            <option value="5">5+ Penumpang</option>
                        </select>
                    </div>
                </div>

                <div class="col-12 mt-4 text-center">
                    <button type="submit" class="btn btn-cititrans btn-lg w-100 py-3 shadow-lg">
                        <i class="bi bi-search me-2"></i> Cari Jadwal Speed Boat Cepat
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Dynamic Rute Populer Section from Database -->
<section class="py-5 bg-light bg-opacity-50 mt-5">
    <div class="container">
        <div class="text-center max-w-700 mx-auto mb-5">
            <span class="badge bg-primary bg-opacity-10 text-primary fw-bold mb-2">DESTINASI TERSEDIA</span>
            <h3 class="fw-bold">Rute Speed Boat Aktif</h3>
            <p class="text-secondary">Pilihan rute penyeberangan aktif yang terhubung di sistem database</p>
        </div>

        <div class="row g-4">
            <?php if (empty($popularRoutes)): ?>
                <div class="col-12 text-center py-4">
                    <p class="text-muted">Belum ada rute aktif di database.</p>
                </div>
            <?php else: ?>
                <?php foreach ($popularRoutes as $pr): ?>
                    <div class="col-md-6">
                        <div class="cititrans-card overflow-hidden h-100">
                            <img src="https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=500&auto=format&fit=crop&q=80" class="card-img-top" alt="<?= esc($pr['origin_name']) ?>" style="height: 200px; object-fit: cover;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-primary rounded-pill"><?= esc($pr['estimated_duration_minutes']) ?> Menit</span>
                                    <span class="fw-extrabold text-primary fs-5">Rp <?= number_format($pr['base_price'], 0, ',', '.') ?></span>
                                </div>
                                <h4 class="fw-bold mb-2"><?= esc($pr['origin_name']) ?> &rarr; <?= esc($pr['destination_name']) ?></h4>
                                <p class="text-secondary small mb-3">Penyeberangan cepat <?= esc($pr['origin_city']) ?> ke <?= esc($pr['destination_city']) ?> dengan jarak <?= esc($pr['distance_nautical_miles']) ?> NM.</p>
                                <a href="<?= base_url('search?origin_id=' . $pr['origin_location_id'] . '&destination_id=' . $pr['destination_location_id']) ?>" class="btn btn-outline-primary w-100 rounded-pill fw-semibold">Pesan Tiket Ini</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- FAQ Accordion -->
<section id="faq" class="py-5">
    <div class="container max-w-800">
        <div class="text-center mb-5">
            <h3 class="fw-bold">Pertanyaan Sering Diajukan (FAQ)</h3>
        </div>
        <div class="accordion" id="faqAccordion">
            <div class="accordion-item border-0 mb-3 cititrans-card">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                        Bagaimana alur pemesanan dan check-in di dermaga?
                    </button>
                </h2>
                <div id="faq1" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Cukup cari jadwal, pilih nomor kursi favorit Anda, masukkan nama & nomor kontak, lalu selesaikan pembayaran via Midtrans (QRIS/VA). E-Ticket QR Code akan otomatis terbit. Di dermaga, tunjukkan QR Code pada tiket untuk di-scan oleh petugas boarding.
                    </div>
                </div>
            </div>

            <div class="accordion-item border-0 mb-3 cititrans-card">
                <h2 class="accordion-header">
                    <button class="accordion-button fw-bold collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq2">
                        Apakah bisa melakukan Refund atau Reschedule?
                    </button>
                </h2>
                <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                    <div class="accordion-body text-secondary">
                        Bisa! Pengajuan refund atau pindah jadwal (reschedule) dapat dilakukan secara mandiri dari halaman E-Ticket Anda selambat-lambatnya 2 jam sebelum waktu keberangkatan.
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

    function updateDestinations(originId) {
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

    // Trigger on initial page load
    if (originSelect && originSelect.value) {
        updateDestinations(originSelect.value);
    }

    // Trigger on origin change
    originSelect.addEventListener('change', function() {
        updateDestinations(this.value);
    });
});
</script>
<?= $this->endSection() ?>
