<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <div class="cititrans-card p-4 p-md-5 shadow-sm text-center">
                <div class="d-inline-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle p-3 mb-3" style="width: 70px; height: 70px;">
                    <i class="bi bi-ticket-perforated-fill display-5"></i>
                </div>
                
                <h3 class="fw-bold mb-1 text-dark">Kelola Pesanan & E-Ticket</h3>
                <p class="text-secondary small mb-4">Masukkan Kode Booking dan Nomor WhatsApp/Email untuk melihat tiket, memilih kursi, atau mengunduh E-Ticket PDF.</p>

                <form action="<?= base_url('booking/manage/search') ?>" method="POST">
                    <div class="mb-3 text-start">
                        <label class="form-label small fw-bold text-dark">Kode Booking / Pesanan</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-qr-code"></i></span>
                            <input type="text" name="booking_code" class="form-control text-uppercase fw-bold" placeholder="Contoh: CITI-20260816-ABCD" required>
                        </div>
                    </div>

                    <div class="mb-4 text-start">
                        <label class="form-label small fw-bold text-dark">Email atau Nomor WhatsApp (Opsional)</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                            <input type="text" name="contact" class="form-control" placeholder="Contoh: 08123456789 atau email@domain.com">
                        </div>
                        <div class="form-text small text-muted">Digunakan untuk verifikasi keamanan pesanan Anda.</div>
                    </div>

                    <button type="submit" class="btn btn-cititrans btn-lg w-100 py-3 font-outfit shadow">
                        <i class="bi bi-search me-2"></i> Cari Pesanan Saya
                    </button>
                </form>

                <div class="mt-4 pt-3 border-top text-muted small">
                    <i class="bi bi-info-circle me-1 text-info"></i> Lupa kode booking? Silakan periksa pesan WhatsApp / E-Mail konfirmasi pembayaran Anda.
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
