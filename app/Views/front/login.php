<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="cititrans-card p-4 p-md-5">
                <div class="text-center mb-4">
                    <h4 class="fw-bold mb-1"><i class="bi bi-box-arrow-in-right text-primary me-2"></i> Login Pengguna</h4>
                    <p class="text-secondary small">Masuk ke akun SpeedExpress Anda</p>
                </div>

                <form action="<?= base_url('login') ?>" method="POST">
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Alamat Email</label>
                        <input type="email" name="email" class="form-control form-control-lg" placeholder="email@domain.com" required>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg" placeholder="••••••••" required>
                    </div>

                    <button type="submit" class="btn btn-cititrans btn-lg w-100 py-3 mb-3">
                        Login Sekarang <i class="bi bi-arrow-right-short ms-1"></i>
                    </button>

                    <div class="text-center small">
                        Belum punya akun? <a href="<?= base_url('register') ?>" class="text-primary fw-bold">Daftar Member</a>
                    </div>
                </form>

                <hr class="my-4">

                <!-- Quick Login Demo Credentials Hint -->
                <div class="bg-light p-3 rounded-3 small">
                    <div class="fw-bold text-dark mb-1"><i class="bi bi-key-fill text-warning me-1"></i> Akun Pengujian Quick Login:</div>
                    <ul class="list-unstyled mb-0 text-muted">
                        <li><strong>Super Admin:</strong> admin@speed.test / password123</li>
                        <li><strong>Petugas Dermaga:</strong> dermaga@speed.test / password123</li>
                        <li><strong>Member:</strong> dewi@gmail.com / password123</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
