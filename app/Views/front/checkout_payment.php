<?= $this->extend('front/layout') ?>

<?= $this->section('content') ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">

            <!-- Countdown Timer Header -->
            <div class="cititrans-card p-4 mb-4 text-center bg-warning bg-opacity-10 border border-warning">
                <span class="badge bg-warning text-dark fw-bold mb-2"><i class="bi bi-clock-history me-1"></i> WAKTU PEMBAYARAN</span>
                <h5 class="fw-bold mb-1">Selesaikan Pembayaran Sebelum Expired</h5>
                <div class="display-6 fw-extrabold text-danger my-2" id="countdownTimer">30:00</div>
                <p class="text-muted small mb-0">Order ID / Kode Booking: <strong class="text-dark"><?= esc($booking['booking_code']) ?></strong></p>
            </div>

            <!-- Booking Order Summary Card -->
            <div class="cititrans-card p-4 mb-4">
                <h5 class="fw-bold border-bottom pb-3 mb-3 d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-receipt-cutoff text-primary me-2"></i> Ringkasan Pemesanan</span>
                    <span class="badge bg-danger rounded-pill">UNPAID</span>
                </h5>

                <div class="row g-3 mb-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Nama Pemesan</small>
                        <strong class="text-dark"><?= esc($booking['customer_name']) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Kontak WhatsApp</small>
                        <strong class="text-dark"><?= esc($booking['customer_phone']) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Email E-Ticket</small>
                        <strong class="text-dark"><?= esc($booking['customer_email']) ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Jumlah Penumpang</small>
                        <strong class="text-dark"><?= esc($booking['total_passengers']) ?> Penumpang</strong>
                    </div>
                </div>

                <h6 class="fw-bold small text-uppercase text-muted mt-4 mb-2">Daftar Kursi & Penumpang</h6>
                <div class="table-responsive mb-3">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr>
                                <th>No. Kursi</th>
                                <th>Nama Penumpang</th>
                                <th class="text-end">Harga Ticket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($passengers as $p): ?>
                                <tr>
                                    <td><span class="badge bg-primary"><?= esc($p['seat_number']) ?></span></td>
                                    <td><?= esc($p['passenger_name']) ?></td>
                                    <td class="text-end">Rp <?= number_format($p['price'], 0, ',', '.') ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-light p-3 rounded-3">
                    <div class="d-flex justify-content-between mb-1 text-muted">
                        <span>Subtotal Tiket</span>
                        <span>Rp <?= number_format($booking['total_amount'], 0, ',', '.') ?></span>
                    </div>
                    <?php if ($booking['discount_amount'] > 0): ?>
                        <div class="d-flex justify-content-between mb-1 text-success fw-bold">
                            <span>Diskon Voucher (<?= esc($booking['voucher_code']) ?>)</span>
                            <span>- Rp <?= number_format($booking['discount_amount'], 0, ',', '.') ?></span>
                        </div>
                    <?php endif; ?>
                    <hr class="my-2">
                    <div class="d-flex justify-content-between align-items-center fs-5 fw-extrabold text-primary">
                        <span>Total Pembayaran</span>
                        <span>Rp <?= number_format($booking['final_amount'], 0, ',', '.') ?></span>
                    </div>
                </div>
            </div>

            <!-- Payment Action Card -->
            <div class="cititrans-card p-4 text-center">
                <h5 class="fw-bold mb-3">Pilih Metode Pembayaran</h5>
                <p class="text-muted small mb-4">Pembayaran otomatis dikonfirmasi via Midtrans Payment Gateway (QRIS, Bank Transfer BCA/BNI/Mandiri, GoPay, ShopeePay).</p>

                <div class="d-grid gap-3">
                    <?php if (!empty($payment['snap_token']) && !str_contains($payment['snap_token'], 'SNAP-MOCK')): ?>
                        <button id="payButton" class="btn btn-cititrans btn-lg py-3 shadow">
                            Bayar Sekarang via Midtrans <i class="bi bi-shield-check ms-2"></i>
                        </button>
                    <?php endif; ?>

                    <!-- Simulated Instant Pay Button (Mock Confirmation) -->
                    <a href="<?= base_url('checkout/mock-pay/' . $booking['booking_code']) ?>" class="btn btn-cititrans-accent btn-lg py-3 shadow">
                        <i class="bi bi-qr-code-scan me-2"></i> Simulasi Bayar Instan / QRIS (Konfirmasi Otomatis)
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php 
$expiredTimestamp = !empty($booking['expired_at']) ? strtotime($booking['expired_at']) : (time() + 1800);
$nowTimestamp     = time();
$remainingSeconds = max(0, $expiredTimestamp - $nowTimestamp);
?>

<?= $this->section('scripts') ?>
<!-- Midtrans Snap JS (Sandbox mode) -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="<?= env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-DUMMY_KEY') ?>"></script>
<script>
    // Real Remaining Seconds from Server expired_at timestamp
    let duration = <?= (int) $remainingSeconds ?>;
    const timerDisplay = document.getElementById('countdownTimer');
    
    function updateTimer() {
        if (duration <= 0) {
            timerDisplay.innerText = "EXPIRED";
            const payBtn = document.getElementById('payButton');
            if (payBtn) payBtn.disabled = true;
            return;
        }
        const minutes = Math.floor(duration / 60);
        const seconds = duration % 60;
        timerDisplay.innerText = `${minutes < 10 ? '0' : ''}${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;
        duration--;
    }

    updateTimer();
    const interval = setInterval(() => {
        if (duration < 0) {
            clearInterval(interval);
            timerDisplay.innerText = "EXPIRED";
            const payBtn = document.getElementById('payButton');
            if (payBtn) payBtn.disabled = true;
        } else {
            updateTimer();
        }
    }, 1000);

    const payBtn = document.getElementById('payButton');
    if (payBtn) {
        payBtn.addEventListener('click', function () {
            snap.pay('<?= $payment["snap_token"] ?>', {
                onSuccess: function(result){
                    window.location.href = '<?= base_url("booking/success/" . $booking["booking_code"]) ?>';
                },
                onPending: function(result){
                    alert("Menunggu pembayaran...");
                },
                onError: function(result){
                    alert("Pembayaran gagal!");
                }
            });
        });
    }
</script>
<?= $this->endSection() ?>
