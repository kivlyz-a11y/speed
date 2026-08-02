<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($title ?? 'Dashboard Admin Operasional') ?> - SpeedExpress AdminLTE 4</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- AdminLTE 4 & Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/cititrans-theme.css') ?>">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f4f6f9; }
        
        .sidebar { 
            width: 260px; 
            height: 100vh; 
            position: fixed; 
            top: 0; 
            left: 0; 
            background-color: #0F2240; 
            color: #fff; 
            z-index: 1045; 
            transition: transform 0.3s ease-in-out; 
            overflow-y: auto;
        }
        
        .sidebar .nav-link { 
            color: rgba(255,255,255,0.7); 
            padding: 12px 20px; 
            font-size: 0.95rem; 
            display: flex; 
            align-items: center; 
            border-radius: 8px; 
            margin: 4px 12px; 
        }
        
        .sidebar .nav-link:hover, .sidebar .nav-link.active { 
            color: #fff; 
            background-color: rgba(255,255,255,0.12); 
            font-weight: 600; 
        }
        
        .main-wrapper { 
            margin-left: 260px; 
            min-height: 100vh; 
            display: flex; 
            flex-direction: column; 
            transition: margin-left 0.3s ease-in-out; 
        }
        
        .top-navbar { 
            background-color: #fff; 
            border-bottom: 1px solid #e5e7eb; 
            padding: 12px 24px; 
        }
        
        .brand-header { 
            padding: 20px; 
            border-bottom: 1px solid rgba(255,255,255,0.1); 
            font-family: 'Outfit', sans-serif; 
            font-weight: 800; 
            font-size: 1.3rem; 
            color: #fff; 
        }

        .sidebar-backdrop {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
        }

        /* Mobile Responsive Styles */
        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .sidebar-backdrop.show {
                display: block;
            }
            .main-wrapper {
                margin-left: 0 !important;
            }
        }
    </style>
</head>
<body>

    <!-- Sidebar Backdrop for Mobile -->
    <div class="sidebar-backdrop" id="sidebarBackdrop"></div>

    <!-- Sidebar AdminLTE 4 Standard -->
    <aside class="sidebar" id="adminSidebar">
        <div class="brand-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <i class="bi bi-water me-2 text-cyan"></i> SpeedExpress <span class="badge bg-primary text-white fs-6 ms-2">ADMIN</span>
            </div>
            <button class="btn btn-sm text-white d-lg-none" id="closeSidebarBtn">
                <i class="bi bi-x-lg fs-5"></i>
            </button>
        </div>

        <ul class="nav flex-column mt-3">
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/dashboard')) ? 'active' : '' ?>" href="<?= base_url('admin/dashboard') ?>">
                    <i class="bi bi-speedometer2 me-3 fs-5"></i> Dashboard
                </a>
            </li>

            <li class="text-uppercase text-muted px-4 mt-3 mb-1 fw-bold" style="font-size: 0.75rem;">Operasional Dermaga</li>
            
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/checkin/scanner')) ? 'active' : '' ?>" href="<?= base_url('admin/checkin/scanner') ?>">
                    <i class="bi bi-qr-code-scan me-3 fs-5"></i> Scanner QR Check-In
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/checkin/manifest*')) ? 'active' : '' ?>" href="<?= base_url('admin/checkin/manifest/1') ?>">
                    <i class="bi bi-clipboard-data-fill me-3 fs-5"></i> Manifest Boarding
                </a>
            </li>

            <li class="text-uppercase text-muted px-4 mt-3 mb-1 fw-bold" style="font-size: 0.75rem;">Master Data Armada</li>
            
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/master/locations')) ? 'active' : '' ?>" href="<?= base_url('admin/master/locations') ?>">
                    <i class="bi bi-geo-alt-fill me-3 fs-5"></i> Kota & Dermaga
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/master/boats')) ? 'active' : '' ?>" href="<?= base_url('admin/master/boats') ?>">
                    <i class="bi bi-tsunami me-3 fs-5"></i> Armada Speedboat
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/master/routes')) ? 'active' : '' ?>" href="<?= base_url('admin/master/routes') ?>">
                    <i class="bi bi-signpost-split-fill me-3 fs-5"></i> Master Rute
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/master/schedules')) ? 'active' : '' ?>" href="<?= base_url('admin/master/schedules') ?>">
                    <i class="bi bi-calendar-week-fill me-3 fs-5"></i> Jadwal Keberangkatan
                </a>
            </li>

            <li class="text-uppercase text-muted px-4 mt-3 mb-1 fw-bold" style="font-size: 0.75rem;">Keuangan & Laporan</li>
            
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/refunds*')) ? 'active' : '' ?>" href="<?= base_url('admin/refunds') ?>">
                    <i class="bi bi-arrow-counterclockwise me-3 fs-5"></i> Pengajuan Refund
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= (url_is('admin/reports/sales')) ? 'active' : '' ?>" href="<?= base_url('admin/reports/sales') ?>">
                    <i class="bi bi-file-earmark-bar-graph-fill me-3 fs-5"></i> Laporan Penjualan
                </a>
            </li>
        </ul>
    </aside>

    <!-- Main Wrapper -->
    <div class="main-wrapper">
        <!-- Top Navbar -->
        <header class="top-navbar d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-outline-dark btn-sm me-3 rounded-3" id="toggleSidebarBtn" title="Toggle Sidebar">
                    <i class="bi bi-list fs-5"></i>
                </button>
                <h5 class="mb-0 fw-bold d-none d-sm-block"><?= esc($title ?? 'Dashboard Admin Operasional') ?></h5>
            </div>
            
            <div class="d-flex align-items-center gap-2 gap-sm-3">
                <a href="<?= base_url() ?>" target="_blank" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
                    <i class="bi bi-globe me-1"></i> <span class="d-none d-sm-inline">Website Utama</span>
                </a>
                
                <div class="dropdown">
                    <button class="btn btn-dark btn-sm rounded-circle px-2" data-bs-toggle="dropdown">
                        <i class="bi bi-person-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow">
                        <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right me-2"></i> Logout System</a></li>
                    </ul>
                </div>
            </div>
        </header>

        <!-- Dynamic Content Area -->
        <div class="p-3 p-md-4 flex-grow-1 overflow-x-hidden">
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $this->renderSection('content') ?>
        </div>

        <footer class="bg-white border-top p-3 text-center text-muted small">
            &copy; <?= date('Y') ?> SpeedExpress - CodeIgniter 4 Enterprise Speed Boat System.
        </footer>
    </div>

    <!-- JS Libraries -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const sidebar = document.getElementById('adminSidebar');
            const toggleBtn = document.getElementById('toggleSidebarBtn');
            const closeBtn = document.getElementById('closeSidebarBtn');
            const backdrop = document.getElementById('sidebarBackdrop');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                backdrop.classList.toggle('show');
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                backdrop.classList.remove('show');
            }

            if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
            if (closeBtn) closeBtn.addEventListener('click', closeSidebar);
            if (backdrop) backdrop.addEventListener('click', closeSidebar);
        });
    </script>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
