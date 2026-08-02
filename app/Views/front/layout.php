<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SpeedExpress - Pemesanan Tiket Speed Boat') ?></title>
    
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#0F2240">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5.3 CSS & Cititrans Custom Theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/cititrans-theme.css') ?>">
</head>
<body>

    <!-- Sticky Cititrans Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top cititrans-navbar navbar-dark">
        <div class="container">
            <a class="navbar-brand cititrans-brand d-flex align-items-center" href="<?= base_url() ?>">
                <i class="bi bi-water me-2 text-cyan"></i>
                Speed<span>Express</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navContent">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" href="<?= base_url() ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= base_url('search?origin_id=1&destination_id=2') ?>">Cari Jadwal</a></li>
                    <li class="nav-item"><a class="nav-link" href="#faq">FAQ & Bantuan</a></li>
                </ul>

                <div class="d-flex align-items-center gap-3">
                    <button class="btn btn-outline-light btn-sm rounded-circle px-2" id="darkModeToggle" title="Toggle Dark Mode">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>

                    <?php if (session()->get('is_logged_in')): ?>
                        <div class="dropdown">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle rounded-pill px-3" type="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i> <?= esc(session()->get('user_name')) ?>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow">
                                <?php if (in_array(session()->get('role_slug'), ['super-admin', 'admin-operasional', 'kasir', 'petugas-dermaga', 'manajer'])): ?>
                                    <li><a class="dropdown-item fw-bold text-primary" href="<?= base_url('admin/dashboard') ?>"><i class="bi bi-speedometer2 me-2"></i> Dashboard Admin</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                <?php endif; ?>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sm px-3 rounded-pill">Login</a>
                        <a href="<?= base_url('register') ?>" class="btn btn-cititrans-accent btn-sm px-3 rounded-pill">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content Slot -->
    <main>
        <?php if (session()->getFlashdata('error')): ?>
            <div class="container mt-3">
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?php if (session()->getFlashdata('success')): ?>
            <div class="container mt-3">
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </main>

    <!-- Cititrans Footer -->
    <footer class="bg-dark text-white pt-5 pb-4 mt-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="cititrans-brand text-white mb-3"><i class="bi bi-water text-cyan me-2"></i>Speed<span>Express</span></h5>
                    <p class="text-secondary small">Layanan Pemesanan Tiket Speed Boat Cepat, Aman, dan Nyaman Berstandar Cititrans. Melayani Rute Bulungan dan Tarakan.</p>
                </div>
                <div class="col-lg-2">
                    <h6 class="text-uppercase fw-bold mb-3 text-cyan">Navigasi</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><a href="<?= base_url() ?>" class="text-secondary text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="<?= base_url('search?origin_id=1&destination_id=2') ?>" class="text-secondary text-decoration-none">Cari Jadwal</a></li>
                        <li class="mb-2"><a href="#faq" class="text-secondary text-decoration-none">FAQ & Bantuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-uppercase fw-bold mb-3 text-cyan">Rute Penyeberangan</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2">Bulungan &rarr; Tarakan</li>
                        <li class="mb-2">Tarakan &rarr; Bulungan</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-uppercase fw-bold mb-3 text-cyan">Metode Pembayaran</h6>
                    <p class="text-secondary small mb-2">Didukung oleh Midtrans Payment Gateway:</p>
                    <div class="d-flex flex-wrap gap-2 text-white fs-4">
                        <i class="bi bi-qr-code-scan" title="QRIS"></i>
                        <i class="bi bi-bank" title="Virtual Account"></i>
                        <i class="bi bi-wallet2" title="GoPay & E-Wallet"></i>
                        <i class="bi bi-credit-card-2-front" title="Credit Card"></i>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-secondary small">
                <p class="mb-0">&copy; <?= date('Y') ?> SpeedExpress. All rights reserved.</p>
                <p class="mb-0">Designed with <i class="bi bi-heart-fill text-danger"></i> Cititrans User Experience Standard</p>
            </div>
        </div>
    </footer>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Dark Mode Controller
        const toggleBtn = document.getElementById('darkModeToggle');
        const themeIcon = document.getElementById('themeIcon');
        toggleBtn.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            themeIcon.className = newTheme === 'dark' ? 'bi bi-sun-fill text-warning' : 'bi bi-moon-stars-fill';
        });

        // Register Service Worker for PWA
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('<?= base_url("sw.js") ?>').catch(err => console.log('SW register failed:', err));
        }
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
