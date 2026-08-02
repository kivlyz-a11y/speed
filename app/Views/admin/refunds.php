<?= $this->extend('admin/layout') ?>

<?= $this->section('content') ?>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="fw-bold mb-0"><i class="bi bi-arrow-counterclockwise text-primary me-2"></i> Pengajuan Refund Penumpang</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle border">
            <thead class="table-dark">
                <tr>
                    <th>Kode Refund</th>
                    <th>Kode Booking</th>
                    <th>Pemesan</th>
                    <th>Alasan Refund</th>
                    <th>Total Bayar</th>
                    <th>Potongan (10%)</th>
                    <th>Jumlah Refund</th>
                    <th>Bank Transfer</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($refunds as $r): ?>
                    <tr>
                        <td><span class="badge bg-secondary"><?= esc($r['refund_code']) ?></span></td>
                        <td><strong><?= esc($r['booking_code']) ?></strong></td>
                        <td><?= esc($r['customer_name']) ?><br><small class="text-muted"><?= esc($r['customer_phone']) ?></small></td>
                        <td><small><?= esc($r['reason']) ?></small></td>
                        <td>Rp <?= number_format($r['total_paid'], 0, ',', '.') ?></td>
                        <td class="text-danger">- Rp <?= number_format($r['deduction_amount'], 0, ',', '.') ?></td>
                        <td><strong class="text-success">Rp <?= number_format($r['refund_amount'], 0, ',', '.') ?></strong></td>
                        <td><small><?= esc($r['bank_name']) ?> - <?= esc($r['account_number']) ?><br>a.n <?= esc($r['account_holder']) ?></small></td>
                        <td>
                            <?php 
                                $statusBadge = 'secondary';
                                if ($r['status'] === 'approved') $statusBadge = 'primary';
                                if ($r['status'] === 'completed') $statusBadge = 'success';
                                if ($r['status'] === 'rejected') $statusBadge = 'danger';
                            ?>
                            <span class="badge bg-<?= $statusBadge ?>"><?= strtoupper($r['status']) ?></span>
                        </td>
                        <td>
                            <?php if ($r['status'] === 'requested'): ?>
                                <form action="<?= base_url('admin/refunds/update-status/' . $r['id']) ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="status" value="approved">
                                    <button class="btn btn-sm btn-success fw-bold me-1" title="Setujui Refund"><i class="bi bi-check-lg"></i> Approve</button>
                                </form>
                                <form action="<?= base_url('admin/refunds/update-status/' . $r['id']) ?>" method="POST" class="d-inline">
                                    <input type="hidden" name="status" value="rejected">
                                    <button class="btn btn-sm btn-outline-danger fw-bold" title="Tolak Refund"><i class="bi bi-x-lg"></i> Reject</button>
                                </form>
                            <?php else: ?>
                                <small class="text-muted">Selesai</small>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
