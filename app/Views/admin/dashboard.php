<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<!-- Stat Metric Cards -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small text-uppercase opacity-75 fw-bold">Total Penjualan</div>
                    <div class="display-6 fw-extrabold my-1"><?= number_format($metrics['total_sales']) ?></div>
                    <div class="small opacity-75">Tiket Lunas</div>
                </div>
                <i class="bi bi-ticket-perforated-fill display-4 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-success text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small text-uppercase opacity-75 fw-bold">Pendapatan Hari Ini</div>
                    <div class="fs-3 fw-extrabold my-1">Rp <?= number_format($metrics['today_revenue'], 0, ',', '.') ?></div>
                    <div class="small opacity-75">Real-time Midtrans</div>
                </div>
                <i class="bi bi-wallet2 display-4 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-info text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small text-uppercase opacity-75 fw-bold">Pendapatan Bulanan</div>
                    <div class="fs-3 fw-extrabold my-1">Rp <?= number_format($metrics['monthly_revenue'], 0, ',', '.') ?></div>
                    <div class="small opacity-75">Bulan Ini</div>
                </div>
                <i class="bi bi-graph-up-arrow display-4 opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="card stat-card p-3 bg-dark text-white">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <div class="small text-uppercase opacity-75 fw-bold">Trip Hari Ini</div>
                    <div class="display-6 fw-extrabold my-1"><?= number_format($metrics['today_trips']) ?></div>
                    <div class="small opacity-75"><?= number_format($metrics['active_boats']) ?> Speedboat Aktif</div>
                </div>
                <i class="bi bi-boat-front-fill display-4 opacity-50 text-cyan"></i>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3"><i class="bi bi-bar-chart-line-fill text-primary me-2"></i> Grafik Tren Penjualan (7 Hari Terakhir)</h5>
            <canvas id="salesTrendChart" style="max-height: 300px;"></canvas>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <h5 class="fw-bold mb-3"><i class="bi bi-pie-chart-fill text-warning me-2"></i> Rute Terlaris</h5>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Rute</th>
                            <th class="text-end">Transaksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($metrics['top_routes'] as $r): ?>
                            <tr>
                                <td><strong class="text-dark"><?= esc($r['origin']) ?> &rarr; <?= esc($r['destination']) ?></strong></td>
                                <td class="text-end"><span class="badge bg-primary rounded-pill"><?= $r['total_bookings'] ?> Booking</span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        const salesData = <?= json_encode($metrics['sales_trend']) ?>;

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: salesData.labels || [],
                datasets: [{
                    label: 'Pendapatan (Rp)',
                    data: salesData.data || [],
                    borderColor: '#0D6EFD',
                    backgroundColor: 'rgba(13, 110, 253, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
<?= $this->endSection() ?>
