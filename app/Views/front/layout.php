<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'SB ANDALAS - Pemesanan Tiket Speed Boat Resmi') ?></title>
    
    <link rel="manifest" href="<?= base_url('manifest.json') ?>">
    <meta name="theme-color" content="#0B3B60">

    <!-- Fonts & Icons -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Bootstrap 5.3 CSS & Custom Theme -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/cititrans-theme.css') ?>">
</head>
<body>

    <!-- BMK Official White Header Navbar -->
    <header class="sticky-top pelni-header-white">
        <div class="container-fluid px-lg-5">
            <div class="d-flex justify-content-between align-items-center">
                <!-- Left: ANDALAS Brand Logo -->
                <a class="pelni-brand-logo" href="<?= base_url() ?>">
                    <i class="bi bi-compass-fill"></i> ANDALAS
                    <span class="d-none d-xl-inline-block ms-2 text-muted fw-bold" style="font-size: 0.72rem; letter-spacing: 0.5px;">SPEED EXPRESS</span>
                </a>

                <!-- Center: Main Navigation Menu -->
                <nav class="navbar navbar-expand-lg p-0">
                    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#bmkNav">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="bmkNav">
                        <ul class="navbar-nav pelni-nav-menu mx-auto">
                            <li class="nav-item"><a class="nav-link <?= (url_is('/')) ? 'active' : '' ?>" href="<?= base_url() ?>">BERANDA</a></li>
                            <li class="nav-item"><a class="nav-link <?= (url_is('search*')) ? 'active' : '' ?>" href="<?= base_url('search?origin_id=1&destination_id=2') ?>">RESERVASI TIKET</a></li>
                            <li class="nav-item"><a class="nav-link <?= (url_is('booking/manage*')) ? 'active' : '' ?>" href="<?= base_url('booking/manage') ?>"><i class="bi bi-ticket-perforated me-1 text-warning"></i> KELOLA PESANAN</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= base_url('#popular-routes') ?>">LAYANAN</a></li>
                            <li class="nav-item"><a class="nav-link" href="<?= base_url('#faq') ?>">FAQ</a></li>
                        </ul>
                    </div>
                </nav>

                <!-- Right: Contact Center 162 & Language / User Dropdown -->
                <div class="d-flex align-items-center gap-3">
                    <!-- Language selector -->
                    <div class="dropdown d-none d-md-block">
                        <button class="btn btn-sm btn-light border-0 fw-bold dropdown-toggle text-muted" type="button" data-bs-toggle="dropdown">
                            ID
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end small">
                            <li><a class="dropdown-item fw-bold" href="#">ID - Indonesia</a></li>
                            <li><a class="dropdown-item" href="#">EN - English</a></li>
                        </ul>
                    </div>

                    <!-- Contact Center 162 -->
                    <a href="tel:162" class="pelni-contact-center d-none d-sm-flex">
                        <i class="bi bi-headset"></i>
                        <div>
                            <span class="pelni-contact-label">CONTACT CENTER</span>
                            <span>(021) 162</span>
                        </div>
                    </a>

                    <!-- User Auth Dropdown / Dark Mode -->
                    <button class="btn btn-outline-secondary btn-sm rounded-circle px-2" id="darkModeToggle" title="Toggle Mode">
                        <i class="bi bi-moon-stars-fill" id="themeIcon"></i>
                    </button>

                    <?php if (session()->get('is_logged_in')): ?>
                        <div class="dropdown">
                            <button class="btn btn-primary text-white btn-sm dropdown-toggle rounded-pill px-3 fw-bold" type="button" data-bs-toggle="dropdown">
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
                        <a href="<?= base_url('login') ?>" class="btn btn-outline-primary btn-sm px-3 rounded-pill fw-bold">Login</a>
                        <a href="<?= base_url('register') ?>" class="btn btn-primary text-white btn-sm px-3 rounded-pill fw-bold">Daftar</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </header>

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

    <!-- BMK Corporate Footer -->
    <footer class="text-white pt-5 pb-4 mt-5" style="background-color: #002340;">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h4 class="fw-bold text-white mb-2"><i class="bi bi-compass-fill text-primary me-2"></i>ANDALAS</h4>
                    <h6 class="text-white-50 small mb-3">SB ANDALAS</h6>
                    <p class="text-secondary small">SB ANDALAS - Layanan Pemesanan Tiket Speed Boat Penyeberangan Cepat, Aman, dan Terpercaya Melayani Rute Bulungan dan Tarakan.</p>
                </div>
                <div class="col-lg-2">
                    <h6 class="text-uppercase fw-bold mb-3 text-warning">Layanan</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><a href="<?= base_url() ?>" class="text-secondary text-decoration-none">Beranda</a></li>
                        <li class="mb-2"><a href="<?= base_url('search?origin_id=1&destination_id=2') ?>" class="text-secondary text-decoration-none">Pesan Tiket Kapal</a></li>
                        <li class="mb-2"><a href="#popular-routes" class="text-secondary text-decoration-none">Jadwal & Rute</a></li>
                        <li class="mb-2"><a href="#faq" class="text-secondary text-decoration-none">Bantuan & FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-uppercase fw-bold mb-3 text-warning">Contact Center 24/7</h6>
                    <ul class="list-unstyled text-secondary small">
                        <li class="mb-2"><i class="bi bi-telephone-fill text-warning me-2"></i> Call Center: (021) 162</li>
                        <li class="mb-2"><i class="bi bi-whatsapp text-success me-2"></i> WhatsApp: 0811-162-1-162</li>
                        <li class="mb-2"><i class="bi bi-envelope-fill me-2"></i> Email: kontak@andalas.co.id</li>
                    </ul>
                </div>
                <div class="col-lg-3">
                    <h6 class="text-uppercase fw-bold mb-3 text-warning">Pembayaran Resmi</h6>
                    <p class="text-secondary small mb-2">Midtrans & Multi Payment Gateway:</p>
                    <div class="d-flex flex-wrap gap-2 text-white fs-3">
                        <i class="bi bi-qr-code-scan text-warning" title="QRIS Standard"></i>
                        <i class="bi bi-bank text-info" title="Virtual Account Mandiri / BNI / BRI / BCA"></i>
                        <i class="bi bi-wallet2 text-success" title="GoPay & E-Wallet"></i>
                    </div>
                </div>
            </div>
            <hr class="border-secondary my-4 opacity-50">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center text-secondary small">
                <p class="mb-0">&copy; <?= date('Y') ?> SB ANDALAS. All rights reserved.</p>
                <p class="mb-0">Speed Express Transport</p>
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
