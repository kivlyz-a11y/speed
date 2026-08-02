<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="cititrans-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1"><i class="bi bi-person-plus-fill text-primary me-2"></i> Pendaftaran Member</h4>
                    <p class="text-secondary small">Nikmati kemudahan pemesanan & voucher diskon member</p>
                </div>

                <form action="<?= base_url('register') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" placeholder="Budi Santoso" required>
                    </div>

                    <div class="row g-2 mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email</label>
                            <input type="email" name="email" class="form-control" placeholder="budi@gmail.com" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">No. Telepon / WhatsApp</label>
                            <input type="tel" name="phone" class="form-control" placeholder="08123456789" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required minlength="6">
                    </div>

                    <button type="submit" class="btn btn-cititrans btn-lg w-100 py-3 mb-3">
                        Daftar Akun Member <i class="bi bi-check-circle-fill ms-1"></i>
                    </button>

                    <div class="text-center small">
                        Sudah punya akun? <a href="<?= base_url('login') ?>" class="text-primary fw-bold">Login</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
